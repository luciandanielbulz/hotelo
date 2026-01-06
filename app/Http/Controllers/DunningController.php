<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\Condition;
use App\Jobs\ProcessDunningInvoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DunningController extends Controller
{
    /**
     * Übersicht Mahnwesen.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Filter-Parameter
        $stage = $request->get('stage', 'all');
        $search = $request->get('search', '');
        
        // Basis-Query: Nur Rechnungen mit Status: Offen (1), Gesendet (2), Teilweise bezahlt (3)
        // Nicht: Entwurf (0), Bezahlt (4), Überfällig (5), Storniert (6), Archiviert (7)
        $query = Invoices::where('client_version_id', $user->client_id)
            ->whereIn('status', [1, 2, 3]) // Nur Offen, Gesendet, Teilweise bezahlt
            ->whereNotNull('condition_id')
            ->with(['customer', 'condition']);
        
        // Filter nach Mahnstufe
        if ($stage !== 'all') {
            $query->where('dunning_stage', $stage);
        } else {
            // Zeige alle mit Mahnstufe > 0 (nur Rechnungen die tatsächlich eine Mahnstufe haben)
            $query->where(function($q) {
                $q->where('dunning_stage', '>', 0)
                  ->orWhere(function($subQ) {
                      // Fallback: Rechnungen mit due_date in der Vergangenheit (falls Job noch nicht gelaufen)
                      $subQ->whereNotNull('due_date')
                           ->where('due_date', '<', Carbon::today())
                           ->where(function($fallbackQ) {
                               $fallbackQ->whereNull('dunning_stage')
                                        ->orWhere('dunning_stage', 0);
                           });
                  });
            });
        }
        
        // Suchfilter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('companyname', 'like', "%{$search}%")
                                    ->orWhere('customername', 'like', "%{$search}%");
                  });
            });
        }
        
        $invoices = $query->orderBy('due_date', 'asc')
                         ->orderBy('dunning_stage', 'desc')
                         ->paginate(20);
        
        // Statistiken - nur Rechnungen mit Status: Offen (1), Gesendet (2), Teilweise bezahlt (3)
        $stats = [
            'total' => Invoices::where('client_version_id', $user->client_id)
                ->whereIn('status', [1, 2, 3]) // Nur Offen, Gesendet, Teilweise bezahlt
                ->where('dunning_stage', '>', 0)
                ->count(),
            'reminder' => Invoices::where('client_version_id', $user->client_id)
                ->whereIn('status', [1, 2, 3]) // Nur Offen, Gesendet, Teilweise bezahlt
                ->where('dunning_stage', 1)
                ->count(),
            'first_stage' => Invoices::where('client_version_id', $user->client_id)
                ->whereIn('status', [1, 2, 3]) // Nur Offen, Gesendet, Teilweise bezahlt
                ->where('dunning_stage', 2)
                ->count(),
            'second_stage' => Invoices::where('client_version_id', $user->client_id)
                ->whereIn('status', [1, 2, 3]) // Nur Offen, Gesendet, Teilweise bezahlt
                ->where('dunning_stage', 3)
                ->count(),
            'third_stage' => Invoices::where('client_version_id', $user->client_id)
                ->whereIn('status', [1, 2, 3]) // Nur Offen, Gesendet, Teilweise bezahlt
                ->where('dunning_stage', 4)
                ->count(),
        ];
        
        return view('dunning.index', compact('invoices', 'stats', 'stage', 'search'));
    }

    /**
     * Startet die manuelle Verarbeitung der Mahnungen.
     */
    public function process(Request $request)
    {
        $user = Auth::user();
        
        // Permission prüfen
        if (!$user->hasPermission('process_dunning')) {
            abort(403, 'Zugriff verweigert');
        }

        try {
            // Job synchron ausführen (sofort) - nur für den aktuellen Client
            $job = new ProcessDunningInvoices($user->client_id);
            $job->handle();
            
            return redirect()->route('dunning.index')
                ->with('success', 'Mahnwesen-Verarbeitung erfolgreich gestartet und abgeschlossen!');
        } catch (\Exception $e) {
            \Log::error('Fehler beim manuellen Starten des Mahnwesen-Jobs', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('dunning.index')
                ->with('error', 'Fehler beim Starten der Mahnwesen-Verarbeitung: ' . $e->getMessage());
        }
    }
}


