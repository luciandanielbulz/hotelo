<?php

namespace App\Http\Controllers;

use App\Models\Condition;
use App\Models\Clients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $conditions = Condition::get();


        return view('condition.index',compact('conditions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $clients = Clients::all();

        return view('condition.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        //dd($user);
        $clientId = $user->client_id;

        $request->validate([
            'conditionname' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
        ]);

        //dd($clientId);
        Condition::create([
            'conditionname' => $request->conditionname,
            'client_id' => $request->client_id,
        ]);

        return redirect()->route('condition.index')->with('success', 'Bedingung erfolgreich erstellt!');
    }


    /**
     * Display the specified resource.
     */
    public function show(Condition $condition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Condition $condition)
    {

        $clients = Clients::all();

        return view('condition.edit',compact('condition','clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Condition $condition)
    {
        $request->validate([
            'conditionname' => 'required|string|max:255',
        ]);

        //dd($request);

        $dbwirte = $condition->update([
            'conditionname' => $request->conditionname,
        ]);

        //dd($dbwirte);

        return redirect()->route('condition.index')->with('success', 'Bedingung erfolgreich aktualisiert!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Condition $condition)
    {
        try {
            $condition->delete();

            // Optional: Logge die Löschaktion
            Log::info('Condition gelöscht', ['condition_id' => $condition->id, 'deleted_by' => Auth::id()]);

            return redirect()->route('condition.index')->with('success', 'Bedingung erfolgreich gelöscht!');
        } catch (\Exception $e) {
            // Fehlerbehandlung
            Log::error('Fehler beim Löschen der Condition', ['error' => $e->getMessage(), 'condition_id' => $condition->id]);

            return redirect()->route('condition.index')->with('error', 'Fehler beim Löschen der Bedingung.');
        }
    }
}
