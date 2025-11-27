<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\ClientSettings;
use Illuminate\Http\Request;
use App\Models\Taxrates;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ClientsController extends Controller
{
    /**
     * Logo hochladen und Verzeichnis erstellen falls nötig
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @return string|false Logo-Dateiname oder false bei Fehler
     */
    private function uploadLogo($file)
    {
        try {
            // Stelle sicher, dass das logos Verzeichnis existiert
            if (!Storage::disk('public')->exists('logos')) {
                Storage::disk('public')->makeDirectory('logos');
                Log::info('Logos Verzeichnis erstellt');
            }

            // Generiere einen eindeutigen Dateinamen
            $logoName = time() . '_' . $file->getClientOriginalName();
            
            // Speichere das Logo
            $logoPath = $file->storeAs('logos', $logoName, 'public');
            
            if ($logoPath) {
                Log::info('Logo erfolgreich hochgeladen: ' . $logoName);
                return $logoName;
            } else {
                Log::error('Logo Upload fehlgeschlagen');
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Fehler beim Logo Upload: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Display a listing of the resourc
     */
    public function index()
    {
        
        // Nur aktive Clients anzeigen (neueste Versionen)
        $clients = Clients::active()
            ->orderBy('clientname')
            ->paginate(15);
        //dd($clients);
        return view('clients.index', compact('clients'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //dd($request->all());
        $taxrates = Taxrates::all();

        return view('clients.create', compact('taxrates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validierung der Eingabedaten
        $validatedData = $request->validate([
            // Client-Daten (versioniert)
            'clientname' => ['required', 'string', 'max:50'],
            'companyname' => ['required', 'string', 'max:200'],
            'business' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:200'],
            'postalcode' => ['required', 'integer'],
            'location' => ['required', 'string', 'max:200'],
            'email' => ['required', 'email', 'max:200'],
            'phone' => ['required', 'string', 'max:200'],
            'tax_id' => ['required', 'integer', 'exists:taxrates,id'],
            'webpage' => ['nullable', 'string', 'max:30'],
            'bank' => ['required', 'string', 'max:200'],
            'accountnumber' => ['required', 'string', 'max:200'],
            'vat_number' => ['nullable', 'string'],
            'bic' => ['required', 'string'],
            'smallbusiness' => ['required', 'boolean'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'signature' => ['nullable', 'string', 'max:1000'],
            'document_footer' => ['nullable', 'string'],
            'style' => ['nullable', 'string', 'max:500'],
            'company_registration_number' => ['nullable', 'string', 'max:100'],
            'tax_number' => ['nullable', 'string', 'max:100'],
            'dgnr' => ['nullable', 'string', 'max:100'],
            'management' => ['nullable', 'string', 'max:200'],
            'regional_court' => ['nullable', 'string', 'max:200'],
            'color' => ['nullable', 'string', 'max:7'],
            
            // ClientSettings-Daten (statisch)
            'lastoffer' => ['required', 'integer'],
            'offermultiplikator' => ['required', 'integer'],
            'lastinvoice' => ['required', 'integer'],
            'invoicemultiplikator' => ['required', 'integer'],
            'max_upload_size' => ['required', 'integer'],
            'invoice_number_format' => ['nullable', 'string', 'max:50'],
            'invoice_prefix' => ['nullable', 'string', 'max:10'],
            'offer_prefix' => ['nullable', 'string', 'max:10'],
        ]);

        try {
            DB::beginTransaction();
            
            // 1. Client-Daten trennen
            $clientData = collect($validatedData)->except([
                'lastoffer', 'offermultiplikator', 'lastinvoice', 
                'invoicemultiplikator', 'invoice_number_format', 
                'max_upload_size', 'invoice_prefix', 'offer_prefix'
            ])->toArray();

            // Logo hochladen, falls vorhanden
            if ($request->hasFile('logo')) {
                $logoName = $this->uploadLogo($request->file('logo'));
                if ($logoName) {
                    $clientData['logo'] = $logoName;
                } else {
                    DB::rollBack();
                    return redirect()->back()->withErrors(['logo' => 'Logo konnte nicht hochgeladen werden.'])->withInput();
                }
            }

            // 2. Neuen Client erstellen (erste Version)
            $clientData['is_active'] = true;
            $clientData['version'] = 1;
            $clientData['valid_from'] = now();
            $clientData['valid_to'] = null;
            $clientData['parent_client_id'] = null;
            
            $client = Clients::create($clientData);

            // 3. ClientSettings erstellen
            ClientSettings::create([
                'client_id' => $client->id,
                'lastinvoice' => $validatedData['lastinvoice'],
                'lastoffer' => $validatedData['lastoffer'],
                'invoicemultiplikator' => $validatedData['invoicemultiplikator'],
                'offermultiplikator' => $validatedData['offermultiplikator'],
                'invoice_number_format' => $validatedData['invoice_number_format'],
                'max_upload_size' => $validatedData['max_upload_size'],
                'invoice_prefix' => $validatedData['invoice_prefix'],
                'offer_prefix' => $validatedData['offer_prefix'],
            ]);

            DB::commit();

            // Erfolgreiche Erstellung, Weiterleitung zur Übersicht
            return redirect()->route('clients.index')->with('success', 'Klient erfolgreich erstellt.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Fehlerbehandlung und Logging
            Log::error('Fehler beim Erstellen des Klienten: ' . $e->getMessage(), [
                'request_data' => $request->all(),
            ]);

            // Weiterleitung zurück zum Formular mit Fehlermeldung
            return redirect()->back()->withErrors(['error' => 'Fehler: ' . $e->getMessage()])->withInput();
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Clients $clients)
    {
        // 
    }

    /** 
     * Show the form for editing the specified resource.
     */
    public function edit(Clients $client)
    {
        $clients = $client; // Für Konsistenz mit der Blade-Vorlage
        $taxrates = Taxrates::all();

        return view('clients.edit', compact('clients', 'taxrates'));
    }

    /**
     * Update the specified resource in storage.
     * Behandelt sowohl versionierte Daten (Client) als auch statische Daten (ClientSettings)
     */
    public function update(Request $request, Clients $client)
    {
        // Validierung der Eingabedaten - getrennt für Client und Settings
        $validatedData = $request->validate([
            // Client-Daten (versioniert)
            'clientname' => ['required', 'string', 'max:50'],
            'companyname' => ['required', 'string', 'max:200', 'min:1'],
            'business' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:200'],
            'postalcode' => ['required', 'integer'],
            'location' => ['required', 'string', 'max:200'],
            'email' => ['required', 'email', 'max:200'],
            'phone' => ['required', 'string', 'max:200'],
            'tax_id' => ['required', 'integer', 'exists:taxrates,id'],
            'webpage' => ['nullable', 'string', 'max:30'],
            'bank' => ['required', 'string', 'max:200'],
            'accountnumber' => ['required', 'string', 'max:200'],
            'vat_number' => ['nullable', 'string'],
            'bic' => ['required', 'string'],
            'smallbusiness' => ['required', 'boolean'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'signature' => ['nullable', 'string', 'max:1000'],
            'document_footer' => ['nullable', 'string'],
            'style' => ['nullable', 'string', 'max:500'],
            'company_registration_number' => ['nullable', 'string', 'max:100'],
            'tax_number' => ['nullable', 'string', 'max:100'],
            'dgnr' => ['nullable', 'string', 'max:100'],
            'management' => ['nullable', 'string', 'max:200'],
            'regional_court' => ['nullable', 'string', 'max:200'],
            'color' => ['nullable', 'string', 'max:7'],
        ]);

        try {
            DB::beginTransaction();
            
            Log::info('Client Update gestartet für ID: ' . $client->id . ' (Version: ' . $client->version . ')');

            // 1. Client-Daten (versioniert) - Neue Version erstellen
            $clientData = $validatedData;
            
            // Stelle sicher, dass alle nullable Felder auch bei null/leer vorhanden sind
            // Konvertiere leere Strings zu null für nullable Felder
            if (!isset($clientData['dgnr']) || $clientData['dgnr'] === '') {
                $clientData['dgnr'] = null;
            }

            // Logo hochladen, falls vorhanden
            if ($request->hasFile('logo')) {
                Log::info('Logo-Datei gefunden im Update, starte Upload...');
                $logoName = $this->uploadLogo($request->file('logo'));
                if ($logoName) {
                    $clientData['logo'] = $logoName;
                    // Prüfe ob die Datei wirklich existiert
                    $logoPath = storage_path('app/public/logos/' . $logoName);
                    if (file_exists($logoPath)) {
                        Log::info('Logo wurde erfolgreich hochgeladen und existiert: ' . $logoName . ' (Pfad: ' . $logoPath . ')');
                    } else {
                        Log::error('Logo wurde hochgeladen, aber Datei existiert nicht: ' . $logoPath);
                    }
                } else {
                    DB::rollBack();
                    return redirect()->back()->withErrors(['logo' => 'Logo konnte nicht hochgeladen werden.'])->withInput();
                }
            } else {
                Log::info('Keine Logo-Datei im Request gefunden, behalte aktuelles Logo: ' . ($client->logo ?? 'kein Logo'));
                // Behalte das aktuelle Logo für die neue Version
                $clientData['logo'] = $client->logo;
            }

            // Erstelle nur neue Version wenn sich Client-Daten geändert haben
            $hasClientChanges = false;
            foreach ($clientData as $key => $value) {
                if ($key !== 'logo') {
                    $oldValue = $client->{$key} ?? null;
                    $newValue = $value ?? null;
                    // Prüfe auf Änderung (auch wenn von null zu Wert oder umgekehrt)
                    if ($oldValue != $newValue) {
                        $hasClientChanges = true;
                        break;
                    }
                }
            }
            
            if ($request->hasFile('logo')) {
                $hasClientChanges = true;
            }
            
            $newVersion = null;
            if ($hasClientChanges) {
                $newVersion = $client->createNewVersion($clientData);
                Log::info('Neue Client-Version erstellt: ID ' . $newVersion->id . ' (Version: ' . $newVersion->version . ')');
            }
            
            DB::commit();

            // Erfolgreiche Aktualisierung
            $message = 'Client erfolgreich aktualisiert.';
            if ($newVersion) {
                $message = 'Neue Client-Version (v' . $newVersion->version . ') erfolgreich erstellt.';
            } else {
                $message = 'Keine Änderungen erkannt.';
            }
            
            return redirect()->route('clients.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            // Fehlerbehandlung und Logging
            Log::error('Fehler beim Aktualisieren des Klienten: ' . $e->getMessage(), [
                'request_data' => $request->all(),
            ]);

            // Weiterleitung zurück zum Formular mit Fehlermeldung
            return redirect()->back()->withErrors(['error' => 'Fehler: ' . $e->getMessage()])->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clients $clients)
    {
        //
    }

    /**
     * Zeigt die Versionshistorie eines Clients
     */
    public function showVersionHistory(Clients $client)
    {
        $user = Auth::user();
        
        // Sicherheitsprüfung: Benutzer kann nur Versionshistorie seines eigenen Clients sehen
        // Es sei denn, er hat die Berechtigung manage_all_clients
        if (!$user->hasPermission('manage_all_clients')) {
            if ($user->client_id !== $client->id && $user->client_id !== $client->parent_client_id) {
                abort(403, 'Sie sind nicht berechtigt, die Versionshistorie dieses Clients einzusehen.');
            }
        }
        
        // Hole alle Versionen dieses Clients
        $versions = $client->allVersions()->get();
        
        return view('clients.version-history', compact('client', 'versions'));
    }

    /**
     * Zeigt eine spezifische Version eines Clients
     */
    public function showVersion(Clients $client, $version)
    {
        $user = Auth::user();
        
        // Sicherheitsprüfung: Benutzer kann nur Versionshistorie seines eigenen Clients sehen
        // Es sei denn, er hat die Berechtigung manage_all_clients
        if (!$user->hasPermission('manage_all_clients')) {
            if ($user->client_id !== $client->id && $user->client_id !== $client->parent_client_id) {
                abort(403, 'Sie sind nicht berechtigt, diese Client-Version einzusehen.');
            }
        }
        
        $parentId = $client->parent_client_id ?? $client->id;
        
        $specificVersion = Clients::where(function($query) use ($parentId) {
                                    $query->where('id', $parentId)
                                          ->orWhere('parent_client_id', $parentId);
                                })
                                ->where('version', $version)
                                ->firstOrFail();
                                
        return view('clients.show-version', compact('specificVersion', 'client'));
    }

    /**
     * Löscht eine spezifische Version eines Clients
     */
    public function deleteVersion(Clients $client, $version)
    {
        $user = Auth::user();
        
        // Sicherheitsprüfung: Benutzer kann nur Versionen seines eigenen Clients löschen
        // Es sei denn, er hat die Berechtigung manage_all_clients
        if (!$user->hasPermission('manage_all_clients')) {
            if ($user->client_id !== $client->id && $user->client_id !== $client->parent_client_id) {
                abort(403, 'Sie sind nicht berechtigt, Client-Versionen zu löschen.');
            }
        }
        
        $parentId = $client->parent_client_id ?? $client->id;
        
        // Finde die zu löschende Version
        $versionToDelete = Clients::where(function($query) use ($parentId) {
                                    $query->where('id', $parentId)
                                          ->orWhere('parent_client_id', $parentId);
                                })
                                ->where('version', $version)
                                ->firstOrFail();

        try {
            DB::beginTransaction();

            // Sicherheitsprüfungen
            $this->validateVersionDeletion($versionToDelete, $parentId);

            Log::info('Lösche Client-Version: ID ' . $versionToDelete->id . ' (Version: ' . $version . ')');

            // Wenn es die aktive Version ist, mache die vorherige Version aktiv
            if ($versionToDelete->is_active) {
                $this->reactivatePreviousVersion($parentId, $version);
            }

            // Lösche die Version
            $versionToDelete->delete();

            DB::commit();

            return redirect()->route('clients.versions', $client->id)
                           ->with('success', 'Version ' . $version . ' wurde erfolgreich gelöscht.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Fehler beim Löschen der Client-Version: ' . $e->getMessage());
            
            return redirect()->back()
                           ->withErrors(['error' => 'Fehler beim Löschen: ' . $e->getMessage()]);
        }
    }

    /**
     * Validiert ob eine Version gelöscht werden kann
     */
    private function validateVersionDeletion($versionToDelete, $parentId)
    {
        // Prüfe ob es die einzige Version ist
        $totalVersions = Clients::where(function($query) use ($parentId) {
                                    $query->where('id', $parentId)
                                          ->orWhere('parent_client_id', $parentId);
                                })->count();

        if ($totalVersions <= 1) {
            throw new \Exception('Die einzige Version eines Clients kann nicht gelöscht werden.');
        }

        // TODO: Hier könnten später Prüfungen hinzugefügt werden, ob die Version
        // bereits in Rechnungen oder Angeboten verwendet wird
        // Beispiel:
        // $usedInInvoices = Invoice::where('client_version_id', $versionToDelete->id)->exists();
        // if ($usedInInvoices) {
        //     throw new \Exception('Diese Version kann nicht gelöscht werden, da sie bereits in Rechnungen verwendet wird.');
        // }
    }

    /**
     * Reaktiviert die vorherige Version wenn die aktive Version gelöscht wird
     */
    private function reactivatePreviousVersion($parentId, $deletedVersion)
    {
        // Finde die höchste Version die kleiner als die gelöschte Version ist
        $previousVersion = Clients::where(function($query) use ($parentId) {
                                    $query->where('id', $parentId)
                                          ->orWhere('parent_client_id', $parentId);
                                })
                                ->where('version', '<', $deletedVersion)
                                ->orderBy('version', 'desc')
                                ->first();

        if ($previousVersion) {
            $previousVersion->update([
                'is_active' => true,
                'valid_to' => null
            ]);
            
            Log::info('Version ' . $previousVersion->version . ' wurde reaktiviert als aktive Version');
        }
    }

    /**
     * Zeigt die Client-Einstellungen des aktuell eingeloggten Benutzers
     */
    public function myClientSettings()
    {
        $user = Auth::user();
        
        // Hole zunächst den Client (egal ob aktiv oder nicht)
        $userClient = Clients::find($user->client_id);
        
        if (!$userClient) {
            abort(403, 'Client nicht gefunden');
        }
        
        // Hole die aktuelle aktive Version des Clients
        $clients = $userClient->getCurrentVersion();
        
        if (!$clients) {
            abort(403, 'Keine aktive Version des Clients gefunden');
        }
        
        $taxrates = Taxrates::all();

        return view('clients.my-settings', compact('clients', 'taxrates'));
    }

    /**
     * Aktualisiert die Client-Einstellungen des aktuell eingeloggten Benutzers
     * NUR versionierte Daten, KEINE Nummerierungseinstellungen
     */
    public function updateMyClientSettings(Request $request)
    {
        $user = Auth::user();
        
        // Validierung der Eingabedaten (NUR versionierte Daten, OHNE statische Einstellungen!)
        $validatedData = $request->validate([
            'clientname' => ['required', 'string', 'max:50'],
            'companyname' => ['required', 'string', 'max:200', 'min:1'],
            'business' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:200'],
            'postalcode' => ['required', 'integer'],
            'location' => ['required', 'string', 'max:200'],
            'email' => ['required', 'email', 'max:200'],
            'phone' => ['required', 'string', 'max:200'],
            'tax_id' => ['required', 'integer', 'exists:taxrates,id'],
            'webpage' => ['nullable', 'string', 'max:30'],
            'bank' => ['required', 'string', 'max:200'],
            'accountnumber' => ['required', 'string', 'max:200'],
            'vat_number' => ['nullable', 'string'],
            'bic' => ['required', 'string'],
            'smallbusiness' => ['required', 'boolean'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'signature' => ['nullable', 'string', 'max:1000'],
            'document_footer' => ['nullable', 'string'],
            'style' => ['nullable', 'string', 'max:500'],
            'company_registration_number' => ['nullable', 'string', 'max:100'],
            'tax_number' => ['nullable', 'string', 'max:100'],
            'dgnr' => ['nullable', 'string', 'max:100'],
            'management' => ['nullable', 'string', 'max:200'],
            'regional_court' => ['nullable', 'string', 'max:200'],
            'color' => ['nullable', 'string', 'max:7'],
        ]);

        try {
            // Hole zunächst den Client (egal ob aktiv oder nicht)
            $userClient = Clients::find($user->client_id);
            
            if (!$userClient) {
                throw new \Exception('Client nicht gefunden');
            }
            
            // Hole die aktuelle aktive Version des Clients
            $client = $userClient->getCurrentVersion();
            
            if (!$client) {
                throw new \Exception('Keine aktive Version des Clients gefunden');
            }

            // Logo hochladen, falls vorhanden
            if ($request->hasFile('logo')) {
                $logoName = $this->uploadLogo($request->file('logo'));
                if ($logoName) {
                    $client->logo = $logoName;
                } else {
                    return redirect()->back()->withErrors(['logo' => 'Logo konnte nicht hochgeladen werden.'])->withInput();
                }
            }

            // Erstelle neue Version statt direkter Aktualisierung (falls sich etwas geändert hat)
            $hasChanges = false;
            $changesToCheck = [
                'clientname', 'companyname', 'business', 'address', 'postalcode', 
                'location', 'email', 'phone', 'tax_id', 'webpage', 'bank', 
                'accountnumber', 'vat_number', 'bic', 'smallbusiness', 'signature', 'document_footer', 
                'style', 'company_registration_number', 'tax_number', 'dgnr', 'management', 
                'regional_court', 'color'
            ];
            
            foreach ($changesToCheck as $field) {
                $oldValue = $client->{$field} ?? null;
                $newValue = $validatedData[$field] ?? null;
                // Prüfe auf Änderung (auch wenn von null zu Wert oder umgekehrt)
                if ($oldValue != $newValue) {
                    $hasChanges = true;
                    break;
                }
            }
            
            // Prüfe auch Logo-Änderung
            if ($request->hasFile('logo')) {
                $hasChanges = true;
            }
            
            if ($hasChanges) {
                // Erstelle neue Version mit geänderten Daten
                $newVersionData = $validatedData;
                
                // Stelle sicher, dass alle nullable Felder auch bei null/leer vorhanden sind
                // Konvertiere leere Strings zu null für nullable Felder
                if (!isset($newVersionData['dgnr']) || $newVersionData['dgnr'] === '') {
                    $newVersionData['dgnr'] = null;
                }
                
                if ($request->hasFile('logo')) {
                    $logoName = $this->uploadLogo($request->file('logo'));
                    if (!$logoName) {
                        return redirect()->back()->withErrors(['logo' => 'Logo konnte nicht hochgeladen werden.'])->withInput();
                    }
                    $newVersionData['logo'] = $logoName;
                }
                
                                 $client->createNewVersion($newVersionData);
             }

            // Erfolgreiche Aktualisierung
            return redirect()->route('clients.my-settings')->with('success', 'Ihre Client-Einstellungen wurden erfolgreich aktualisiert.');
        } catch (\Exception $e) {
            // Fehlerbehandlung und Logging
            Log::error('Fehler beim Aktualisieren der Client-Einstellungen: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'client_id' => $user->client_id,
                'request_data' => $request->all(),
            ]);

            // Weiterleitung zurück zum Formular mit Fehlermeldung
            return redirect()->back()->withErrors(['error' => 'Fehler: ' . $e->getMessage()])->withInput();
        }
    }
}
