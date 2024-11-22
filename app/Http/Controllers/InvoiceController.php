<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoicepositions;
use App\Models\Invoices;
use App\Models\Condition;
use App\Models\Clients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $client_id = Auth::user()->client_id;

        $search = $request->input('search');

        // Abfrage der Rechnungen mit Kundenverknüpfung und Filterung nach client_id
        $invoices = Invoices::join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->where('customers.client_id', $client_id)
            ->orderBy('invoices.number', 'desc')
            ->when($search, function ($query, $search) {
                return $query->where('customers.customername', 'like', "%$search%")
                    ->orWhere('customers.companyname', 'like', "%$search%");
            })
            ->select('invoices.id','invoices.number','invoices.comment', 'customers.customername', 'invoices.date')
            ->paginate(15);

        //dd($invoices->toArray());

        return view('invoice.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($customer_id)
    {
        $client_id = Auth::user()->client_id;

        $client = Clients::where('id', '=', $client_id)->select('lastinvoice', 'invoicemultiplikator')->first();

        $invoice_raw_number = $client->lastinvoice ?? 0; // Fallback: 0
        $invoicemultiplikator = $client->invoicemultiplikator ?? 1000; // Standardwert für Multiplikator

        $invoicenumber = now()->year * $invoicemultiplikator +1000+ $invoice_raw_number;

        $invoice = Invoices::create([
            'customer_id' => $customer_id,
            'number' => $invoicenumber,
            'description' => '',
            'tax_id' => 1,
            'condition_id' => 1,
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
    public function edit($invoice)
    {
        $invoice = Invoices::where('invoices.id','=',$invoice)
            ->join('taxrates', 'invoices.tax_id','=','taxrates.id')
            ->join('customers','customers.id','=','invoices.customer_id')
            ->select(
                'invoices.id as invoice_id',
                'invoices.*',
                'customers.companyname as companyname',
                'taxrates.taxrate',
                'customers.customername as customername',
                'customers.address as address',
                'customers.country as country'
                ) // Nur das companyname von customers auswählen
            ->first(); // Nur den ersten Datensatz abrufen



        $invoicepositions = Invoicepositions::join('units', 'invoicepositions.unit_id', '=', 'units.id') // Inner Join mit der Tabelle 'units'
            ->where('invoicepositions.invoice_id','=',$invoice->id)
            ->select('invoicepositions.*', 'units.*') // Berechnung der Summe
            ->get();
        //dd($invoicepositions);

        //dd($invoice);
        $total_price = Invoices::join('invoicepositions', 'invoices.id', '=', 'invoicepositions.invoice_id')
            ->where('invoicepositions.invoice_id', '=', $invoice->id)
            ->select(DB::raw('SUM(invoicepositions.amount * invoicepositions.price) as total_price')) // Berechnung der Gesamtsumme
            ->first();
        //dd($total_price);


        $conditions = Condition::all();
        // Zugriff auf companyname
        //dd($invoicepositions);


        return view('invoice.edit', compact('invoice', 'conditions', 'invoicepositions','total_price'));
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
                'description' => 'required|string',
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
                'comment' => 'required|string',
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
                'depositamount' => 'required|string',
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
}
