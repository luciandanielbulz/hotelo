<?php

namespace App\Http\Controllers;

use App\Models\Permissions;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permissions::all();
        //dd($permissions);
        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permissions.create');
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

        Permissions::create($validatedData);

        // Erfolgsnachricht und Weiterleitung
        return redirect()->route('permissions.index')->with('message', 'Recht erfolgreich gespeichert!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permissions $permissions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($permissions_id)
    {
        //dd($role_id);
        $role = Permissions::find($permissions_id);

        $permissions = Permissions::where('permissions.id','=',$permissions_id)
            ->first();

        $raw_permissions = Permissions::all();

        //dd($permissions);
        return view('permissions.edit', compact('permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permissions $permission)
    {
        //dd($request);
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

        $permission->update($validatedData);

        return to_route('permissions.index')->with('message', 'Recht wurde geändert');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permissions $permissions)
    {
        //
    }
}
