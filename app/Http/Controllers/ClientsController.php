<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Illuminate\Http\Request;
use App\Models\Taxrates;
use Illuminate\Support\Facades\Log;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resourc
     */
    public function index()
    {

        $clients=Clients::orderBy('clientname')
            ->paginate(15);
        //dd($clients);
        return view('clients.index', compact('clients'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
            'logo'           => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Validierung für das Logo
            'logoheight'     => ['nullable', 'integer', 'max:500'],
            'logowidth'      => ['nullable', 'integer', 'max:500'],
            'signature'      => ['nullable', 'string', 'max:1000'],
            'style'          => ['nullable', 'string', 'max:500'],
            'lastoffer'      => ['required', 'integer'],
            'offermultiplikator' => ['required', 'integer'],
            'lastinvoice'    => ['required', 'integer'],
            'invoicemultiplikator' => ['required', 'integer'],
        ]);

        try {
            // Logo hochladen, falls vorhanden
            if ($request->hasFile('logo')) {
                $validatedData['logo'] = $request->file('logo')->store('logos', 'public'); // Speichert das Logo in storage/app/public/logos
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
    public function edit($client_id)
    {
        $clients=Clients::where('clients.id','=',$client_id)
            ->orderBy('clientname')
            ->select('clients.*')
            ->first();

        $taxrates = Taxrates::all();


        //dd($clients);
        return view('clients.edit', compact('clients', 'taxrates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clients $clients)
{
    try {
        Log::info('Request data: ', $request->all()); // Beispiel-Logging

        // Validierung der Eingabedaten
        $validatedData = $request->validate([
            'id' => ['required', 'integer'],
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
            'smallbusiness' => ['nullable', 'boolean'],
            'logo' => ['nullable', 'string', 'max:500'],
            'logoheight' => ['nullable', 'integer', 'max:500'],
            'logowidth' => ['nullable', 'integer', 'max:500'],
            'signature' => ['nullable', 'string', 'max:1000'],
            'style' => ['nullable', 'string', 'max:500'],
            'lastoffer' => ['required', 'integer'],
            'offermultiplikator' => ['required', 'integer'],
            'lastinvoice' => ['required', 'integer'],
            'invoicemultiplikator' => ['required', 'integer'],
        ]);

        //dd($validatedData);
        // Aktualisierung des Klienten
        $client = Clients::findOrFail($validatedData['id']);
        $client->update($validatedData);

        // Erfolgreiche Aktualisierung, Weiterleitung zur Übersicht
        return redirect()->route('clients.index')->with('success', 'Klient erfolgreich aktualisiert.');
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
}
