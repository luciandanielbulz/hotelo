<?php

namespace App\Http\Controllers;

use App\Models\Offerpositions;
use App\Models\Offers;
use App\Models\Customer;
use App\Models\Condition;
use App\Models\Clients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importiere DB, falls du es benötigs
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\TemplateHelper;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        return view('offer.index');

    }

    public function index_archivated(Request $request)
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        $search = $request->input('search');
        //dd($search);
        // Suche oder alle Kunden abfragen
        $offers = Offers::join('customers', 'offers.customer_id', '=', 'customers.id')
            ->where('customers.client_id', $clientId) // auth()->user()->client_id
            ->where('offers.archived', '=', true)
            ->orderBy('number', 'desc')
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('customers.customername', 'like', "%{$search}%")
                        ->orWhere('customers.companyname', 'like', "%{$search}%")
                        ->orWhere('offers.number', 'like', "%{$search}%");
                });
            })
            ->select('offers.id as offer_id', 'offers.*', 'customers.*')
            ->paginate(9);

        //dd($offers->toSQL(), $offers->getBindings());

        $offers->appends(['search' => $search]);
        //dd($offers->all()); // Zeigt die Ergebnisse an
        return view('offer.index_archivated', compact('offers'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($customer_id)
    {
        $client_id = Auth::user()->client_id;

        $customer = Customer::where('id','=',$customer_id)->first();

        $client = Clients::where('id', '=', $client_id)->select('lastoffer', 'offermultiplikator', 'invoice_number_format', 'tax_id')->first();

        $offer_raw_number = $client->lastoffer ?? 0; // Fallback: 0

        $offernumber = $client->generateOfferNumber();



        // Erstelle das Angebot mit der berechneten Nummer
        $offer = Offers::create([
            'customer_id' => $customer_id,
            'number' => $offernumber, // Verwende die berechnete Angebotsnummer
            'description' => 'test',
            'tax_id' => $client->tax_id ?? 1, // Verwende Client-Steuersatz oder Fallback
            'condition_id' => $customer->condition_id,
        ]);

        // Aktualisiere die `lastoffer`-Spalte für den Client
        Clients::where('id', '=', $client_id)->update([
            'lastoffer' => $offer_raw_number + 1, // Erhöhe den Wert um 1
        ]);

        // Weiterleitung zur Angebotsdetailseite
        return redirect()->route('offer.edit', ['offer' => $offer->id]);



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
    public function show(Offers $offer)
    {
        //dd($offer->id);

        $offercontent = Offers::join('customers', 'offers.customer_id', '=', 'customers.id')
            ->where('customers.client_id', 1)
            ->where('offers.id','=',$offer->id) // auth()->user()->client_id
            ->select('offers.id as offer_id','offers.*', 'customers.*') // Nur das companyname von customers auswählen
            ->first(); // Nur den ersten Datensatz abrufen
        //dd($offer_dataset);
        return view('offer.edit', compact('offercontent'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offers $offer)
    {
        // Aktuell eingeloggter Benutzer
        $user = Auth::user();
        $clientId = $user->client_id;

        // Überprüfen, ob das Angebot zum aktuellen Client gehört
        $offer = Offers::join('customers', 'offers.customer_id', '=', 'customers.id')
            ->join('taxrates', 'offers.tax_id', '=', 'taxrates.id') // Join mit der taxrates Tabelle
            ->where('customers.client_id', '=', $clientId) // Dynamischer client_id
            ->where('offers.id', '=', $offer->id) // Angebot mit passender ID finden
            ->select(
                'offers.id as offer_id',
                'offers.*',
                'offers.date as date',
                'customers.companyname as companyname',
                'taxrates.taxrate',
                'customers.customername as customername',
                'customers.address as address',
                'customers.country as country',
                'customers.postalcode as postalcode',
                'customers.location as location'
            )
            ->first();

        // Wenn kein passendes Angebot gefunden wird, Zugriff verweigern
        if (!$offer) {
            abort(403, 'Sie sind nicht berechtigt, dieses Angebot zu bearbeiten.');
        }

        // Berechnung der Gesamtsumme für das Angebot
        $total_price = Offers::join('offerpositions', 'offers.id', '=', 'offerpositions.offer_id')
            ->select(DB::raw('SUM(offerpositions.Amount * offerpositions.Price) as total_price')) // Berechnung der Gesamtsumme
            ->where('offers.id', '=', $offer->id)
            ->first();

        // Bedingungen laden (nur aktive für aktuellen Client)
        $user = Auth::user();
        $conditions = Condition::where('client_id', $user->client_id)->get();

        // View zurückgeben
        return view('offer.edit', compact('offer', 'conditions', 'total_price'));
    }



    public function destroy(Offers $offer)
    {
        //
    }

    public function updatedescription(Request $request)
    {
        try {
            Log::info('Request data: ', $request->all()); // Beispiel-Logging

            // Deine Logik hier ...
            $validated = $request->validate([
                'offer_id' => 'required|integer|exists:offers,id',
                'description' => 'nullable|string',
            ]);

            //dd($validated);
            $offer = Offers::findOrFail($validated['offer_id']);
            $offer->description = $validated['description'];
            $offer->save();

            return response()->json(['message' => 'Beschreibung erfolgreich aktualisiert.'], 200);
        } catch (\Exception $e) {
            Log::error('Fehler beim Aktualisieren der Beschreibung: ' . $e->getMessage(), [
                'offer_id' => $request->offer_id,
                'description' => $request->description
            ]);
            return response()->json(['message' => 'Fehler: ' . $e->getMessage()], 500);
        }
    }

    public function updatecomment(Request $request)
    {
        try {
            Log::info('Request data: ', $request->all()); // Beispiel-Logging

            // Deine Logik hier ...
            $validated = $request->validate([
                'offer_id' => 'required|integer|exists:offers,id',
                'comment' => 'nullable|string',
            ]);

            Log::info('Validated data: ', $validated); // Beispiel-Logging

            $offer = Offers::findOrFail($validated['offer_id']);
            $offer->comment = $validated['comment'];
            $offer->save();
            Log::info('Saved data: ', $offer->toArray());


            return response()->json(['message' => 'Kommentar erfolgreich aktualisiert.'], 200);
        } catch (\Exception $e) {
            Log::error('Fehler beim Aktualisieren des Kommentars: ' . $e->getMessage(), [
                'offer_id' => $request->offer_id,
                'comment' => $request->comment
            ]);
            return response()->json(['message' => 'Fehler: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Offers $offer)
    {
     
     
        //
    }

    public function sendmail(Request $request) {
        // Daten abrufen
        $clientdata = Clients::join('customers', 'customers.client_id', '=', 'clients.id')
            ->join('offers', 'offers.customer_id', '=', 'customers.id')
            ->where('offers.id', '=', $request->objectid)
            ->select(
                'customers.email as getter_email',
                'clients.email as sender_email',
                'clients.*',
                'customers.*',
                'offers.*',
                'offers.id as offer_id'
            )
            ->first();

            $actual_month = date('m');
            $actual_month_name =  Carbon::now()->translatedFormat('F');
            $actual_year = date('Y');
            $now = Carbon::now();

            // Ermitteln des aktuellen Quartals (1 bis 4)
            $currentQuarter = $now->quarter;

            // Optional: Quartal als beschreibender Text
            $quarterNames = [
                1 => '1. Quartal (Januar - März)',
                2 => '2. Quartal (April - Juni)',
                3 => '3. Quartal (Juli - September)',
                4 => '4. Quartal (Oktober - Dezember)',
            ];

            $currentQuarterName = $quarterNames[$currentQuarter];


        //dd($clientdata->emailsubject);
        // Platzhalter in emailsubject und emailbody ersetzen
        $variables = [
            '{signatur}' => $clientdata->signature ?? '', // Signatur aus Clients
            '{S}' => $clientdata->signature ?? '', // Signatur aus Clients
            '{objekt}' => 'Angebot', // Objektart als fixer Wert
            '{O}' => 'Angebot', // Objektart als fixer Wert
            '{objekt_mit_artikel}' => 'das Angebot', // Objektart als fixer Wert
            '{OA}' => 'das Angebot', // Objektart als fixer Wert
            '{objektnummer}' => $clientdata->number ?? '', // Objektnummer aus Invoices
            '{ON}' => $clientdata->number ?? '', // Objektnummer aus Invoices
            '{aktuelles_monat-aktuelles_jahr}' => $actual_month . "/" . $actual_year,
            '{aktueller_monatsname}' => $actual_month_name,
            '{M0N}' => $actual_month_name,
            '{aktuelles_quartal}' => $currentQuarterName,
            '{Q0N}' => $currentQuarterName,
            '{akutelles_monat}' => $actual_month,
            '{M0}' => $actual_month,
            '{aktuelles_jahr}' => $actual_year,
            '{Y0}' => $actual_year,

        ];

        //dd($placeholders);

        //$emailsubject = str_replace(array_keys($placeholders), array_values($placeholders), $clientdata->emailsubject);
        $emailsubject = TemplateHelper::replacePlaceholders($clientdata->emailsubject, $variables);
        //$emailbody = str_replace(array_keys($placeholders), array_values($placeholders), $clientdata->emailbody);
        $emailbody = TemplateHelper::replacePlaceholders($clientdata->emailbody, $variables);

        
        //dd($emailsubject);
        // PDF Dateinamen erstellen
        $pdf_filename = 'Angebot_' . $clientdata->number . '.pdf';

        // Werte an die View weitergeben
        return view('offer.sendmail', compact('clientdata', 'emailsubject', 'emailbody', 'pdf_filename'));
    }
}
