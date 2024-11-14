<?php

namespace App\Http\Controllers;

use App\Models\Offerpositions;
use App\Models\Units;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OfferpositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 'test';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return 'test';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Offerpositions $offerposition)
    {
        return 'test';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offerpositions $offerposition)
    {
        $units=Units::all();

        //dd($offerposition->id);
        $offerpositioncontent = Offerpositions::join('offers', 'offerpositions.offer_id', '=', 'offers.id')
        ->where('offerpositions.id','=',$offerposition->id) // auth()->user()->client_id
        ->select('offerpositions.*')
        ->first(); // Nur den ersten Datensatz abrufen

        return view('offerposition.edit', compact('offerpositioncontent', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //dd($offerposition);

        try {
            Log::info('Request data: ', $request->all()); // Debugging-Log

            $validated = $request->validate([
                'id' => 'required|integer|exists:offerpositions,id',
                'amount' => 'required|numeric|min:0',
                'unit_id' => 'required|integer|exists:units,id',
                'designation' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'details' => 'nullable|string|max:1000',
            ]);

            // Datenbankeintrag finden
            $offerposition = Offerpositions::findOrFail($validated['id']);

            // Felder aktualisieren
            $offerposition->amount = $validated['amount'];
            $offerposition->unit_id = $validated['unit_id'];
            $offerposition->designation = $validated['designation'];
            $offerposition->price = $validated['price'];
            $offerposition->details = $validated['details'] ?? null;

            // Speichern
            $offerposition->save();

            // Weiterleitung zur "offer" View mit der ID des zugehörigen Angebots
            return redirect()->route('offer.edit', ['offer' => $offerposition->offer_id])
                             ->with('success', 'Steuersatz erfolgreich aktualisiert.');
        } catch (\Exception $e) {
            Log::error('Fehler beim Aktualisieren des Steuersatzes: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Fehler: ' . $e->getMessage());
        }




    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offerpositions $offerposition)
    {
        return 'test';
    }
}
