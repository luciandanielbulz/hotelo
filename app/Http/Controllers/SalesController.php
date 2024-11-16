<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use App\Models\Invoice;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentClientId = auth()->user()->client_id; // Ersetzen mit deinem tatsächlichen Zugang zur Client-ID

        $salespositions = Invoice::join('invoicepositions', 'invoices.Id', '=', 'invoicepositions.invoice_id')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->where('client_id', '=', $currentClientId)
            ->where('invoicepositions.issoftdeleted', '=', 0)
            ->selectRaw('YEAR(invoices.date) AS Jahr')
            ->selectRaw('SUM(invoicepositions.price * invoicepositions.amount) AS Umsatz')
            ->selectRaw('SUM(invoices.depositAmount) AS Deposit')
            ->selectRaw('(SUM(invoicepositions.price * invoicepositions.amount) - SUM(invoicepositions.amount)) AS SumExit')
            ->groupByRaw('YEAR(invoices.date)')
            ->orderByRaw('YEAR(invoices.date)')
            ->get();
        //dd($salespositions);
        return view('sales.index', compact('salespositions'));
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
    public function show(Sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sales $sales)
    {
        //
    }
}
