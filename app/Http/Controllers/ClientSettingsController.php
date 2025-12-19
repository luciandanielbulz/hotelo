<?php

namespace App\Http\Controllers;

use App\Models\ClientSettings;
use App\Models\Clients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientSettingsController extends Controller
{
    /**
     * Zeigt die statischen Einstellungen des aktuellen Clients
     */
    public function edit()
    {
        $user = Auth::user();
        $clientId = $user->client_id;
        
        // Hole zunächst den Client (egal ob aktiv oder nicht)
        $userClient = Clients::find($clientId);
        
        if (!$userClient) {
            abort(403, 'Client nicht gefunden');
        }
        
        // Hole die aktuelle aktive Version des Clients
        $client = $userClient->getCurrentVersion();
        
        if (!$client) {
            abort(403, 'Keine aktive Version des Clients gefunden');
        }
        
        // Bestimme die ursprüngliche Client-ID (für statische Einstellungen)
        $originalClientId = $client->parent_client_id ?? $client->id;
        
        // Lade oder erstelle ClientSettings
        $clientSettings = ClientSettings::where('client_id', $originalClientId)->first();
        
        if (!$clientSettings) {
            $clientSettings = ClientSettings::create([
                'client_id' => $originalClientId,
                'lastinvoice' => 0,
                'lastoffer' => 0,
                'invoice_number_format' => 'YYYYNN',
                'max_upload_size' => 2048,
                'invoice_prefix' => null,
                'offer_prefix' => null,
            ]);
        }
        
        return view('client-settings.edit', compact('clientSettings', 'client'));
    }
    
    /**
     * Aktualisiert die statischen Client-Einstellungen
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $clientId = $user->client_id;
        
        // Hole zunächst den Client (egal ob aktiv oder nicht)
        $userClient = Clients::find($clientId);
        
        if (!$userClient) {
            abort(403, 'Client nicht gefunden');
        }
        
        // Hole die aktuelle aktive Version des Clients
        $client = $userClient->getCurrentVersion();
        
        if (!$client) {
            abort(403, 'Keine aktive Version des Clients gefunden');
        }
        
        // Validierung der statischen Einstellungen
        // Nummernformate und Präfixe werden jetzt in clients.my-settings verwaltet
        // Multiplikatoren werden nicht mehr verwendet
        $validatedData = $request->validate([
            'lastinvoice' => ['required', 'integer', 'min:0'],
            'lastoffer' => ['required', 'integer', 'min:0'],
            'max_upload_size' => ['required', 'integer', 'min:1', 'max:100'],
        ]);
        
        try {
            DB::beginTransaction();
            
            // Bestimme die ursprüngliche Client-ID
            $originalClientId = $client->parent_client_id ?? $client->id;
            
            // Finde oder erstelle ClientSettings
            $clientSettings = ClientSettings::where('client_id', $originalClientId)->first();
            
            if (!$clientSettings) {
                $clientSettings = ClientSettings::create(array_merge($validatedData, [
                    'client_id' => $originalClientId
                ]));
                Log::info('Neue ClientSettings erstellt für Client-ID: ' . $originalClientId);
            } else {
                $clientSettings->update($validatedData);
                Log::info('ClientSettings aktualisiert für Client-ID: ' . $originalClientId);
            }
            
            DB::commit();
            
            return redirect()->route('client-settings.edit')
                           ->with('success', 'Statische Einstellungen wurden erfolgreich aktualisiert.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Fehler beim Aktualisieren der Client-Einstellungen: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'client_id' => $clientId,
                'request_data' => $request->all(),
            ]);
            
            return redirect()->back()
                           ->withErrors(['error' => 'Fehler beim Speichern: ' . $e->getMessage()])
                           ->withInput();
        }
    }
    
    /**
     * Zeigt eine Übersicht der Client-Einstellungen (für Admins)
     */
    public function index()
    {
        $user = Auth::user();
        
        // Nur für Admins oder bestimmte Rollen
        // TODO: Hier könnte eine Berechtigungsprüfung hinzugefügt werden
        
        $clientSettings = ClientSettings::with('client')->paginate(10);
        
        return view('client-settings.index', compact('clientSettings'));
    }
    
    /**
     * Zeigt Details einer spezifischen Client-Einstellung (für Admins)
     */
    public function show($clientId)
    {
        // Finde den Client
        $client = Clients::findOrFail($clientId);
        
        // Bestimme die ursprüngliche Client-ID (für statische Einstellungen)
        $originalClientId = $client->parent_client_id ?? $client->id;
        
        // Lade oder erstelle ClientSettings
        $clientSettings = ClientSettings::where('client_id', $originalClientId)->first();
        
        if (!$clientSettings) {
            $clientSettings = ClientSettings::create([
                'client_id' => $originalClientId,
                'lastinvoice' => 0,
                'lastoffer' => 0,
                'invoice_number_format' => 'YYYYNN',
                'max_upload_size' => 2048,
                'invoice_prefix' => null,
                'offer_prefix' => null,
            ]);
        }
        
        return view('client-settings.show', compact('clientSettings', 'client'));
    }
    
    /**
     * Generiert eine Vorschau der nächsten Nummern basierend auf den aktuellen Einstellungen
     */
    public function previewNumbers()
    {
        $user = Auth::user();
        $clientId = $user->client_id;
        
        // Hole zunächst den Client (egal ob aktiv oder nicht)
        $userClient = Clients::find($clientId);
        
        if (!$userClient) {
            return response()->json(['error' => 'Client nicht gefunden'], 404);
        }
        
        // Hole die aktuelle aktive Version des Clients
        $client = $userClient->getCurrentVersion();
        
        if (!$client) {
            return response()->json(['error' => 'Keine aktive Version des Clients gefunden'], 404);
        }
        
        $originalClientId = $client->parent_client_id ?? $client->id;
        
        $clientSettings = ClientSettings::where('client_id', $originalClientId)->first();
        
        if (!$clientSettings) {
            return response()->json(['error' => 'Client-Einstellungen nicht gefunden'], 404);
        }
        
        $nextInvoiceNumber = $clientSettings->generateInvoiceNumber();
        $nextOfferNumber = $clientSettings->generateOfferNumber();
        
        return response()->json([
            'next_invoice_number' => $nextInvoiceNumber,
            'next_offer_number' => $nextOfferNumber
        ]);
    }
}
