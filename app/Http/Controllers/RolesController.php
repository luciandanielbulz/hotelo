<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Roles::all();

        return view('roles.index', compact('roles'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);
        $validatedData = $request->validate([

            'name' => ['string', 'max:50'],
            'description' => ['nullable', 'string', 'max:200'],
        ]);

        Roles::create($validatedData);

        // Erfolgsnachricht und Weiterleitung
        return redirect()->route('roles.index')->with('message', 'Daten erfolgreich gespeichert!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Roles $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($role_id)
    {
        $role = Roles::find($role_id);
        //dd($role);

        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Roles $role)
    {

        try {
            // Hier wird die Validierung durchgeführt
            $validatedData = $request->validate([
                'name' => ['string', 'max:200'],
                'description' => ['string', 'max:200']
            ]);

            // Wenn die Validierung erfolgreich ist, fahre hier fort
            // Speichere die Daten oder führe deine gewünschte Aktion aus

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Hier kannst du Fehler behandeln, wenn die Validierung fehlschlägt
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
        //dd($validatedData);

        $role->update($validatedData);

        return to_route('roles.index')->with('message', 'Rolle wurde geändert');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Roles $role)
    {
        //
    }
}
