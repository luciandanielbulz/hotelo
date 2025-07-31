<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankData;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\AutoCategorizationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BankDataController extends Controller
{
    protected $autoCategorizationService;

    public function __construct(AutoCategorizationService $autoCategorizationService)
    {
        $this->autoCategorizationService = $autoCategorizationService;
    }

    // Zeigt alle Bankdaten/Ausgaben an
    public function index(Request $request)
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        // Debug: Logge die Suchparameter
        Log::info('BankData search parameters', [
            'partner' => $request->get('partner'),
            'amount' => $request->get('amount'),
            'date' => $request->get('date'),
            'all_params' => $request->all(),
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_client_id' => $user->client_id,
            'is_authenticated' => Auth::check(),
            'current_user' => Auth::user() ? Auth::user()->email : 'not authenticated'
        ]);

        // Basis-Query
        $query = BankData::where('client_id', $clientId)
            ->with('category')
            ->orderBy('date', 'desc');

        // Suchfilter anwenden
        if ($request->filled('partner')) {
            $partner = $request->get('partner');
            // Case-insensitive Suche
            $query->whereRaw('LOWER(partnername) LIKE ?', ['%' . strtolower($partner) . '%']);
            Log::info('Applied partner filter', [
                'partner' => $partner,
                'search_pattern' => '%' . strtolower($partner) . '%',
                'client_id' => $clientId
            ]);
        }

        if ($request->filled('amount')) {
            $amount = $request->get('amount');
            // Suche nach exaktem Betrag (positive und negative Werte)
            $query->where(function($q) use ($amount) {
                $q->where('amount', $amount)
                  ->orWhere('amount', -$amount);
            });
            Log::info('Applied amount filter', ['amount' => $amount]);
        }

        if ($request->filled('date')) {
            $date = $request->get('date');
            $query->whereDate('date', $date);
            Log::info('Applied date filter', ['date' => $date]);
        }

        // Kategorien für Filter laden
        $categories = Category::active()->forClient($clientId)->get();
        $filteredCategories = $categories;
        $incomeCategories = $categories->where('type', 'income')->values();
        $expenseCategories = $categories->where('type', 'expense')->values();

        // Bankdaten mit Pagination laden
        // Wenn Suchparameter vorhanden sind, zur ersten Seite zurückkehren
        if ($request->filled('partner') || $request->filled('amount') || $request->filled('date')) {
            // Zur ersten Seite zurückkehren bei Suche
            $bankData = $query->paginate(15, ['*'], 'page', 1)->appends($request->all());
            Log::info('Search performed, reset to page 1');
        } else {
            $bankData = $query->paginate(15);
        }
        
        // Debug: Zeige SQL-Query
        Log::info('SQL Query', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        // Debug: Logge die Anzahl der gefundenen Ergebnisse
        Log::info('BankData search results', [
            'total_results' => $bankData->total(),
            'current_page' => $bankData->currentPage(),
            'per_page' => $bankData->perPage(),
            'has_partner_filter' => $request->filled('partner'),
            'partner_search' => $request->get('partner'),
            'client_id' => $clientId
        ]);
        
        // Debug: Zeige einige Beispieldaten für A123
        if ($request->filled('partner') && strpos(strtolower($request->get('partner')), 'a123') !== false) {
            $a123Results = BankData::where('client_id', $clientId)
                ->whereRaw('LOWER(partnername) LIKE ?', ['%a123%'])
                ->take(5)
                ->get(['partnername', 'amount', 'date']);
            
            Log::info('A123 debug results', [
                'found_count' => $a123Results->count(),
                'sample_data' => $a123Results->toArray()
            ]);
        }

        // Suchwerte für die View vorbereiten
        $searchValues = [
            'partner' => $request->get('partner', ''),
            'amount' => $request->get('amount', ''),
            'date' => $request->get('date', '')
        ];

        return view('bankdata.index', compact('bankData', 'categories', 'filteredCategories', 'incomeCategories', 'expenseCategories', 'searchValues'));
    }

    /**
     * Automatically categorize all uncategorized transactions
     */
    public function autoCategorize(Request $request)
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        try {
            $results = $this->autoCategorizationService->categorizeTransactions(null, $clientId);

            return response()->json([
                'success' => true,
                'message' => "Automatische Kategorisierung abgeschlossen: {$results['categorized']} von {$results['total']} Transaktionen kategorisiert",
                'results' => $results
            ]);
        } catch (\Exception $e) {
            Log::error('Auto-categorization failed', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Fehler bei der automatischen Kategorisierung: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get keyword suggestions for categories
     */
    public function getKeywordSuggestions(Request $request)
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        try {
            $suggestions = $this->autoCategorizationService->getKeywordSuggestions($clientId);

            return response()->json([
                'success' => true,
                'suggestions' => $suggestions
            ]);
        } catch (\Exception $e) {
            Log::error('Keyword suggestions failed', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Fehler beim Abrufen der Keyword-Vorschläge: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test categorization for a specific transaction
     */
    public function testCategorization(Request $request, BankData $bankData)
    {
        $user = Auth::user();
        
        // Prüfe ob der Bankdatensatz zum aktuellen Client gehört
        if ($bankData->client_id !== $user->client_id) {
            return response()->json(['error' => 'Zugriff verweigert.'], 403);
        }

        try {
            $matches = $this->autoCategorizationService->testCategorization($bankData);

            return response()->json([
                'success' => true,
                'transaction' => $bankData,
                'matches' => $matches
            ]);
        } catch (\Exception $e) {
            Log::error('Test categorization failed', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Fehler beim Testen der Kategorisierung: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show auto-categorization statistics
     */
    public function autoCategorizationStats(Request $request)
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        $stats = [
            'total_transactions' => BankData::where('client_id', $clientId)->count(),
            'categorized_transactions' => BankData::where('client_id', $clientId)->whereNotNull('category_id')->count(),
            'uncategorized_transactions' => BankData::where('client_id', $clientId)->whereNull('category_id')->count(),
            'categories_with_keywords' => Category::active()->forClient($clientId)->whereNotNull('keywords')->where('keywords', '!=', '')->count(),
            'categories_without_keywords' => Category::active()->forClient($clientId)->where(function($query) {
                $query->whereNull('keywords')->orWhere('keywords', '');
            })->count(),
        ];

        $stats['categorization_rate'] = $stats['total_transactions'] > 0 
            ? round(($stats['categorized_transactions'] / $stats['total_transactions']) * 100, 2) 
            : 0;

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Add a new expense manually
     */
    public function addExpense(Request $request)
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        try {
            $validated = $request->validate([
                'partnername' => 'required|string|max:255',
                'amount' => 'required|numeric',
                'date' => 'required|date',
                'reference' => 'nullable|string|max:1000',
                'category_id' => 'nullable|exists:categories,id',
                'type' => 'required|in:income,expense'
            ]);

            // Ensure amount is negative for expenses, positive for income
            if ($validated['type'] === 'expense' && $validated['amount'] > 0) {
                $validated['amount'] = -$validated['amount'];
            } elseif ($validated['type'] === 'income' && $validated['amount'] < 0) {
                $validated['amount'] = abs($validated['amount']);
            }

            // Create new bank data entry
            $bankData = BankData::create([
                'client_id' => $clientId,
                'partnername' => $validated['partnername'],
                'amount' => $validated['amount'],
                'date' => $validated['date'],
                'reference' => $validated['reference'] ?? '',
                'category_id' => $validated['category_id'],
                'type' => $validated['type'],
                'partneriban' => '', // Empty for manual entries
                'partnerbic' => '', // Empty for manual entries
                'valuta' => $validated['date'], // Use same date as valuta
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Log::info('Manual expense added', [
                'bank_data_id' => $bankData->id,
                'partnername' => $bankData->partnername,
                'amount' => $bankData->amount,
                'type' => $bankData->type,
                'category_id' => $bankData->category_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ausgabe erfolgreich hinzugefügt!',
                'bank_data' => $bankData
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to add manual expense', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'error' => 'Fehler beim Hinzufügen der Ausgabe: ' . $e->getMessage()
            ], 500);
        }
    }

    // Zeigt das Upload-Formular
    public function showUploadForm()
    {
        //dd('test');
        return view('bankdata.upload');
    }

    // Verarbeitet den CSV-Upload
    public function uploadJSON(Request $request)
{
    $user = Auth::user();
    $client_id = $user->client_id;

    // Validierung
    $request->validate([
        'json_file' => 'required|file|mimes:json|max:2048',
        'auto_detect_type' => 'required|in:auto,manual',
        'default_type' => 'required_if:auto_detect_type,manual|in:income,expense',
        'overwrite_duplicates' => 'boolean',
    ]);


    // JSON-Datei lesen
    $file = $request->file('json_file');

    $jsonData = file_get_contents($file->getRealPath());

    $data = json_decode($jsonData, true); // JSON in ein Array umwandeln


    if (json_last_error() !== JSON_ERROR_NONE) {
        return redirect()->back()->with('error', 'Ungültiges JSON-Format.');
    }

    // Debug: Logge die JSON-Struktur
    Log::info('JSON upload started', [
        'data_count' => count($data),
        'sample_data' => array_slice($data, 0, 2)
    ]);

    // Zähler für verschiedene Transaktionstypen
    $processedCount = 0;
    $bankCostCount = 0;
    $skippedCount = 0;

    // Verarbeitung der Daten
    foreach ($data as $index => $row) {
        // Partner-Name aus verschiedenen Quellen extrahieren
        $partnerName = $row['partnerName'] ?? null;
        if (empty($partnerName) || $partnerName === null) {
            // Fallback: Verwende reference als Partner-Name
            $partnerName = $row['reference'] ?? 'Unbekannt';
            
            // Kürze den Partner-Namen wenn er zu lang ist
            if (strlen($partnerName) > 100) {
                $partnerName = substr($partnerName, 0, 97) . '...';
            }
        }

        // Spezielle Behandlung für Bankkosten
        // Wenn es sich um Bankkosten handelt (Sollzinsen, Kontoführung, etc.)
        $isBankCost = false;
        $referenceLower = strtolower($row['reference'] ?? '');
        $partnerNameLower = strtolower($partnerName ?? '');
        
        // Debug: Logge alle Einträge für Analyse
        Log::info('Processing row', [
            'reference' => $row['reference'] ?? null,
            'partnerName' => $partnerName,
            'reference_lower' => $referenceLower,
            'partner_lower' => $partnerNameLower,
            'amount' => $row['amount'] ?? null
        ]);
        
        // Erweiterte Bankkosten-Erkennung
        $bankCostKeywords = [
            'sollzinsen',
            'kontoführung', 
            'kostenbeitrag',
            'konto-protect',
            'konto protect',
            'kontobeitrag',
            'kostenbeitrag f.',
            'kostenbeitrag für',
            'kostenbeitrag weitere',
            's konto-protect',
            'kostenbeitrag weitere leistung',
            'kostenbeitrag f. kontoführung',
            'weitere leistung',
            'kontoführung',
            'konto protect',
            's konto'
        ];
        
        // Kartenbehebungen-Erkennung
        $atmKeywords = [
            'sb-auszahlung',
            'sb auszahlung',
            'kartenbehebung',
            'atm',
            'automatenauszahlung',
            'bargeldauszahlung',
            'cash withdrawal',
            'geldautomat'
        ];
        
        // Bankkosten-Erkennung
        foreach ($bankCostKeywords as $keyword) {
            if (strpos($referenceLower, $keyword) !== false || 
                strpos($partnerNameLower, $keyword) !== false) {
                $isBankCost = true;
                Log::info('Bank cost keyword matched', [
                    'keyword' => $keyword,
                    'reference' => $row['reference'] ?? null,
                    'partnerName' => $partnerName,
                    'reference_lower' => $referenceLower,
                    'partner_lower' => $partnerNameLower
                ]);
                break;
            }
        }
        
        // Kartenbehebungen-Erkennung
        $isATMWithdrawal = false;
        foreach ($atmKeywords as $keyword) {
            if (strpos($referenceLower, $keyword) !== false || 
                strpos($partnerNameLower, $keyword) !== false) {
                $isATMWithdrawal = true;
                Log::info('ATM withdrawal keyword matched', [
                    'keyword' => $keyword,
                    'reference' => $row['reference'] ?? null,
                    'partnerName' => $partnerName,
                    'reference_lower' => $referenceLower,
                    'partner_lower' => $partnerNameLower
                ]);
                break;
            }
        }
        
        // Alternative Erkennung: Direkte Prüfung auf spezifische Referenzen
        if (!$isBankCost) {
            $specificReferences = [
                'Kostenbeitrag weitere Leistung',
                'Kostenbeitrag f. Kontoführung',
                's Konto-Protect',
                'Sollzinsen'
            ];
            
            foreach ($specificReferences as $specificRef) {
                if (strtolower($row['reference'] ?? '') === strtolower($specificRef)) {
                    $isBankCost = true;
                    Log::info('Bank cost specific reference matched', [
                        'specific_reference' => $specificRef,
                        'actual_reference' => $row['reference'] ?? null
                    ]);
                    break;
                }
            }
        }
        
        if ($isBankCost) {
            // Für Bankkosten: Verwende den Partner-Namen direkt aus reference
            $partnerName = $row['reference'] ?? $partnerName;
            
            // Kürze den Partner-Namen für Bankkosten auf maximal 100 Zeichen
            if (strlen($partnerName) > 100) {
                // Versuche, einen sinnvollen Teil zu extrahieren
                if (strpos(strtolower($partnerName), 'sollzinsen') !== false) {
                    $partnerName = 'Sollzinsen';
                } elseif (strpos(strtolower($partnerName), 'kontoführung') !== false) {
                    $partnerName = 'Kontoführung';
                } elseif (strpos(strtolower($partnerName), 'kostenbeitrag') !== false) {
                    $partnerName = 'Kostenbeitrag';
                } elseif (strpos(strtolower($partnerName), 'konto-protect') !== false) {
                    $partnerName = 'Konto-Protect';
                } else {
                    $partnerName = substr($partnerName, 0, 97) . '...';
                }
            }
            
            Log::info('Bank cost detected', [
                'original_partnername' => $row['partnerName'] ?? null,
                'reference' => $row['reference'] ?? null,
                'final_partnername' => $partnerName,
                'is_bank_cost' => $isBankCost,
                'amount_before_processing' => $row['amount'] ?? null
            ]);
        }
        
        if ($isATMWithdrawal) {
            // Für Kartenbehebungen: Verwende einen standardisierten Namen
            $partnerName = 'SB-Auszahlung (Kartenbehebung)';
            
            Log::info('ATM withdrawal detected', [
                'original_partnername' => $row['partnerName'] ?? null,
                'reference' => $row['reference'] ?? null,
                'final_partnername' => $partnerName,
                'is_atm_withdrawal' => $isATMWithdrawal,
                'amount_before_processing' => $row['amount'] ?? null
            ]);
        }

        // Prüfen und Zugriff auf PartnerAccount-Felder
        $partnerAccount = $row['partnerAccount'] ?? [];

        // Extrahiere spezifische Felder wie IBAN
        $iban = $partnerAccount['iban'] ?? null;
        $bic = $partnerAccount['bic'] ?? null;
        $accountNumber = $partnerAccount['number'] ?? null;
        $bankCode = $partnerAccount['bankCode'] ?? null;

        $amountdata = $row['amount'] ?? [];
        $amount = $amountdata['value']/100 ?? null;
        $currency = $amountdata['currency'] ?? null;

        // Spezielle Behandlung für Bankkosten - verschiedene JSON-Formate
        if ($isBankCost) {
            // Für Bankkosten: Stelle sicher, dass sie als Ausgaben markiert sind
            if ($amount >= 0) {
                $amount = -abs($amount); // Immer negativ machen
            }
            
            Log::info('Bank cost amount processed', [
                'original_amount' => $row['amount'] ?? null,
                'processed_amount' => $amount,
                'reference' => $row['reference'] ?? null,
                'is_bank_cost' => $isBankCost
            ]);
        }
        
        // Spezielle Behandlung für Kartenbehebungen
        if ($isATMWithdrawal) {
            // Für Kartenbehebungen: Stelle sicher, dass sie als Ausgaben markiert sind
            if ($amount >= 0) {
                $amount = -abs($amount); // Immer negativ machen
            }
            
            Log::info('ATM withdrawal amount processed', [
                'original_amount' => $row['amount'] ?? null,
                'processed_amount' => $amount,
                'reference' => $row['reference'] ?? null,
                'is_atm_withdrawal' => $isATMWithdrawal
            ]);
        }

        // Überspringe Datensätze mit Betrag 0 (Informations-Einträge)
        if ($amount == 0 || $amount === null) {
            Log::info('Skipping zero amount entry', [
                'reference' => $row['reference'] ?? null,
                'partnerName' => $row['partnerName'] ?? null,
                'amount' => $amount
            ]);
            $skippedCount++;
            continue; // Überspringe diesen Datensatz
        }
        
        // Datum konvertieren (falls nötig)
        $bookingDate = isset($row['booking']) ? \DateTime::createFromFormat('Y-m-d\TH:i:s.uP', $row['booking']) : null;
        $valuationDate = isset($row['valuation']) ? \DateTime::createFromFormat('Y-m-d\TH:i:s.uP', $row['valuation']) : null;

        $newreferencenumber = $row['referenceNumber'];

        // Für Bankkosten und Kartenbehebungen: Verwende eine Kombination aus referenceNumber und reference für Duplikat-Erkennung
        if ($isBankCost || $isATMWithdrawal) {
            $reference = BankData::where('referencenumber', '=', $newreferencenumber)
                ->where('reference', '=', $row['reference'] ?? '')
                ->where('client_id', $client_id)
                ->first();
        } else {
            $reference = BankData::where('referencenumber','=',$newreferencenumber)
                ->where('client_id', $client_id)
                ->first();
        }

        // Debug: Logge Duplikat-Prüfung
        if ($reference) {
            Log::info('Duplicate found', [
                'referencenumber' => $newreferencenumber,
                'existing_id' => $reference->id,
                'existing_partnername' => $reference->partnername,
                'existing_amount' => $reference->amount,
                'overwrite_duplicates' => $request->get('overwrite_duplicates', false)
            ]);
            
            // Wenn Überschreiben aktiviert ist, lösche den alten Eintrag
            if ($request->get('overwrite_duplicates', false)) {
                $reference->delete();
                Log::info('Existing entry deleted for overwrite', [
                    'referencenumber' => $newreferencenumber,
                    'deleted_id' => $reference->id
                ]);
                $reference = null; // Erlaube Erstellung des neuen Eintrags
            }
        }

        if (!$reference) {
            // Bestimme den Typ basierend auf den Upload-Einstellungen
            $type = 'expense'; // Standard
            if ($request->auto_detect_type === 'auto') {
                // Automatische Erkennung: positiv = Einnahmen, negativ = Ausgaben
                $type = $amount >= 0 ? 'income' : 'expense';
            } else {
                // Manuelle Auswahl
                $type = $request->default_type;
            }

            // Für Bankkosten: Immer als expense markieren
            if ($isBankCost) {
                $type = 'expense';
            }
            
            // Für Kartenbehebungen: Immer als expense markieren
            if ($isATMWithdrawal) {
                $type = 'expense';
            }

            // IBAN aus verschiedenen Quellen extrahieren
            $finalIban = $iban;
            if (empty($finalIban) && !empty($accountNumber) && !empty($bankCode)) {
                // Fallback: Konstruiere IBAN aus number und bankCode
                $finalIban = 'AT' . $bankCode . $accountNumber;
            }

            // In die Datenbank einfügen
            $bankwrite = BankData::create([
                'transaction_id' => $row['transactionId'] ?? null,
                'contained_transaction_id' => $row['containedTransactionId'] ?? null,
                'date' => $bookingDate ? $bookingDate->format('Y-m-d') : null,
                'partnername' => $partnerName,
                'partneriban' => $finalIban,
                'partnerbic' => $bic,
                'amount' => $amount,
                'currency' => $currency,
                'referencenumber' => $row['referenceNumber'] ?? null,
                'reference' => $row['reference'] ?? null,
                'client_id' => $client_id,
                'category_id' => null,
                'type' => $type,
            ]);

            Log::info('BankData created', [
                'id' => $bankwrite->id,
                'partnername' => $partnerName,
                'amount' => $amount,
                'type' => $type,
                'reference' => $row['reference'] ?? null
            ]);
            
            $processedCount++;
            if ($isBankCost) {
                $bankCostCount++;
            }
        }
    }

    // Finale Statistiken loggen
    Log::info('JSON upload completed', [
        'total_processed' => $processedCount,
        'bank_costs_processed' => $bankCostCount,
        'skipped_entries' => $skippedCount
    ]);


    return redirect()->back()->with('success', 'JSON-Datei erfolgreich hochgeladen und importiert!');
}

    // Kategorie für einen Bankdatensatz aktualisieren
    public function updateCategory(Request $request, BankData $bankData)
    {
        $user = Auth::user();
        
        // Prüfe ob der Bankdatensatz zum aktuellen Client gehört
        if ($bankData->client_id !== $user->client_id) {
            return response()->json(['error' => 'Zugriff verweigert.'], 403);
        }

        $request->validate([
            'category_id' => 'nullable|exists:categories,id'
        ]);

        // Debug-Logging
        \Log::info('Category update request', [
            'bankData_id' => $bankData->id,
            'category_id' => $request->category_id,
            'user_id' => $user->id
        ]);

        $bankData->update([
            'category_id' => $request->category_id
        ]);

        // Lade die aktualisierte Kategorie
        $bankData->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Kategorie erfolgreich aktualisiert.',
            'category' => $bankData->category ? [
                'name' => $bankData->category->name,
                'color' => $bankData->category->color
            ] : null
        ]);
    }

    // Zeigt das Bearbeitungsformular
    public function edit(BankData $bankData)
    {
        $user = Auth::user();
        
        // Prüfe ob der Bankdatensatz zum aktuellen Client gehört
        if ($bankData->client_id !== $user->client_id) {
            abort(403, 'Zugriff verweigert.');
        }

        // Hole alle verfügbaren Kategorien für den Client
        $categories = Category::where('client_id', $user->client_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('bankdata.edit', compact('bankData', 'categories'));
    }

    // Aktualisiert einen Bankdatensatz
    public function update(Request $request, BankData $bankData)
    {
        $user = Auth::user();
        
        // Prüfe ob der Bankdatensatz zum aktuellen Client gehört
        if ($bankData->client_id !== $user->client_id) {
            abort(403, 'Zugriff verweigert.');
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'partnername' => 'nullable|string|max:255',
            'partneriban' => 'nullable|string|max:255',
            'partnerbic' => 'nullable|string|max:255',
            'amount' => 'required|numeric',
            'currency' => 'required|string|max:3',
            'reference' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'type' => 'required|in:income,expense'
        ]);

        $bankData->update($validated);

        return redirect()->route('bankdata.index')
            ->with('success', 'Bankdatensatz erfolgreich aktualisiert.');
    }
}
