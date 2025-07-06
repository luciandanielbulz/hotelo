<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();

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
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:200'],
            'permissions' => ['array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        // Rolle erstellen
        $role = Role::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
        ]);

        // Berechtigungen zuweisen
        if (!empty($validatedData['permissions'])) {
            $role->permissions()->attach($validatedData['permissions']);
        }

        // Erfolgsnachricht und Weiterleitung
        return redirect()->route('roles.index')->with('message', 'Rolle erfolgreich erstellt!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($role_id)
    {
        $role = Role::find($role_id);

        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        try {
            // Validierung
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:200'],
                'description' => ['nullable', 'string', 'max:200'],
                'permissions' => ['array'],
                'permissions.*' => ['integer', 'exists:permissions,id'],
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        // Rolle aktualisieren
        $role->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
        ]);

        // Berechtigungen synchronisieren
        $permissions = $validatedData['permissions'] ?? [];
        $role->permissions()->sync($permissions);

        return redirect()->route('roles.index')->with('message', 'Rolle wurde erfolgreich aktualisiert');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
