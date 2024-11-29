<?php

namespace App\Http\Controllers;

use App\Models\OutgoingEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutgoingEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //dd($request);

        $user = Auth::user();
        $clientId = $user->client_id;
        // Suchbegriff
        $search = $request->input('search');

        // Abfrage mit Suche und Pagination
        $outgoingEmails = OutgoingEmail::join('customers', 'customers.id', '=', 'outgoingemails.customer_id')
            ->select('customers.*', 'outgoingemails.*')
            ->when($search, function ($query, $search) {
                $query->where('customers.customername', 'like', "%$search%") // Beispiel: Suche nach Kundenname
                    ->orWhere('outgoingemails.objectnumber', 'like', "%$search%"); // Beispiel: Suche nach Betreff
            })
            ->where('outgoingemails.client_id','=',$clientId)
            ->orderBy('outgoingemails.sentdate', 'desc')
            ->paginate(18); // 18 Items pro Seite

        return view('outgoingemails.index', compact('outgoingEmails', 'search'));
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
    public function show(OutgoingEmail $outgoingEmail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OutgoingEmail $outgoingEmail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OutgoingEmail $outgoingEmail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OutgoingEmail $outgoingEmail)
    {
        //
    }
}
