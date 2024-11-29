<?php

namespace App\Http\Controllers;

use App\Models\Logos;
use App\Models\Clients;
use Illuminate\Http\Request;

class LogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logos = Logos::get();
        //dd($logos);
        return view ('logo.index', compact('logos'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Clients::all();



        return view('logo.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validierung der Eingaben
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'file' => 'required|file|mimes:jpg,jpeg,png,gif|max:2048', // Nur bestimmte Dateitypen erlauben
        'client_id' => 'required|integer'
    ]);
    //dd($validatedData);
    // Datei speichern
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $path = $file->store('logos', 'public'); // Speichert die Datei im "storage/app/public/logos/test"-Ordner

        // In der Datenbank speichern
        Logos::create([
            'name' => $validatedData['name'],
            'filename' => $file->getClientOriginalName(), // Originaler Dateiname
            'localfilename' => $path,
            'client_id' => $validatedData['client_id']
        ]);

        return redirect()->back()->with('success', 'Datei erfolgreich hochgeladen!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Logos $logos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Logos $logos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Logos $logos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Logos $logos)
    {
        //
    }
}
