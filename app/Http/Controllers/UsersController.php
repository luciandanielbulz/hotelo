<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Clients;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::join('clients','users.client_id','=','clients.id')
            ->join('roles','users.role_id','=','roles.id')
            ->whereNull('clients.parent_client_id') // Nur ursprüngliche Clients, keine Versionen
            ->select('users.id as user_id','users.name as user_name', 'users.*','clients.*', 'roles.*', 'roles.name as role_name', 'users.email as user_email' )
            ->orderBy('users.id', 'asc')
            ->get();
        return view('users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $clients = Clients::whereNull('parent_client_id')->get(); // Nur ursprüngliche Clients, keine Versionen

        //dd($clients);

        return view('users.create', compact('roles', 'clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|email|max:255|unique:users,email',
                'client_id' => 'required|exists:clients,id',
                'isactive' => 'required|boolean',
                'role_id' => 'required|exists:roles,id',
                'password' => 'required|string|min:8',
            ]);

            // Hier weiterarbeiten, wenn alles validiert wurde
            $validated['password'] = bcrypt($validated['password']);
            User::create($validated);

            return redirect()->route('users.index')->with('success', 'Benutzer erfolgreich erstellt!');
        } catch (ValidationException $e) {
            // Validierungsfehler auffangen und zurückgeben
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $clients = Clients::whereNull('parent_client_id')->get(); // Nur ursprüngliche Clients, keine Versionen
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'login' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'client_id' => 'required|exists:clients,id',
            'isactive' => 'required|boolean',
            'role_id' => 'required|exists:roles,id',
        ]);

        //dd($validated);
        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User erfolgreich aktualisiert!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function resetUserPassword(Request $request, $userId)
{

    // Prüfe, ob der aktuelle Benutzer die Berechtigung hat
    if (!Auth::user()->hasPermission('reset_user_password')) {
        abort(403, 'Zugriff verweigert. Sie haben keine Berechtigung, Passwörter zurückzusetzen.');
    }



    // Validiere das neue Passwort
    $validated = $request->validate([
        'password' => 'required|min:8|confirmed',
    ]);


    // Finde den Benutzer und setze das neue Passwort
    $user = User::findOrFail($userId);


    $user->password = bcrypt($validated['password']);

    $user->save();

    //dd($userId);

    return redirect()->route('users.index')
    ->with('success', 'Das Passwort wurde erfolgreich zurückgesetzt.');

}
}
