<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(RolePermissions $permissions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($role_id)
    {
        //dd($role_id);
        $role = Role::find($role_id);

        $permissions = Permission::join('role_permission', 'role_permission.permission_id', '=', 'permissions.id')
            ->join('roles', 'roles.id', '=', 'role_permission.role_id')
            ->where('roles.id','=',$role_id)
            ->select('permissions.*', 'roles.name as role_name') // Beispiel: Nur bestimmte Spalten auswählen
            ->get();

        $raw_permissions = Permission::all();

        //dd($permissions);
        return view('rolepermissions.edit', compact('permissions', 'raw_permissions', 'role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $roleId)
    {
        // Validierung der Anfrage
        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        // Prüfen, ob eine Rollen-ID übergeben wurde
        if (!$roleId) {
            return redirect()->back()->withErrors(['msg' => 'Rollen-ID nicht definiert']);
        }

        // Rolle aus der Datenbank abrufen
        $role = Role::find($roleId);
        if (!$role) {
            return redirect()->back()->withErrors(['msg' => 'Rolle nicht gefunden']);
        }

        // Die gewünschten Berechtigungen aus der Anfrage
        $newPermissions = $validated['permissions'] ?? [];

        // Aktuelle Berechtigungen der Rolle aus der DB
        $currentPermissions = $role->permissions()->pluck('permissions.id')->toArray();

        // Berechnen, welche Berechtigungen hinzugefügt und entfernt werden müssen
        $permissionsToAdd = array_diff($newPermissions, $currentPermissions);
        $permissionsToRemove = array_diff($currentPermissions, $newPermissions);

        // Hinzufügen und Entfernen der Berechtigungen
        if (!empty($permissionsToAdd)) {
            $role->permissions()->attach($permissionsToAdd);
        }

        if (!empty($permissionsToRemove)) {
            $role->permissions()->detach($permissionsToRemove);
        }

        // Debugging: Abfragen anzeigen
        //DB::enableQueryLog();
        //dd(DB::getQueryLog());

        // Erfolgsmeldung und Weiterleitung
        return redirect()->route('roles.index')->with('success', 'Berechtigungen erfolgreich aktualisiert!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permissions)
    {
        //
    }
}
