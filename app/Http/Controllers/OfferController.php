<?php

namespace App\Http\Controllers;

use App\Models\Offerpositions;
use App\Models\Offers;
use App\Models\Customer;
use App\Models\Conditions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importiere DB, falls du es benötigs
use Illuminate\Support\Facades\Log;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $search = $request->input('search');
        //dd($search);
        // Suche oder alle Kunden abfragen
        $offers = Offers::join('customers', 'offers.customer_id', '=', 'customers.id')
            ->where('customers.client_id', 1) // auth()->user()->client_id
            ->orderBy('number','desc')
            ->when($search, function ($query, $search) {
                return $query->where('customers.customername', 'like', "%$search%")
                    ->orWhere('customers.companyname', 'like', "%$search%");
            })
            ->select('offers.id as offer_id','offers.*','customers.*')

            ->paginate(15);

        //dd($offers->all()); // Zeigt die Ergebnisse an
        return view('offer.index', compact('offers'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($customer_id)
    {

        // Hole den Kunden aus der Datenbank
        $customer = Customer::findOrFail($customer_id);

        // Erstelle das Angebot mit Standardwerten
        $offer = Offers::create([
            'customer_id' => $customer_id,
            'number' => '20241923',
            'description' => 'test',
            'tax_id' => 1,
            'condition_id' => 1,
        ]);

        //dd($offer->id);
        // Weiterleitung zur Angebotsdetailseite
        //return redirect()->route('offer.edit', ['id' => $offer->id]);
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
    public function edit($offer)
    {
        //dd($offer);

        $offercontent = Offers::join('customers', 'offers.customer_id', '=', 'customers.id')
            ->join('taxrates', 'offers.tax_id', '=', 'taxrates.id') // Join mit der taxrates Tabelle
            ->where('customers.client_id', '=', 1) // Dynamischer client_id
            ->where('offers.id', '=', $offer)
            ->select('offers.id as offer_id', 'offers.*', 'customers.companyname', 'taxrates.taxrate') // taxrates Spalte auswählen
            ->first();

        $total_price = Offers::join('offerpositions', 'offers.id', '=', 'offerpositions.offer_id')
            ->select(DB::raw('SUM(offerpositions.Amount * offerpositions.Price) as total_price')) // Berechnung der Gesamtsumme
            ->where('offers.id','=',$offer)
            ->first();
        //dd($total_price);
        $conditions = Conditions::all();

        return view('offer.edit', compact('offercontent','conditions', 'total_price'));
    }


    public function destroy(Offers $offer)
    {
        //
    }

    public function updateTaxRate(Request $request)
    {
        try {
            Log::info('Request data: ', $request->all()); // Beispiel-Logging

            // Deine Logik hier ...
            $validated = $request->validate([
                'offer_id' => 'required|integer|exists:offers,id',
                'tax_id' => 'required|integer|exists:taxrates,id',
            ]);

            $offer = Offers::findOrFail($validated['offer_id']);
            $offer->tax_id = $validated['tax_id'];
            $offer->save();

            return response()->json(['message' => 'Steuersatz erfolgreich aktualisiert.'], 200);
        } catch (\Exception $e) {
            Log::error('Fehler beim Aktualisieren des Steuersatzes: ' . $e->getMessage(), [
                'offer_id' => $request->offer_id,
                'tax_id' => $request->tax_id
            ]);
            return response()->json(['message' => 'Fehler: ' . $e->getMessage()], 500);
        }
    }

    public function updateofferdate(Request $request)
    {
        try {
            Log::info('Request data: ', $request->all()); // Beispiel-Logging

            // Deine Logik hier ...
            $validated = $request->validate([
                'offer_id' => 'required|integer|exists:offers,id',
                'offerdate' => 'required|date',
            ]);

            //dd($validated);
            $offer = Offers::findOrFail($validated['offer_id']);
            $offer->date = $validated['offerdate'];
            $offer->save();

            return response()->json(['message' => 'Steuersatz erfolgreich aktualisiert.'], 200);
        } catch (\Exception $e) {
            Log::error('Fehler beim Aktualisieren des Datums: ' . $e->getMessage(), [
                'offer_id' => $request->offer_id,
                'offerdate' => $request->offerdate
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
                'offer_id' => 'required|integer|exists:offers,id',
                'number' => 'required|integer',
            ]);

            $offer = Offers::findOrFail($validated['offer_id']);
            $offer->number = $validated['number'];
            $offer->save();

            return response()->json(['message' => 'Angebotsnummer erfolgreich aktualisiert.'], 200);
        } catch (\Exception $e) {
            Log::error('Fehler beim Aktualisieren der Nummer: ' . $e->getMessage(), [
                'offer_id' => $request->offer_id,
                'number' => $request->number
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
                'offer_id' => 'required|integer|exists:offers,id',
                'description' => 'required|string',
            ]);

            //dd($validated);
            $offer = Offers::findOrFail($validated['offer_id']);
            $offer->description = $validated['description'];
            $offer->save();

            return response()->json(['message' => 'Steuersatz erfolgreich aktualisiert.'], 200);
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
                'comment' => 'required|string',
            ]);

            //dd($validated);
            $offer = Offers::findOrFail($validated['offer_id']);
            $offer->comment = $validated['comment'];
            $offer->save();

            return response()->json(['message' => 'Steuersatz erfolgreich aktualisiert.'], 200);
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
}
