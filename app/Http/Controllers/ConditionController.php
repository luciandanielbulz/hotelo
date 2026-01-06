<?php

namespace App\Http\Controllers;

use App\Models\Condition;
use App\Models\Clients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Nur aktive Konditionen des aktuellen Klienten anzeigen
        $conditions = Condition::where('client_id', $user->client_id)->get();

        return view('condition.index',compact('conditions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Nur den aktuellen Klienten anzeigen
        $clients = Clients::where('id', $user->client_id)->get();

        return view('condition.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        $request->validate([
            'conditionname' => 'required|string|max:255',
            'dunning_reminder_days' => 'nullable|integer|min:0',
            'dunning_first_stage_days' => 'nullable|integer|min:0',
            'dunning_second_stage_days' => 'nullable|integer|min:0',
            'dunning_third_stage_days' => 'nullable|integer|min:0',
        ]);

        // Client_id automatisch setzen basierend auf dem eingeloggten User
        Condition::create([
            'conditionname' => $request->conditionname,
            'client_id' => $clientId,
            'dunning_reminder_days' => $request->dunning_reminder_days ?? 0,
            'dunning_first_stage_days' => $request->dunning_first_stage_days ?? 0,
            'dunning_second_stage_days' => $request->dunning_second_stage_days ?? 0,
            'dunning_third_stage_days' => $request->dunning_third_stage_days ?? 0,
        ]);

        return redirect()->route('condition.index')->with('success', 'Bedingung erfolgreich erstellt!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Condition $condition)
    {
        $user = Auth::user();
        
        // Sicherstellen, dass nur eigene Konditionen angezeigt werden
        if ($condition->client_id !== $user->client_id) {
            abort(403, 'Zugriff verweigert');
        }
        
        return view('condition.show', compact('condition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Condition $condition)
    {
        $user = Auth::user();
        
        // Sicherstellen, dass nur eigene Konditionen bearbeitet werden können
        if ($condition->client_id !== $user->client_id) {
            abort(403, 'Zugriff verweigert');
        }

        $clients = Clients::where('id', $user->client_id)->get();

        return view('condition.edit',compact('condition','clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Condition $condition)
    {
        $user = Auth::user();
        
        // Sicherstellen, dass nur eigene Konditionen aktualisiert werden können
        if ($condition->client_id !== $user->client_id) {
            abort(403, 'Zugriff verweigert');
        }

        $request->validate([
            'conditionname' => 'required|string|max:255',
            'dunning_reminder_days' => 'nullable|integer|min:0',
            'dunning_first_stage_days' => 'nullable|integer|min:0',
            'dunning_second_stage_days' => 'nullable|integer|min:0',
            'dunning_third_stage_days' => 'nullable|integer|min:0',
        ]);

        $dbwirte = $condition->update([
            'conditionname' => $request->conditionname,
            'dunning_reminder_days' => $request->dunning_reminder_days ?? 0,
            'dunning_first_stage_days' => $request->dunning_first_stage_days ?? 0,
            'dunning_second_stage_days' => $request->dunning_second_stage_days ?? 0,
            'dunning_third_stage_days' => $request->dunning_third_stage_days ?? 0,
        ]);

        return redirect()->route('condition.index')->with('success', 'Bedingung erfolgreich aktualisiert!');
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(Condition $condition)
    {
        $user = Auth::user();
        
        // Debug-Logging hinzufügen
        Log::info('Destroy method called', [
            'condition_id' => $condition->id,
            'condition_name' => $condition->conditionname,
            'user_id' => $user->id,
            'user_client_id' => $user->client_id,
            'condition_client_id' => $condition->client_id
        ]);
        
        // Sicherstellen, dass nur eigene Konditionen gelöscht werden können
        if ($condition->client_id !== $user->client_id) {
            Log::warning('Access denied - different client_id', [
                'user_client_id' => $user->client_id,
                'condition_client_id' => $condition->client_id
            ]);
            abort(403, 'Zugriff verweigert');
        }

        try {
            // Prüfen, ob die Condition noch in Verwendung ist
            $usedInOffers = $condition->offers()->count();
            $usedInInvoices = $condition->invoices()->count();
            $usedInCustomers = \App\Models\Customer::where('condition_id', $condition->id)->count();
            
            Log::info('Usage check', [
                'condition_id' => $condition->id,
                'used_in_offers' => $usedInOffers,
                'used_in_invoices' => $usedInInvoices,
                'used_in_customers' => $usedInCustomers
            ]);
            
            if ($usedInOffers > 0 || $usedInInvoices > 0 || $usedInCustomers > 0) {
                Log::warning('Versuch eine verwendete Condition zu löschen', [
                    'condition_id' => $condition->id, 
                    'used_in_offers' => $usedInOffers,
                    'used_in_invoices' => $usedInInvoices,
                    'used_in_customers' => $usedInCustomers,
                    'deleted_by' => Auth::id()
                ]);
                
                $usageMessage = [];
                if ($usedInOffers > 0) $usageMessage[] = "{$usedInOffers} Angeboten";
                if ($usedInInvoices > 0) $usageMessage[] = "{$usedInInvoices} Rechnungen";
                if ($usedInCustomers > 0) $usageMessage[] = "{$usedInCustomers} Kunden";
                
                return redirect()->route('condition.index')->with('warning', 
                    "Bedingung kann nicht gelöscht werden. Sie wird noch in " . implode(', ', $usageMessage) . " verwendet.");
            }

            // Soft Delete
            $deleted = $condition->delete();
            
            Log::info('Delete result', [
                'condition_id' => $condition->id,
                'delete_result' => $deleted ? 'success' : 'failed',
                'condition_deleted_at' => $condition->deleted_at
            ]);

            // Logge die Löschaktion
            Log::info('Condition soft-deleted', ['condition_id' => $condition->id, 'deleted_by' => Auth::id()]);

            return redirect()->route('condition.index')->with('success', 'Bedingung erfolgreich gelöscht!');
        } catch (\Exception $e) {
            // Fehlerbehandlung
            Log::error('Fehler beim Löschen der Condition', [
                'error' => $e->getMessage(), 
                'condition_id' => $condition->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('condition.index')->with('error', 'Fehler beim Löschen der Bedingung: ' . $e->getMessage());
        }
    }

    /**
     * Zeigt gelöschte Konditionen an (optional für Admin)
     */
    public function trashed()
    {
        $user = Auth::user();
        $conditions = Condition::onlyTrashed()
            ->where('client_id', $user->client_id)
            ->get();

        return view('condition.trashed', compact('conditions'));
    }

    /**
     * Stellt eine gelöschte Kondition wieder her
     */
    public function restore($id)
    {
        $user = Auth::user();
        $condition = Condition::onlyTrashed()
            ->where('client_id', $user->client_id)
            ->findOrFail($id);

        $condition->restore();

        Log::info('Condition restored', ['condition_id' => $condition->id, 'restored_by' => Auth::id()]);

        return redirect()->route('condition.index')->with('success', 'Bedingung erfolgreich wiederhergestellt!');
    }

    /**
     * Löscht eine Kondition permanent
     */
    public function forceDelete($id)
    {
        $user = Auth::user();
        $condition = Condition::onlyTrashed()
            ->where('client_id', $user->client_id)
            ->findOrFail($id);

        try {
            // Prüfen, ob die Condition noch in Verwendung ist (auch für permanent löschen)
            $usedInOffers = $condition->offers()->count();
            $usedInInvoices = $condition->invoices()->count();
            $usedInCustomers = \App\Models\Customer::where('condition_id', $condition->id)->count();
            
            if ($usedInOffers > 0 || $usedInInvoices > 0 || $usedInCustomers > 0) {
                $usageMessage = [];
                if ($usedInOffers > 0) $usageMessage[] = "{$usedInOffers} Angeboten";
                if ($usedInInvoices > 0) $usageMessage[] = "{$usedInInvoices} Rechnungen";
                if ($usedInCustomers > 0) $usageMessage[] = "{$usedInCustomers} Kunden";
                
                Log::warning('Versuch eine verwendete Condition permanent zu löschen', [
                    'condition_id' => $condition->id, 
                    'used_in_offers' => $usedInOffers,
                    'used_in_invoices' => $usedInInvoices,
                    'used_in_customers' => $usedInCustomers,
                    'deleted_by' => Auth::id()
                ]);
                
                return redirect()->route('condition.trashed')->with('warning', 
                    "Bedingung kann nicht permanent gelöscht werden. Sie wird noch in " . implode(', ', $usageMessage) . " verwendet.");
            }

            $condition->forceDelete();

            Log::info('Condition permanently deleted', ['condition_id' => $condition->id, 'deleted_by' => Auth::id()]);

            return redirect()->route('condition.trashed')->with('success', 'Bedingung permanent gelöscht!');
        } catch (\Exception $e) {
            Log::error('Fehler beim permanenten Löschen der Condition', [
                'error' => $e->getMessage(), 
                'condition_id' => $condition->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('condition.trashed')->with('error', 'Fehler beim permanenten Löschen: ' . $e->getMessage());
        }
    }
}
