<?php

namespace App\Http\Controllers;

use App\Models\Invoicepositions; // Achte darauf, dass du das Plural-Modell verwendest
use Illuminate\Http\Request;
use App\Models\Units;

class InvoicepositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoicepositions = Invoicepositions::all(); // Hier wird das Plural verwendet
        return view('invoicepositions.index', compact('invoicepositions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Logik für das Erstellen einer neuen Position
        return view('invoicepositions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validierung und Speicherung der neuen Position
        $validatedData = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        Invoicepositions::create($validatedData);

        return redirect()->route('invoicepositions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoicepositions $invoiceposition) // Hier wird das Plural verwendet
    {
        // Anzeige der Position
        return view('invoicepositions.show', compact('invoiceposition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoicepositions $invoiceposition) // Hier wird das Plural verwendet
    {

        $units=Units::all();

        //dd($invoiceposition->id);
        $position = Invoicepositions::join('invoices', 'invoicepositions.invoice_id', '=', 'invoices.id')
        ->where('invoicepositions.id','=',$invoiceposition->id) // auth()->user()->client_id
        ->select('invoicepositions.*')
        ->first(); // Nur den ersten Datensatz abrufen
        //dd($position);

        return view('invoicepositions.edit', compact('position', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoicepositions $invoiceposition) // Hier wird das Plural verwendet
    {
        dd($request);
        // Validierung und Aktualisierung der Position
        $validatedData = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'unit_id' => 'required|integer|max:100',
            'price' => 'required|numeric',
            'details' => 'string|max:2000'
        ]);

        $invoiceposition->update($validatedData);

        return redirect()->route('invoicepositions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoicepositions $invoiceposition) // Hier wird das Plural verwendet
    {
        // Löschen der Position
        $invoiceposition->delete();

        return redirect()->route('invoicepositions.index');
    }
}
