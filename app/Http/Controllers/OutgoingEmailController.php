<?php

namespace App\Http\Controllers;

use App\Models\OutgoingEmail;
use Illuminate\Http\Request;

class OutgoingEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $outgoingEmails = OutgoingEmail::join('customers','customers.id','=','outgoingemails.customer_id')
            ->select('customers.*','outgoingemails.*')
            ->get();
        //dd($outgoingEmails);
        return view('outgoingemails.index',compact('outgoingEmails'));

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
