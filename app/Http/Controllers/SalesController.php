<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\BankData;

use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentClientId = auth()->user()->client_id;

        // Umsätze
        $salespositions = Invoice::join('invoicepositions', 'invoices.Id', '=', 'invoicepositions.invoice_id')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->where('customers.client_id', '=', $currentClientId)
            ->where('invoicepositions.issoftdeleted', '=', 0)
            ->selectRaw('YEAR(invoices.date) AS Jahr') //MONTH(invoices.date) AS Monat
            ->addSelect(DB::raw('SUM(invoicepositions.price * invoicepositions.amount) AS Umsatz'))
            ->groupByRaw('YEAR(invoices.date)') //, MONTH(invoices.date)
            ->orderByRaw('YEAR(invoices.date)') //, MONTH(invoices.date)
            ->get();

        // Ausgaben in eine Collection laden
        $expenses = collect(DB::table('bankdata')
            ->selectRaw('YEAR(date) AS Jahr, ABS(SUM(amount)) AS Ausgaben')
            ->where('client_id', '=', $currentClientId)
            ->where('amount', '<', 0)
            ->groupByRaw('YEAR(date)')
            ->orderByRaw('YEAR(date)') //, MONTH(date)
            ->get());

        //dd($salespositions);
        // Umsätze und Ausgaben zusammenführen
        $salespositions = $salespositions->map(function ($salesposition) use ($expenses) {
            $expense = $expenses->firstWhere('Jahr', $salesposition->Jahr);
                                //->firstWhere('Monat', $salesposition->Monat);
            $salesposition->Ausgaben = $expense->Ausgaben ?? 0; // Füge die Ausgaben hinzu
            return $salesposition;
        });

        // Chart-Daten vorbereiten
        $chartData = [
            'labels' => $salespositions->map(fn($pos) => $pos->Jahr . '-' . str_pad($pos->Monat, 2, '0', STR_PAD_LEFT)),
            'revenue' => $salespositions->map(fn($pos) => $pos->Umsatz),
            'expenses' => $salespositions->map(function ($pos) use ($expenses) {
                $expense = $expenses->firstWhere('Jahr', $pos->Jahr);
                                    //->firstWhere('Monat', $pos->Monat);
                return $expense->Ausgaben ?? 0;
            }),
        ];


        return view('sales.index', compact('salespositions', 'expenses', 'chartData'));
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
