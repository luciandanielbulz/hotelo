<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Salutations;
use App\Models\Taxrates;
use App\Models\Condition;
use App\Models\Clients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        //dd($user);
        $clientId = $user->client_id;

        $search = $request->input('search');

        // Suche oder alle Kunden abfragen
        $customers = Customer::where('client_id', $clientId)
            ->when($search, function($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('customername', 'like', "%$search%")
                      ->orWhere('companyname', 'like', "%$search%")
                      ->orWhere('customer_number', 'like', "%$search%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        //dd($user);
        $clientId = $user->client_id;

        $conditions = Condition::all();
        $taxrates = Taxrates::all();

        $standardtaxrate = Clients::where('id', $clientId)->first('tax_id')->tax_id;
        
        //dd($standardtaxrate);

        $salutations = Salutations::all(); // Alle Anreden aus der DB abrufen
        return view('customer.create', compact('salutations', 'conditions', 'taxrates', 'standardtaxrate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $user = Auth::user();
        $clientId = $user->client_id;

        //dd($clientId);
        // Validierung der Formulardaten
        $validatedData = $request->validate([
            'title' => ['nullable', 'string', 'max:50'],
            'customername' => ['nullable', 'string', 'max:200', 'required_without:companyname'],
            'companyname' => ['nullable', 'string', 'max:200', 'required_without:customername'],
            'customer_number' => ['nullable', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:200'],
            'postalcode' => ['required', 'integer'],
            'location' => ['required', 'string', 'max:200'],
            'country' => ['required', 'string', 'max:200'],
            'tax_id' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'fax' => ['nullable', 'string', 'max:200'],
            'email' => ['nullable', 'string', 'max:200'],
            'vat_number' => ['nullable', 'string', 'max:100'],
            'condition_id' => ['required', 'integer'],
            'salutation_id' => ['required', 'integer'],
            'emailsubject' => ['nullable', 'string', 'max:200'],
            'emailbody' => ['nullable', 'string', 'max:1000']
        ]);

        $validatedData['client_id'] = $clientId;
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
        // Überprüfen, ob der Kunde zum aktuellen Client gehört
        $currentClientId = auth()->user()->client_id; // Angenommen, der eingeloggte Benutzer hat eine `client_id`

        if ($customer->client_id !== $currentClientId) {
            abort(403, 'Sie sind nicht berechtigt, diesen Kunden zu bearbeiten.');
        }
        $conditions = Condition::all();
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
                'title' => ['nullable', 'string', 'max:50'],
                'customername' => ['nullable', 'string', 'max:200', 'required_without:companyname'],
                'companyname' => ['nullable', 'string', 'max:200', 'required_without:customername'],
                'customer_number' => ['nullable', 'string', 'max:50'],
                'address' => ['required', 'string', 'max:200'],
                'postalcode' => ['required', 'string', 'max:10'],
                'location' => ['required', 'string', 'max:200'],
                'country' => ['required', 'string', 'max:200'],
                'tax_id' => ['required', 'string', 'max:100'],
                'phone' => ['nullable', 'string', 'max:30'],
                'fax' => ['nullable', 'string', 'max:200'],
                'email' => ['nullable', 'string', 'max:200'],
                'vat_number' => ['nullable', 'string', 'max:100'],
                'condition_id' => ['required', 'integer'],
                'salutation_id' => ['required', 'integer'],
                'emailsubject' => ['nullable', 'string', 'max:300'],
                'emailbody' => ['nullable', 'string', 'max:10000']
            ]);
        //dd($validatedData);
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
