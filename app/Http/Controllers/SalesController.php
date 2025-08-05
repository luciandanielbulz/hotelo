<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\BankData;
use App\Models\Category;
use App\Services\MyPDF;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentClientId = auth()->user()->client_id;
        $selectedYear = $request->get('year', null);

        // Umsätze
        $salespositions = Invoice::join('invoicepositions', 'invoices.Id', '=', 'invoicepositions.invoice_id')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->where('customers.client_id', '=', $currentClientId)
            ->where('invoicepositions.issoftdeleted', '=', 0);
        
        if ($selectedYear) {
            $salespositions = $salespositions->whereRaw('YEAR(invoices.date) = ?', [$selectedYear]);
        }
        
        $salespositions = $salespositions->selectRaw('YEAR(invoices.date) AS Jahr')
            ->addSelect(DB::raw('SUM(invoicepositions.price * invoicepositions.amount) AS Umsatz'))
            ->groupByRaw('YEAR(invoices.date)')
            ->orderByRaw('YEAR(invoices.date)')
            ->get();

        // Kategorisierte Einnahmen
        $categorizedIncomeQuery = DB::table('bankdata')
            ->join('categories', 'bankdata.category_id', '=', 'categories.id')
            ->selectRaw('
                YEAR(bankdata.date) AS Jahr,
                SUM(
                    CASE 
                        WHEN categories.billing_duration_years > 0 THEN 
                            ABS(bankdata.amount) * (categories.percentage / 100) / categories.billing_duration_years
                        ELSE 
                            ABS(bankdata.amount) * (categories.percentage / 100)
                    END
                ) AS Einnahmen
            ')
            ->where('bankdata.client_id', '=', $currentClientId)
            ->where('bankdata.type', '=', 'income')
            ->where('categories.is_active', '=', 1);
        
        if ($selectedYear) {
            $categorizedIncomeQuery = $categorizedIncomeQuery->whereRaw('YEAR(bankdata.date) = ?', [$selectedYear]);
        }
        
        $categorizedIncome = collect($categorizedIncomeQuery->groupByRaw('YEAR(bankdata.date)')
            ->orderByRaw('YEAR(bankdata.date)')
            ->get());

        // Ausgaben mit Kategorie-Berücksichtigung und Verrechnungsdauer
        $expensesQuery = DB::table('bankdata')
            ->join('categories', 'bankdata.category_id', '=', 'categories.id')
            ->selectRaw('
                YEAR(bankdata.date) AS Jahr,
                SUM(
                    CASE 
                        WHEN categories.billing_duration_years > 0 THEN 
                            ABS(bankdata.amount) * (categories.percentage / 100) / categories.billing_duration_years
                        ELSE 
                            ABS(bankdata.amount) * (categories.percentage / 100)
                    END
                ) AS Ausgaben
            ')
            ->where('bankdata.client_id', '=', $currentClientId)
            ->where('bankdata.type', '=', 'expense')
            ->where('categories.is_active', '=', 1);
        
        if ($selectedYear) {
            $expensesQuery = $expensesQuery->whereRaw('YEAR(bankdata.date) = ?', [$selectedYear]);
        }
        
        $expenses = collect($expensesQuery->groupByRaw('YEAR(bankdata.date)')
            ->orderByRaw('YEAR(bankdata.date)')
            ->get());

        // Ausgaben ohne Kategorie (Fallback für nicht kategorisierte Ausgaben)
        $uncategorizedExpensesQuery = DB::table('bankdata')
            ->leftJoin('categories', 'bankdata.category_id', '=', 'categories.id')
            ->selectRaw('YEAR(bankdata.date) AS Jahr, ABS(SUM(bankdata.amount)) AS Ausgaben')
            ->where('bankdata.client_id', '=', $currentClientId)
            ->where('bankdata.type', '=', 'expense')
            ->whereNull('categories.id');
        
        if ($selectedYear) {
            $uncategorizedExpensesQuery = $uncategorizedExpensesQuery->whereRaw('YEAR(bankdata.date) = ?', [$selectedYear]);
        }
        
        $uncategorizedExpenses = collect($uncategorizedExpensesQuery->groupByRaw('YEAR(bankdata.date)')
            ->orderByRaw('YEAR(bankdata.date)')
            ->get());

        // Einnahmen ohne Kategorie (Fallback für nicht kategorisierte Einnahmen)
        $uncategorizedIncomeQuery = DB::table('bankdata')
            ->leftJoin('categories', 'bankdata.category_id', '=', 'categories.id')
            ->selectRaw('YEAR(bankdata.date) AS Jahr, ABS(SUM(bankdata.amount)) AS Einnahmen')
            ->where('bankdata.client_id', '=', $currentClientId)
            ->where('bankdata.type', '=', 'income')
            ->whereNull('categories.id');
        
        if ($selectedYear) {
            $uncategorizedIncomeQuery = $uncategorizedIncomeQuery->whereRaw('YEAR(bankdata.date) = ?', [$selectedYear]);
        }
        
        $uncategorizedIncome = collect($uncategorizedIncomeQuery->groupByRaw('YEAR(bankdata.date)')
            ->orderByRaw('YEAR(bankdata.date)')
            ->get());

        // Detaillierte Aufschlüsselung nach Kategorien mit berechneten Beträgen
        $categoryBreakdownQuery = DB::table('bankdata')
            ->join('categories', 'bankdata.category_id', '=', 'categories.id')
            ->selectRaw('
                YEAR(bankdata.date) AS Jahr,
                categories.name AS Kategorie,
                categories.percentage AS Prozentsatz,
                categories.billing_duration_years AS Verrechnungsdauer,
                categories.type AS Typ,
                bankdata.type AS Transaktionstyp,
                SUM(
                    CASE 
                        WHEN categories.billing_duration_years > 0 THEN 
                            ABS(bankdata.amount) * (categories.percentage / 100) / categories.billing_duration_years
                        ELSE 
                            ABS(bankdata.amount) * (categories.percentage / 100)
                    END
                ) AS Betrag
            ')
            ->where('bankdata.client_id', '=', $currentClientId)
            ->where('categories.is_active', '=', 1);
        
        if ($selectedYear) {
            $categoryBreakdownQuery = $categoryBreakdownQuery->whereRaw('YEAR(bankdata.date) = ?', [$selectedYear]);
        }
        
        $categoryBreakdown = collect($categoryBreakdownQuery->groupByRaw('YEAR(bankdata.date), categories.name, categories.percentage, categories.billing_duration_years, categories.type, bankdata.type')
            ->orderByRaw('YEAR(bankdata.date) DESC, Betrag DESC')
            ->get());

        // Debug: Prüfe ob Daten vorhanden sind
        \Log::info('Category Breakdown Debug', [
            'total_records' => $categoryBreakdown->count(),
            'sample_data' => $categoryBreakdown->take(3)->toArray(),
            'selected_year' => $selectedYear,
            'client_id' => $currentClientId
        ]);

        // Verfügbare Jahre für den Filter
        $availableYears = collect(DB::table('bankdata')
            ->where('client_id', '=', $currentClientId)
            ->selectRaw('DISTINCT YEAR(date) as year')
            ->orderBy('year', 'desc')
            ->pluck('year'));

        // Umsätze und Ausgaben zusammenführen
        $salespositions = $salespositions->map(function ($salesposition) use ($expenses, $uncategorizedExpenses, $categorizedIncome, $uncategorizedIncome) {
            $categorizedExpense = $expenses->firstWhere('Jahr', $salesposition->Jahr);
            $uncategorizedExpense = $uncategorizedExpenses->firstWhere('Jahr', $salesposition->Jahr);
            $categorizedIncomeItem = $categorizedIncome->firstWhere('Jahr', $salesposition->Jahr);
            $uncategorizedIncomeItem = $uncategorizedIncome->firstWhere('Jahr', $salesposition->Jahr);
            
            $totalExpenses = ($categorizedExpense->Ausgaben ?? 0) + ($uncategorizedExpense->Ausgaben ?? 0);
            $totalIncome = ($categorizedIncomeItem->Einnahmen ?? 0) + ($uncategorizedIncomeItem->Einnahmen ?? 0);
            
            $salesposition->Ausgaben = $totalExpenses;
            $salesposition->Einnahmen_Kategorisiert = $categorizedIncomeItem->Einnahmen ?? 0;
            $salesposition->Ausgaben_Kategorisiert = $categorizedExpense->Ausgaben ?? 0;
            $salesposition->Gewinn_Kategorisiert = ($categorizedIncomeItem->Einnahmen ?? 0) - ($categorizedExpense->Ausgaben ?? 0);
            
            return $salesposition;
        });

        // Chart-Daten vorbereiten
        $chartData = [
            'labels' => $salespositions->map(fn($pos) => $pos->Jahr . '-' . str_pad($pos->Monat ?? 1, 2, '0', STR_PAD_LEFT)),
            'revenue' => $salespositions->map(fn($pos) => $pos->Umsatz),
            'income_categorized' => $salespositions->map(fn($pos) => $pos->Einnahmen_Kategorisiert),
            'expenses_categorized' => $salespositions->map(fn($pos) => $pos->Ausgaben_Kategorisiert),
            'profit_categorized' => $salespositions->map(fn($pos) => $pos->Gewinn_Kategorisiert),
        ];

        // Debug-Informationen
        \Log::info('Sales Analysis Debug', [
            'categorized_income' => $categorizedIncome->toArray(),
            'uncategorized_income' => $uncategorizedIncome->toArray(),
            'categorized_expenses' => $expenses->toArray(),
            'uncategorized_expenses' => $uncategorizedExpenses->toArray(),
            'category_breakdown' => $categoryBreakdown->take(10)->toArray(),
            'selected_year' => $selectedYear,
            'client_id' => $currentClientId
        ]);

        return view('sales.index', compact('salespositions', 'expenses', 'uncategorizedExpenses', 'categorizedIncome', 'uncategorizedIncome', 'categoryBreakdown', 'chartData', 'availableYears', 'selectedYear'));
    }

    /**
     * Test method without authentication
     */
    public function testNoAuth(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Route funktioniert ohne Auth!',
            'timestamp' => now()
        ]);
    }

    /**
     * Test method to check if the route works
     */
    public function testPDF(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Route funktioniert!',
                'user_id' => auth()->id(),
                'client_id' => auth()->user()->client_id,
                'request_params' => $request->all()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Test fehlgeschlagen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export sales report as PDF
     */
    public function exportPDF(Request $request)
    {
        // Echte Daten aus der Datenbank abrufen
        $currentClientId = auth()->user()->client_id;
        $selectedYear = $request->get('year', null);
        $reportType = $request->get('report_type', 'summary');

        // Umsätze
        $salespositions = Invoice::join('invoicepositions', 'invoices.Id', '=', 'invoicepositions.invoice_id')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->where('customers.client_id', '=', $currentClientId)
            ->where('invoicepositions.issoftdeleted', '=', 0);
        
        if ($selectedYear) {
            $salespositions = $salespositions->whereRaw('YEAR(invoices.date) = ?', [$selectedYear]);
        }
        
        $salespositions = $salespositions->selectRaw('YEAR(invoices.date) AS Jahr')
            ->addSelect(DB::raw('SUM(invoicepositions.price * invoicepositions.amount) AS Umsatz'))
            ->groupByRaw('YEAR(invoices.date)')
            ->orderByRaw('YEAR(invoices.date)')
            ->get();

        // Kategorisierte Einnahmen
        $categorizedIncomeQuery = DB::table('bankdata')
            ->join('categories', 'bankdata.category_id', '=', 'categories.id')
            ->selectRaw('
                YEAR(bankdata.date) AS Jahr,
                SUM(
                    CASE 
                        WHEN categories.billing_duration_years > 0 THEN 
                            ABS(bankdata.amount) * (categories.percentage / 100) / categories.billing_duration_years
                        ELSE 
                            ABS(bankdata.amount) * (categories.percentage / 100)
                    END
                ) AS Einnahmen
            ')
            ->where('bankdata.client_id', '=', $currentClientId)
            ->where('bankdata.type', '=', 'income')
            ->where('categories.is_active', '=', 1);
        
        if ($selectedYear) {
            $categorizedIncomeQuery = $categorizedIncomeQuery->whereRaw('YEAR(bankdata.date) = ?', [$selectedYear]);
        }
        
        $categorizedIncome = collect($categorizedIncomeQuery->groupByRaw('YEAR(bankdata.date)')
            ->orderByRaw('YEAR(bankdata.date)')
            ->get());

        // Ausgaben mit Kategorie-Berücksichtigung
        $expensesQuery = DB::table('bankdata')
            ->join('categories', 'bankdata.category_id', '=', 'categories.id')
            ->selectRaw('
                YEAR(bankdata.date) AS Jahr,
                SUM(
                    CASE 
                        WHEN categories.billing_duration_years > 0 THEN 
                            ABS(bankdata.amount) * (categories.percentage / 100) / categories.billing_duration_years
                        ELSE 
                            ABS(bankdata.amount) * (categories.percentage / 100)
                    END
                ) AS Ausgaben
            ')
            ->where('bankdata.client_id', '=', $currentClientId)
            ->where('bankdata.type', '=', 'expense')
            ->where('categories.is_active', '=', 1);
        
        if ($selectedYear) {
            $expensesQuery = $expensesQuery->whereRaw('YEAR(bankdata.date) = ?', [$selectedYear]);
        }
        
        $expenses = collect($expensesQuery->groupByRaw('YEAR(bankdata.date)')
            ->orderByRaw('YEAR(bankdata.date)')
            ->get());

        // Summen berechnen
        $totalRevenue = $salespositions->sum('Umsatz');
        $totalIncome = $categorizedIncome->sum('Einnahmen');
        $totalExpenses = $expenses->sum('Ausgaben');
        $totalProfit = $totalIncome - $totalExpenses;

        // Detaillierte Einzelpositionen abrufen
        $detailedPositions = Invoice::join('invoicepositions', 'invoices.Id', '=', 'invoicepositions.invoice_id')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->select(
                'invoices.date',
                'invoices.number',
                'customers.customername as customer_name',
                'invoicepositions.designation',
                'invoicepositions.price',
                'invoicepositions.amount',
                DB::raw('invoicepositions.price * invoicepositions.amount as total')
            )
            ->where('customers.client_id', '=', $currentClientId)
            ->where('invoicepositions.issoftdeleted', '=', 0);
        
        if ($selectedYear) {
            $detailedPositions = $detailedPositions->whereRaw('YEAR(invoices.date) = ?', [$selectedYear]);
        }
        
        $detailedPositions = $detailedPositions->orderBy('invoices.date', 'desc')
            ->orderBy('invoices.number')
            ->get();

        // Detaillierte Bankdaten abrufen
        $detailedBankData = DB::table('bankdata')
            ->leftJoin('categories', 'bankdata.category_id', '=', 'categories.id')
            ->select(
                'bankdata.date',
                'bankdata.reference',
                'bankdata.amount',
                'bankdata.type',
                'categories.name as category_name',
                'categories.percentage',
                'categories.billing_duration_years'
            )
            ->where('bankdata.client_id', '=', $currentClientId);
        
        if ($selectedYear) {
            $detailedBankData = $detailedBankData->whereRaw('YEAR(bankdata.date) = ?', [$selectedYear]);
        }
        
        $detailedBankData = $detailedBankData->orderBy('bankdata.date', 'desc')->get();

        $html = '<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Umsatz- und Ausgabenbericht</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .positive { color: green; }
        .negative { color: red; }
    </style>
</head>
<body>
    <h1>Umsatz- und Ausgabenbericht</h1>
    
    <h2>Berichtsparameter</h2>
    <p><strong>Zeitraum:</strong> ' . ($selectedYear ? $selectedYear : 'Alle Jahre') . '</p>
    <p><strong>Berichtstyp:</strong> ' . ucfirst($reportType) . '</p>
    <p><strong>Erstellt am:</strong> ' . date('d.m.Y H:i') . '</p>
    
    <h2>Zusammenfassung</h2>
    <table>
        <tr>
            <th>Kategorie</th>
            <th>Betrag (€)</th>
            <th>Erklärung</th>
        </tr>
        <tr>
            <td>Umsatz (Rechnungen)</td>
            <td>' . number_format($totalRevenue, 2, ',', '.') . '</td>
            <td>Summe aller Rechnungsbeträge</td>
        </tr>
        <tr>
            <td>Einnahmen (kategorisiert)</td>
            <td class="positive">' . number_format($totalIncome, 2, ',', '.') . '</td>
            <td>Bankdaten mit Kategorie-Prozentsätzen</td>
        </tr>
        <tr>
            <td>Ausgaben (kategorisiert)</td>
            <td class="negative">' . number_format($totalExpenses, 2, ',', '.') . '</td>
            <td>Bankdaten mit Kategorie-Prozentsätzen</td>
        </tr>
        <tr>
            <td><strong>Gewinn (kategorisiert)</strong></td>
            <td class="' . ($totalProfit >= 0 ? 'positive' : 'negative') . '"><strong>' . number_format($totalProfit, 2, ',', '.') . '</strong></td>
            <td>Einnahmen - Ausgaben (kategorisiert)</td>
        </tr>
    </table>
    
    <h3>Erklärung der Unterschiede:</h3>
    <ul>
        <li><strong>Umsätze:</strong> Summe aller Rechnungsbeträge, die du deinen Kunden in Rechnung gestellt hast</li>
        <li><strong>Einnahmen:</strong> Tatsächliche Geldeingänge auf deinem Bankkonto, berechnet mit Kategorie-Prozentsätzen</li>
        <li><strong>Differenz:</strong> ' . number_format($totalRevenue - $totalIncome, 2, ',', '.') . ' € (noch nicht eingegangene Rechnungen oder andere Einnahmen)</li>
    </ul>
    
    <h2>Detaillierte Rechnungspositionen</h2>
    <table>
        <tr>
            <th>Datum</th>
            <th>Rechnungsnummer</th>
            <th>Kunde</th>
            <th>Beschreibung</th>
            <th>Preis (€)</th>
            <th>Menge</th>
            <th>Gesamt (€)</th>
        </tr>';
        
        foreach ($detailedPositions as $position) {
            $html .= '
        <tr>
            <td>' . date('d.m.Y', strtotime($position->date)) . '</td>
            <td>' . $position->number . '</td>
            <td>' . htmlspecialchars($position->customer_name) . '</td>
            <td>' . htmlspecialchars($position->designation) . '</td>
            <td>' . number_format($position->price, 2, ',', '.') . '</td>
            <td>' . $position->amount . '</td>
            <td>' . number_format($position->total, 2, ',', '.') . '</td>
        </tr>';
        }
        
        $html .= '
    </table>
    
    <h2>Detaillierte Bankdaten</h2>
    <table>
        <tr>
            <th>Datum</th>
            <th>Beschreibung</th>
            <th>Betrag (€)</th>
            <th>Typ</th>
            <th>Kategorie</th>
            <th>Prozentsatz</th>
            <th>Verrechnungsdauer (Jahre)</th>
        </tr>';
        
        foreach ($detailedBankData as $bankData) {
            $html .= '
        <tr>
            <td>' . date('d.m.Y', strtotime($bankData->date)) . '</td>
            <td>' . htmlspecialchars($bankData->reference) . '</td>
            <td class="' . ($bankData->amount >= 0 ? 'positive' : 'negative') . '">' . number_format($bankData->amount, 2, ',', '.') . '</td>
            <td>' . ($bankData->type == 'income' ? 'Einnahme' : 'Ausgabe') . '</td>
            <td>' . htmlspecialchars($bankData->category_name ?? 'Keine Kategorie') . '</td>
            <td>' . ($bankData->percentage ?? '-') . '%</td>
            <td>' . ($bankData->billing_duration_years ?? '-') . '</td>
        </tr>';
        }
        
        $html .= '
    </table>
    
    <p><em>Erstellt mit Venditio - ' . date('d.m.Y H:i') . '</em></p>
</body>
</html>';

        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sales $sales)
    {
        //
    }
}
