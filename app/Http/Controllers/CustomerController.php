<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Salutations;
use App\Models\Taxrates;
use App\Models\Conditions;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        $search = $request->input('search');

        // Suche oder alle Kunden abfragen
        $customers = Customer::where('client_id',1)// auth()->user()->client_id)
            ->when($search, function($query, $search) {
                return $query->where('customername', 'like', "%$search%");
            })
            ->get();

        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $conditions = Conditions::all();
        $salutations = Salutations::all(); // Alle Anreden aus der DB abrufen
        return view('customer.create', compact('salutations', 'conditions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        // Validierung der Formulardaten
        $validatedData = $request->validate([

            'title' => ['nullable', 'string', 'max:50'],
            'customername' => ['nullable', 'string', 'max:200'],
            'companyname' => ['nullable', 'string', 'max:200'],
            'address' => ['nullable', 'string', 'max:200'],
            'postalcode' => ['nullable', 'integer'],
            'location' => ['nullable', 'string', 'max:200'],
            'country' => ['nullable', 'string', 'max:200'],
            'tax_id' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'fax' => ['nullable', 'string', 'max:200'],
            'email' => ['nullable', 'string', 'max:200'],
            'condition_id' => ['required', 'integer'],
            'salutation_id' => ['required', 'integer'],
            'emailsubject' => ['nullable', 'string', 'max:200'],
            'emailbody' => ['nullable', 'string', 'max:1000']
        ]);
        //dd($validatedData);


        // Daten speichern
        Customer::create($validatedData);

        // Erfolgsnachricht und Weiterleitung
        return redirect()->route('customer.index')->with('message', 'Daten erfolgreich gespeichert!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $conditions = Conditions::all();
        $salutations = Salutations::all();
        $taxrates = Taxrates::all();
        //dd($taxrates);
        return view('customer.edit', compact('customer', 'conditions', 'salutations','taxrates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //dd($request);
        try {
            // Hier wird die Validierung durchgeführt
            $validatedData = $request->validate([
                'customername' => ['nullable', 'string', 'max:200'],
                'title' => ['nullable', 'string', 'max:50'],
                'companyname' => ['nullable', 'string', 'max:200'],
                'address' => ['nullable', 'string', 'max:200'],
                'postalcode' => ['nullable', 'integer'],
                'location' => ['nullable', 'string', 'max:200'],
                'country' => ['nullable', 'string', 'max:200'],
                'tax_id' => ['nullable', 'string', 'max:50'],
                'phone' => ['nullable', 'string', 'max:50'],
                'fax' => ['nullable', 'string', 'max:200'],
                'email' => ['nullable', 'string', 'max:200'],
                'condition_id' => ['required', 'integer'],
                'salutation_id' => ['required', 'integer'],
                'emailsubject' => ['nullable', 'string', 'max:200'],
                'emailbody' => ['nullable', 'string', 'max:1000']
            ]);

            // Wenn die Validierung erfolgreich ist, fahre hier fort
            // Speichere die Daten oder führe deine gewünschte Aktion aus

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Hier kannst du Fehler behandeln, wenn die Validierung fehlschlägt
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
        //dd($validatedData);

        $customer->update($validatedData);

        return to_route('customer.index')->with('message', 'Kunde wurde geändert');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return to_route('customer.index')->with('message', 'Kunde wurde gelöscht');

    }
}
