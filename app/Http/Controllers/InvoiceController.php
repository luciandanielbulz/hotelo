<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoicepositions;
use App\Models\Invoices;
use App\Models\Condition;
use App\Models\Clients;
use App\Models\Offers;
use App\Models\Offerpositions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\TemplateHelper;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('invoice.index');
    }


    public function index_archivated(Request $request)
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        $search = $request->input('search');
        //dd($search);
        // Suche oder alle Kunden abfragen
        $invoices = Invoices::join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->where('customers.client_id', $clientId) // auth()->user()->client_id
            ->where('invoices.archived', '=', true)
            ->orderBy('number', 'desc')
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('customers.customername', 'like', "%{$search}%")
                        ->orWhere('customers.companyname', 'like', "%{$search}%")
                        ->orWhere('invoices.number', 'like', "%{$search}%");
                });
            })
            ->select('invoices.id as invoice_id', 'invoices.*', 'customers.*')
            ->paginate(10);

        $invoices->appends(['search' => $search]);

        //dd($offers->all()); // Zeigt die Ergebnisse an
        return view('invoice.index_archivated', compact('invoices'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($customer_id)
    {
        $client_id = Auth::user()->client_id;

        $customer = Customer::where('id','=',$customer_id)->first();

        $client = Clients::where('id', '=', $client_id)->select('lastinvoice', 'invoicemultiplikator')->first();

        $invoice_raw_number = $client->lastinvoice ?? 0; // Fallback: 0
        $invoicemultiplikator = $client->invoicemultiplikator ?? 1000; // Standardwert für Multiplikator

        $invoicenumber = now()->year * $invoicemultiplikator +1000+ $invoice_raw_number;

        $invoice = Invoices::create([
            'customer_id' => $customer_id,
            'number' => $invoicenumber,
            'description' => '',
            'tax_id' => 1,
            'condition_id' => $customer->condition_id,
        ]);

        Clients::where('id', '=', $client_id)->update([
            'lastinvoice' => $invoice_raw_number + 1, // Erhöhe den Wert um 1
        ]);


        return redirect()->route('invoice.edit', ['invoice' => $invoice->id]);
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
    public function show(Invoices $invoice)
    {
        return view('invoice.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($invoiceId)
    {
        // Aktuell eingeloggter Benutzer
        $user = Auth::user();
        $clientId = $user->client_id;

        // Überprüfen, ob die Rechnung zum aktuellen Client gehört
        $invoice = Invoices::where('invoices.id', '=', $invoiceId)
            ->join('taxrates', 'invoices.tax_id', '=', 'taxrates.id')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->where('customers.client_id', '=', $clientId) // Sicherstellen, dass der Kunde zum aktuellen Client gehört
            ->select(
                'invoices.id as invoice_id',
                'invoices.*',
                'customers.companyname as companyname',
                'taxrates.taxrate',
                'customers.customername as customername',
                'customers.address as address',
                'customers.country as country',
                'customers.postalcode',
                'customers.location',
                'customers.country'
            )
            ->first();

        // Wenn keine Rechnung gefunden wird, Zugriff verweigern
        if (!$invoice) {
            abort(403, 'Sie sind nicht berechtigt, diese Rechnung zu bearbeiten.');
        }

        // Positionen der Rechnung abrufen
        $invoicepositions = Invoicepositions::join('units', 'invoicepositions.unit_id', '=', 'units.id') // Inner Join mit der Tabelle 'units'
            ->where('invoicepositions.invoice_id', '=', $invoice->id)
            ->select('invoicepositions.*', 'units.*') // Daten aus beiden Tabellen abrufen
            ->get();

        // Gesamtsumme berechnen
        $total_price = Invoices::join('invoicepositions', 'invoices.id', '=', 'invoicepositions.invoice_id')
            ->where('invoicepositions.invoice_id', '=', $invoice->id)
            ->select(DB::raw('SUM(invoicepositions.amount * invoicepositions.price) as total_price'))
            ->first();

        // Bedingungen laden
        $conditions = Condition::all();

        // View zurückgeben
        return view('invoice.edit', compact('invoice', 'conditions', 'invoicepositions', 'total_price'));
    }


    public function copy ($invoiceid){

            //dd($invoiceid);
            $client_id = Auth::user()->client_id;

            $client = Clients::where('id', '=', $client_id)->select('lastinvoice', 'invoicemultiplikator')->first();

            $invoice_raw_number = $client->lastinvoice ?? 0; // Fallback: 0
            $invoicemultiplikator = $client->invoicemultiplikator ?? 1000; // Standardwert für Multiplikator

            $invoicenumber = now()->year * $invoicemultiplikator +1000+ $invoice_raw_number;



            $invoiceId = $invoiceid;

            //dd("test");
            $invoice = Invoices::findOrFail($invoiceId);

            $invoicePositions = InvoicePositions::where('invoice_id', '=',$invoiceId)
                ->where('issoftdeleted','!=',1)
                ->get();

            //dd($invoicePositions);

            $invoice = Invoices::create([
                'customer_id' => $invoice->customer_id,
                'date' => now(),
                'number' => $invoicenumber,
                'description' => $invoice->description,
                'tax_id' => $invoice->tax_id,
                'taxburden' => $invoice->taxburden,
                'depositamount' => $invoice->depositamount,
                'periodfrom' => $invoice->periodfrom,
                'periodto' => $invoice->periodto,
                'condition_id' => $invoice->condition_id,
                'payed' => 0,
                'payeddate' => '',
                'archived' => 0,
                'archiveddate' => '',
                'sequence' => $invoice->sequence,
                'comment' => $invoice->comment,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            //dd("erstellt");

            Clients::where('id', '=', $client_id)->update([
                'lastinvoice' => $invoice_raw_number + 1, // Erhöhe den Wert um 1
            ]);

            //dd($invoice);
            // 4. Neue InvoicePositions erstellen
            foreach ($invoicePositions as $position) {
                //dd($position->sequence);
                Invoicepositions::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $position->amount,
                    'designation' => $position->description,
                    'details' => $position->details,
                    'unit_id' => $position->unit_id,
                    'price' => $position->price,
                    'positiontext' => $position->positiontext,
                    'sequence' => $position->sequence,
                    'created_at' => now(),
                    'updated_at' => now(),
                    // Weitere Felder hier übernehmen
                ]);
            }
            //dd("erledigtpos");

            // 5. Erfolgsmeldung oder Weiterleitung
            return redirect()->route('invoice.edit', ['invoice' => $invoice->id])
            ->with('success', 'Invoice successfully created and opened in edit mode!');


    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoices $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoices $invoice)
    {
        //
    }

    public function updatetaxrate(Request $request)
    {
        Log::info('function bestätigt');
        //dd($request);
        try {
            Log::info('Request data: ', $request->all()); // Beispiel-Logging

            // Deine Logik hier ...
            $validated = $request->validate([
                'invoice_id' => 'required|integer|exists:invoices,id',
                'tax_id' => 'required|integer|exists:taxrates,id',
            ]);

            $invoice = Invoices::findOrFail($validated['invoice_id']);
            $invoice->tax_id = $validated['tax_id'];
            $invoice->save();

            return response()->json(['message' => 'Steuersatz erfolgreich aktualisiert.'], 200);
        } catch (\Exception $e) {
            Log::error('Fehler beim Aktualisieren des Steuersatzes: ' . $e->getMessage(), [
                'offer_id' => $request->offer_id,
                'tax_id' => $request->tax_id
            ]);
            return response()->json(['message' => 'Fehler: ' . $e->getMessage()], 500);
        }
    }

    public function updateinvoicedate(Request $request)
    {
        try {
            Log::info('Request data: ', $request->all()); // Beispiel-Logging

            // Deine Logik hier ...
            $validated = $request->validate([
                'invoice_id' => 'required|integer|exists:invoices,id',
                'invoicedate' => 'required|date',
            ]);

            //dd($validated);
            $invoice = Invoices::findOrFail($validated['invoice_id']);
            $invoice->date = $validated['invoicedate'];
            $invoice->save();

            return response()->json(['message' => 'Datum erfolgreich aktualisiert.'], 200);
        } catch (\Exception $e) {
            Log::error('Fehler beim Aktualisieren des Datums: ' . $e->getMessage(), [
                'offer_id' => $request->offer_id,
                'invoicedate' => $request->invoicedate
            ]);
            return response()->json(['message' => 'Fehler: ' . $e->getMessage()], 500);
        }
    }
    public function updatedescription(Request $request)
    {
        try {
            Log::info('Request data: ', $request->all()); // Beispiel-Logging

            // Deine Logik hier ...
            $validated = $request->validate([
                'invoice_id' => 'required|integer|exists:invoices,id',
                'description' => 'nullable|string',
            ]);

            //dd($validated);
            $invoice = Invoices::findOrFail($validated['invoice_id']);
            $invoice->description = $validated['description'];
            $invoice->save();

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
                'invoice_id' => 'required|integer|exists:invoices,id',
                'comment' => 'nullable|string',
            ]);

            //dd($validated);
            $invoice = Invoices::findOrFail($validated['invoice_id']);
            $invoice->comment = $validated['comment'];
            $invoice->save();

            return response()->json(['message' => 'Kommentar erfolgreich aktualisiert.'], 200);
        } catch (\Exception $e) {
            Log::error('Fehler beim Aktualisieren des Kommentars: ' . $e->getMessage(), [
                'offer_id' => $request->offer_id,
                'comment' => $request->comment
            ]);
            return response()->json(['message' => 'Fehler: ' . $e->getMessage()], 500);
        }
    }

    public function updatenumber(Request $request)
        {
            try {
                Log::info('Request data: ', $request->all()); // Beispiel-Logging

                // Deine Logik hier ...
                $validated = $request->validate([
                    'invoice_id' => 'required|integer|exists:invoices,id',
                    'number' => 'required|string',
                ]);

                //dd($validated);
                $invoice = Invoices::findOrFail($validated['invoice_id']);
                $invoice->number = $validated['number'];
                $invoice->save();

                Log::info('Nummer erfolgreich aktualisiert: ');
                return response()->json(['message' => 'Nummer erfolgreich aktualisiert.'], 200);
            } catch (\Exception $e) {
                Log::error('Fehler beim Aktualisieren der Nummer: ' . $e->getMessage(), [
                    'offer_id' => $request->offer_id,
                    'number' => $request->number
                ]);
                return response()->json(['message' => 'Fehler: ' . $e->getMessage()], 500);
            }
        }

    public function updatecondition(Request $request)
    {
        try {
            Log::info('Request data: ', $request->all()); // Beispiel-Logging

            // Deine Logik hier ...
            $validated = $request->validate([
                'invoice_id' => 'required|integer|exists:invoices,id',
                'condition_id' => 'required|string',
            ]);

            //dd($validated);
            $invoice = Invoices::findOrFail($validated['invoice_id']);
            $invoice->condition_id = $validated['condition_id'];
            $invoice->save();

            Log::info('Condition erfolgreich aktualisiert: ');
            return response()->json(['message' => 'Zahlungsziel erfolgreich aktualisiert.'], 200);
        } catch (\Exception $e) {
            Log::error('Fehler beim Aktualisieren der Konditionen: ' . $e->getMessage(), [
                'offer_id' => $request->offer_id,
                'condition_id' => $request->condition_id
            ]);
            return response()->json(['message' => 'Fehler: ' . $e->getMessage()], 500);
        }
    }
    public function updatedeposit(Request $request)
    {
        try {
            Log::info('Request data: ', $request->all()); // Beispiel-Logging

            // Deine Logik hier ...
            $validated = $request->validate([
                'invoice_id' => 'required|integer|exists:invoices,id',
                'depositamount' => 'nullable|string',
            ]);

            //dd($validated);
            $invoice = Invoices::findOrFail($validated['invoice_id']);
            $invoice->depositamount = $validated['depositamount'];
            $invoice->save();

            Log::info('Anzahlung erfolgreich aktualisiert: ');
            return response()->json(['message' => 'Anzahlung erfolgreich aktualisiert.'], 200);
        } catch (\Exception $e) {
            Log::error('Fehler beim Aktualisieren der Anzahlung: ' . $e->getMessage(), [
                'offer_id' => $request->offer_id,
                'depositamount' => $request->depositamount
            ]);
            return response()->json(['message' => 'Fehler: ' . $e->getMessage()], 500);
        }
    }

    public function createinvoicefromoffer(Request $request) {

        $client_id = Auth::user()->client_id;

        $client = Clients::where('id', '=', $client_id)->select('lastinvoice', 'invoicemultiplikator')->first();

        $invoice_raw_number = $client->lastinvoice ?? 0; // Fallback: 0
        $invoicemultiplikator = $client->invoicemultiplikator ?? 1000; // Standardwert für Multiplikator

        $invoicenumber = now()->year * $invoicemultiplikator +1000+ $invoice_raw_number;

        //dd($invoicemultiplikator);
        $request->validate([
            'offerid' => 'required|exists:offers,id', // Existenzprüfung in der Tabelle Offers
        ]);

        $offerId = $request->offerid;
        //dd($offerId);


        $offer = Offers::findOrFail($offerId);
        $offerPositions = OfferPositions::where('offer_id', '=',$offerId)
            ->where('issoftdeleted','!=',1)
            ->get();

        //dd($offerPositions);

        $invoice = Invoices::create([
            'customer_id' => $offer->customer_id,
            'date' => now(),
            'number' => $invoicenumber,
            'description' => $offer->description,
            'tax_id' => $offer->tax_id,
            'taxburden' => $offer->taxburden,
            'deposit' => $offer->deposit,
            'depositamount' => $offer->depositamount,
            'periodfrom' => $offer->periodfrom,
            'periodto' => $offer->periodto,
            'condition_id' => $offer->condition_id,
            'payed' => 0,
            'payeddate' => '',
            'archived' => 0,
            'archiveddate' => '',
            'sequence' => $offer->sequence,
            'comment' => $offer->comment,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        Clients::where('id', '=', $client_id)->update([
            'lastinvoice' => $invoice_raw_number + 1, // Erhöhe den Wert um 1
        ]);

        //dd($offerPositions);
        // 4. Neue InvoicePositions erstellen
        foreach ($offerPositions as $position) {
            //dd($position->sequence);
            Invoicepositions::create([
                'invoice_id' => $invoice->id,
                'amount' => $position->amount,
                'designation' => $position->designation,
                'details' => $position->details,
                'unit_id' => $position->unit_id,
                'price' => $position->price,
                'positiontext' => $position->positiontext,
                'sequence' => $position->sequence,
                'created_at' => now(),
                'updated_at' => now(),
                // Weitere Felder hier übernehmen
            ]);
        }

        // 5. Erfolgsmeldung oder Weiterleitung
        return redirect()->route('invoice.edit', ['invoice' => $invoice->id])
        ->with('success', 'Invoice successfully created and opened in edit mode!');

    }

    public function sendmail(Request $request) {
        // Daten abrufen
        $clientdata = Clients::join('customers', 'customers.client_id', '=', 'clients.id')
            ->join('invoices', 'invoices.customer_id', '=', 'customers.id')
            ->where('invoices.id', '=', $request->objectid)
            ->select(
                'customers.email as getter_email',
                'clients.email as sender_email',
                'clients.*',
                'customers.*',
                'invoices.*',
                'invoices.id as invoice_id'
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
            '{objekt}' => 'Rechnung', // Objektart als fixer Wert
            '{O}' => 'Rechnung', // Objektart als fixer Wert
            '{objekt_mit_artikel}' => 'die Rechnung', // Objektart als fixer Wert
            '{OA}' => 'die Rechnung', // Objektart als fixer Wert
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
        // Werte an die View weitergeben
        return view('invoice.sendmail', compact('clientdata', 'emailsubject', 'emailbody'));
    }


}
