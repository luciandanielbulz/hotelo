<?php

namespace App\Http\Controllers;

use App\Models\Clients;
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
            'clientname'     => ['required', 'string', 'max:50'],
            'companyname'    => ['required', 'string', 'max:200'],
            'business'       => ['required', 'string', 'max:100'],
            'address'        => ['required', 'string', 'max:200'],
            'postalcode'     => ['required', 'integer'],
            'location'       => ['required', 'string', 'max:200'],
            'email'          => ['required', 'email', 'max:200'],
            'phone'          => ['required', 'string', 'max:200'],
            'tax_id'         => ['required', 'integer', 'exists:taxrates,id'],
            'webpage'        => ['nullable', 'string', 'max:30'],
            'bank'           => ['required', 'string', 'max:200'],
            'accountnumber'  => ['required', 'string', 'max:200'],
            'vat_number'     => ['nullable', 'string'],
            'bic'            => ['required', 'string'],
            'smallbusiness'  => ['required', 'boolean'],
            'logo'           => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'signature'      => ['nullable', 'string', 'max:1000'],
            'style'          => ['nullable', 'string', 'max:500'],
            'lastoffer'      => ['required', 'integer'],
            'offermultiplikator' => ['required', 'integer'],
            'lastinvoice'    => ['required', 'integer'],
            'invoicemultiplikator' => ['required', 'integer'],
            'max_upload_size' => ['required', 'integer'],
            'company_registration_number' => ['nullable', 'string', 'max:100'],
            'tax_number' => ['nullable', 'string', 'max:100'],
            'management' => ['nullable', 'string', 'max:200'],
            'regional_court' => ['nullable', 'string', 'max:200'],
            'color' => ['nullable', 'string', 'max:7'],
            'invoice_number_format' => ['nullable', 'string', 'max:50'],
            'invoice_prefix' => ['nullable', 'string', 'max:10'],
            'offer_prefix' => ['nullable', 'string', 'max:10'],
        ]);

        try {
            // Logo hochladen, falls vorhanden
            if ($request->hasFile('logo')) {
                $logoName = $this->uploadLogo($request->file('logo'));
                if ($logoName) {
                    $validatedData['logo'] = $logoName;
                } else {
                    return redirect()->back()->withErrors(['logo' => 'Logo konnte nicht hochgeladen werden.'])->withInput();
                }
            }

            // Neuen Klienten erstellen
            Clients::create($validatedData);

            // Erfolgreiche Erstellung, Weiterleitung zur Übersicht
            return redirect()->route('clients.index')->with('success', 'Klient erfolgreich erstellt.');
        } catch (\Exception $e) {
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

        //dd($clients);
        return view('clients.edit', compact('clients', 'taxrates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clients $client)
    {
        // Validierung der Eingabedaten - AUSSERHALB des try-catch!
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
            'style' => ['nullable', 'string', 'max:500'],
            'lastoffer' => ['required', 'integer'],
            'offermultiplikator' => ['required', 'integer'],
            'lastinvoice' => ['required', 'integer'],
            'invoicemultiplikator' => ['required', 'integer'],
            'max_upload_size' => ['required', 'integer'],
            'company_registration_number' => ['nullable', 'string', 'max:100'],
            'tax_number' => ['nullable', 'string', 'max:100'],
            'management' => ['nullable', 'string', 'max:200'],
            'regional_court' => ['nullable', 'string', 'max:200'],
            'color' => ['nullable', 'string', 'max:7'],
            'invoice_number_format' => ['nullable', 'string', 'max:50'],
            'invoice_prefix' => ['nullable', 'string', 'max:10'],
            'offer_prefix' => ['nullable', 'string', 'max:10'],
        ]);

        try {
            // Client-Objekt ist bereits durch Route Model Binding verfügbar
            Log::info('Client Update gestartet für ID: ' . $client->id . ' (Version: ' . $client->version . ')');

            // Bereite die neuen Daten vor
            $newData = $validatedData;

            // Logo hochladen, falls vorhanden
            if ($request->hasFile('logo')) {
                Log::info('Logo-Datei gefunden im Update, starte Upload...');
                $logoName = $this->uploadLogo($request->file('logo'));
                if ($logoName) {
                    $newData['logo'] = $logoName;
                    Log::info('Logo wurde für neue Version vorbereitet: ' . $logoName);
                } else {
                    return redirect()->back()->withErrors(['logo' => 'Logo konnte nicht hochgeladen werden.'])->withInput();
                }
            } else {
                Log::info('Keine Logo-Datei im Request gefunden');
                // Behalte das aktuelle Logo für die neue Version
                $newData['logo'] = $client->logo;
            }

            // Erstelle eine neue Version des Clients
            $newVersion = $client->createNewVersion($newData);
            
            Log::info('Neue Client-Version erstellt: ID ' . $newVersion->id . ' (Version: ' . $newVersion->version . ')');

            // Erfolgreiche Aktualisierung, Weiterleitung zur Übersicht
            return redirect()->route('clients.index')->with('success', 'Neue Client-Version (v' . $newVersion->version . ') erfolgreich erstellt.');
        } catch (\Exception $e) {
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
        // Hole alle Versionen dieses Clients
        $versions = $client->allVersions()->get();
        
        return view('clients.version-history', compact('client', 'versions'));
    }

    /**
     * Zeigt eine spezifische Version eines Clients
     */
    public function showVersion(Clients $client, $version)
    {
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
        
        // Hole den Client des aktuellen Benutzers
        $clients = Clients::where('id', $user->client_id)->firstOrFail();
        $taxrates = Taxrates::all();

        return view('clients.my-settings', compact('clients', 'taxrates'));
    }

    /**
     * Aktualisiert die Client-Einstellungen des aktuell eingeloggten Benutzers
     */
    public function updateMyClientSettings(Request $request)
    {
        $user = Auth::user();
        
        // Validierung der Eingabedaten
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
            'style' => ['nullable', 'string', 'max:500'],
            'lastoffer' => ['required', 'integer'],
            'offermultiplikator' => ['required', 'integer'],
            'lastinvoice' => ['required', 'integer'],
            'invoicemultiplikator' => ['required', 'integer'],
            'max_upload_size' => ['required', 'integer'],
            'company_registration_number' => ['nullable', 'string', 'max:100'],
            'tax_number' => ['nullable', 'string', 'max:100'],
            'management' => ['nullable', 'string', 'max:200'],
            'regional_court' => ['nullable', 'string', 'max:200'],
            'color' => ['nullable', 'string', 'max:7'],
            'invoice_number_format' => ['nullable', 'string', 'max:50'],
            'invoice_prefix' => ['nullable', 'string', 'max:10'],
            'offer_prefix' => ['nullable', 'string', 'max:10'],
        ]);

        try {
            // Hole NUR den Client des aktuellen Benutzers (Sicherheit!)
            $client = Clients::where('id', $user->client_id)->firstOrFail();

            // Logo hochladen, falls vorhanden
            if ($request->hasFile('logo')) {
                $logoName = $this->uploadLogo($request->file('logo'));
                if ($logoName) {
                    $client->logo = $logoName;
                } else {
                    return redirect()->back()->withErrors(['logo' => 'Logo konnte nicht hochgeladen werden.'])->withInput();
                }
            }

            // Aktualisiere die restlichen Felder
            $client->clientname = $validatedData['clientname'];
            $client->companyname = $validatedData['companyname'];
            $client->business = $validatedData['business'];
            $client->address = $validatedData['address'];
            $client->postalcode = $validatedData['postalcode'];
            $client->location = $validatedData['location'];
            $client->email = $validatedData['email'];
            $client->phone = $validatedData['phone'];
            $client->tax_id = $validatedData['tax_id'];
            $client->webpage = $validatedData['webpage'];
            $client->bank = $validatedData['bank'];
            $client->accountnumber = $validatedData['accountnumber'];
            $client->vat_number = $validatedData['vat_number'];
            $client->bic = $validatedData['bic'];
            $client->smallbusiness = $validatedData['smallbusiness'];
            $client->signature = $validatedData['signature'];
            $client->style = $validatedData['style'];
            $client->lastoffer = $validatedData['lastoffer'];
            $client->offermultiplikator = $validatedData['offermultiplikator'];
            $client->lastinvoice = $validatedData['lastinvoice'];
            $client->invoicemultiplikator = $validatedData['invoicemultiplikator'];
            $client->max_upload_size = $validatedData['max_upload_size'];
            $client->company_registration_number = $validatedData['company_registration_number'];
            $client->tax_number = $validatedData['tax_number'];
            $client->management = $validatedData['management'];
            $client->regional_court = $validatedData['regional_court'];
            $client->color = $validatedData['color'];
            $client->invoice_number_format = $validatedData['invoice_number_format'];
            $client->invoice_prefix = $validatedData['invoice_prefix'] ?? null;
            $client->offer_prefix = $validatedData['offer_prefix'] ?? null;

            $client->save();

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
