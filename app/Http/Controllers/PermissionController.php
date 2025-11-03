<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::orderBy('category')->orderBy('name')->get();
        
        // Gruppiere Berechtigungen nach Kategorien
        $groupedPermissions = $permissions->groupBy('category');
        
        // Berechtigungen ohne Kategorie in "Sonstige" gruppieren
        if ($groupedPermissions->has('')) {
            $groupedPermissions['Sonstige'] = $groupedPermissions->get('');
            $groupedPermissions->forget('');
        }
        
        // Sortiere die Kategorien alphabetisch
        $groupedPermissions = $groupedPermissions->sortKeys();
        
        return view('permissions.index', compact('permissions', 'groupedPermissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Permission::getCategories();
        return view('permissions.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:permissions,name'],
            'description' => ['nullable', 'string', 'max:200'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        Permission::create($validatedData);

        // Erfolgsnachricht und Weiterleitung
        return redirect()->route('permissions.index')->with('message', 'Berechtigung erfolgreich gespeichert!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permissions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($permissions_id)
    {
        //dd($role_id);
        $role = Permission::find($permissions_id);

        $permissions = Permission::where('permissions.id','=',$permissions_id)
            ->first();

        $raw_permissions = Permission::all();
        $categories = Permission::getCategories();

        //dd($permissions);
        return view('permissions.edit', compact('permissions', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        //dd($request);
        try {
            // Hier wird die Validierung durchgeführt
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:200', Rule::unique('permissions', 'name')->ignore($permission->id)],
                'description' => ['nullable', 'string', 'max:200'],
                'category' => ['nullable', 'string', 'max:100'],
            ]);

            // Wenn die Validierung erfolgreich ist, fahre hier fort
            // Speichere die Daten oder führe deine gewünschte Aktion aus

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Hier kannst du Fehler behandeln, wenn die Validierung fehlschlägt
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
        //dd($validatedData);

        $permission->update($validatedData);

        return redirect()->route('permissions.index')->with('message', 'Berechtigung wurde erfolgreich aktualisiert');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        try {
            // Prüfe ob die Berechtigung in Verwendung ist
            $rolesUsingPermission = $permission->roles()->get();
            
            if ($rolesUsingPermission->count() > 0) {
                $roleNames = $rolesUsingPermission->pluck('name')->toArray();
                $roleNamesString = implode(', ', $roleNames);
                
                return redirect()->route('permissions.index')
                    ->with('error', 'Diese Berechtigung kann nicht gelöscht werden, da sie noch von folgenden Rollen verwendet wird: ' . $roleNamesString);
            }

            $permission->delete();

            return redirect()->route('permissions.index')
                ->with('message', 'Berechtigung wurde erfolgreich gelöscht.');

        } catch (\Exception $e) {
            return redirect()->route('permissions.index')
                ->with('error', 'Fehler beim Löschen der Berechtigung: ' . $e->getMessage());
        }
    }
}
