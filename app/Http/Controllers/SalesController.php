<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\BankData;
use App\Models\Category;

use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentClientId = auth()->user()->client_id;
        $selectedYear = $request->get('year', null);

        // Umsätze
        $salespositions = Invoice::join('invoicepositions', 'invoices.Id', '=', 'invoicepositions.invoice_id')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->where('customers.client_id', '=', $currentClientId)
            ->where('invoicepositions.issoftdeleted', '=', 0);
        
        if ($selectedYear) {
            $salespositions = $salespositions->whereRaw('YEAR(invoices.date) = ?', [$selectedYear]);
        }
        
        $salespositions = $salespositions->selectRaw('YEAR(invoices.date) AS Jahr')
            ->addSelect(DB::raw('SUM(invoicepositions.price * invoicepositions.amount) AS Umsatz'))
            ->groupByRaw('YEAR(invoices.date)')
            ->orderByRaw('YEAR(invoices.date)')
            ->get();

        // Kategorisierte Einnahmen
        $categorizedIncomeQuery = DB::table('bankdata')
            ->join('categories', 'bankdata.category_id', '=', 'categories.id')
            ->selectRaw('
                YEAR(bankdata.date) AS Jahr,
                SUM(
                    CASE 
                        WHEN categories.billing_duration_years > 0 THEN 
                            ABS(bankdata.amount) * (categories.percentage / 100) / categories.billing_duration_years
                        ELSE 
                            ABS(bankdata.amount) * (categories.percentage / 100)
                    END
                ) AS Einnahmen
            ')
            ->where('bankdata.client_id', '=', $currentClientId)
            ->where('bankdata.type', '=', 'income')
            ->where('categories.is_active', '=', 1);
        
        if ($selectedYear) {
            $categorizedIncomeQuery = $categorizedIncomeQuery->whereRaw('YEAR(bankdata.date) = ?', [$selectedYear]);
        }
        
        $categorizedIncome = collect($categorizedIncomeQuery->groupByRaw('YEAR(bankdata.date)')
            ->orderByRaw('YEAR(bankdata.date)')
            ->get());

        // Ausgaben mit Kategorie-Berücksichtigung und Verrechnungsdauer
        $expensesQuery = DB::table('bankdata')
            ->join('categories', 'bankdata.category_id', '=', 'categories.id')
            ->selectRaw('
                YEAR(bankdata.date) AS Jahr,
                SUM(
                    CASE 
                        WHEN categories.billing_duration_years > 0 THEN 
                            ABS(bankdata.amount) * (categories.percentage / 100) / categories.billing_duration_years
                        ELSE 
                            ABS(bankdata.amount) * (categories.percentage / 100)
                    END
                ) AS Ausgaben
            ')
            ->where('bankdata.client_id', '=', $currentClientId)
            ->where('bankdata.type', '=', 'expense')
            ->where('categories.is_active', '=', 1);
        
        if ($selectedYear) {
            $expensesQuery = $expensesQuery->whereRaw('YEAR(bankdata.date) = ?', [$selectedYear]);
        }
        
        $expenses = collect($expensesQuery->groupByRaw('YEAR(bankdata.date)')
            ->orderByRaw('YEAR(bankdata.date)')
            ->get());

        // Ausgaben ohne Kategorie (Fallback für nicht kategorisierte Ausgaben)
        $uncategorizedExpensesQuery = DB::table('bankdata')
            ->leftJoin('categories', 'bankdata.category_id', '=', 'categories.id')
            ->selectRaw('YEAR(bankdata.date) AS Jahr, ABS(SUM(bankdata.amount)) AS Ausgaben')
            ->where('bankdata.client_id', '=', $currentClientId)
            ->where('bankdata.type', '=', 'expense')
            ->whereNull('categories.id');
        
        if ($selectedYear) {
            $uncategorizedExpensesQuery = $uncategorizedExpensesQuery->whereRaw('YEAR(bankdata.date) = ?', [$selectedYear]);
        }
        
        $uncategorizedExpenses = collect($uncategorizedExpensesQuery->groupByRaw('YEAR(bankdata.date)')
            ->orderByRaw('YEAR(bankdata.date)')
            ->get());

        // Einnahmen ohne Kategorie (Fallback für nicht kategorisierte Einnahmen)
        $uncategorizedIncomeQuery = DB::table('bankdata')
            ->leftJoin('categories', 'bankdata.category_id', '=', 'categories.id')
            ->selectRaw('YEAR(bankdata.date) AS Jahr, ABS(SUM(bankdata.amount)) AS Einnahmen')
            ->where('bankdata.client_id', '=', $currentClientId)
            ->where('bankdata.type', '=', 'income')
            ->whereNull('categories.id');
        
        if ($selectedYear) {
            $uncategorizedIncomeQuery = $uncategorizedIncomeQuery->whereRaw('YEAR(bankdata.date) = ?', [$selectedYear]);
        }
        
        $uncategorizedIncome = collect($uncategorizedIncomeQuery->groupByRaw('YEAR(bankdata.date)')
            ->orderByRaw('YEAR(bankdata.date)')
            ->get());

        // Detaillierte Aufschlüsselung nach Kategorien
        $categoryBreakdownQuery = DB::table('bankdata')
            ->join('categories', 'bankdata.category_id', '=', 'categories.id')
            ->selectRaw('
                YEAR(bankdata.date) AS Jahr,
                categories.name AS Kategorie,
                categories.percentage AS Prozentsatz,
                categories.billing_duration_years AS Verrechnungsdauer,
                categories.type AS Typ,
                bankdata.type AS Transaktionstyp,
                SUM(
                    CASE 
                        WHEN categories.billing_duration_years > 0 THEN 
                            ABS(bankdata.amount) * (categories.percentage / 100) / categories.billing_duration_years
                        ELSE 
                            ABS(bankdata.amount) * (categories.percentage / 100)
                    END
                ) AS Betrag
            ')
            ->where('bankdata.client_id', '=', $currentClientId)
            ->where('categories.is_active', '=', 1);
        
        if ($selectedYear) {
            $categoryBreakdownQuery = $categoryBreakdownQuery->whereRaw('YEAR(bankdata.date) = ?', [$selectedYear]);
        }
        
        $categoryBreakdown = collect($categoryBreakdownQuery->groupByRaw('YEAR(bankdata.date), categories.name, categories.percentage, categories.billing_duration_years, categories.type')
            ->orderByRaw('YEAR(bankdata.date) DESC, Betrag DESC')
            ->get());

        // Verfügbare Jahre für den Filter
        $availableYears = collect(DB::table('bankdata')
            ->where('client_id', '=', $currentClientId)
            ->selectRaw('DISTINCT YEAR(date) as year')
            ->orderBy('year', 'desc')
            ->pluck('year'));

        // Umsätze und Ausgaben zusammenführen
        $salespositions = $salespositions->map(function ($salesposition) use ($expenses, $uncategorizedExpenses, $categorizedIncome, $uncategorizedIncome) {
            $categorizedExpense = $expenses->firstWhere('Jahr', $salesposition->Jahr);
            $uncategorizedExpense = $uncategorizedExpenses->firstWhere('Jahr', $salesposition->Jahr);
            $categorizedIncomeItem = $categorizedIncome->firstWhere('Jahr', $salesposition->Jahr);
            $uncategorizedIncomeItem = $uncategorizedIncome->firstWhere('Jahr', $salesposition->Jahr);
            
            $totalExpenses = ($categorizedExpense->Ausgaben ?? 0) + ($uncategorizedExpense->Ausgaben ?? 0);
            $totalIncome = ($categorizedIncomeItem->Einnahmen ?? 0) + ($uncategorizedIncomeItem->Einnahmen ?? 0);
            
            $salesposition->Ausgaben = $totalExpenses;
            $salesposition->Einnahmen_Kategorisiert = $categorizedIncomeItem->Einnahmen ?? 0;
            $salesposition->Ausgaben_Kategorisiert = $categorizedExpense->Ausgaben ?? 0;
            $salesposition->Gewinn_Kategorisiert = ($categorizedIncomeItem->Einnahmen ?? 0) - ($categorizedExpense->Ausgaben ?? 0);
            
            return $salesposition;
        });

        // Chart-Daten vorbereiten
        $chartData = [
            'labels' => $salespositions->map(fn($pos) => $pos->Jahr . '-' . str_pad($pos->Monat ?? 1, 2, '0', STR_PAD_LEFT)),
            'revenue' => $salespositions->map(fn($pos) => $pos->Umsatz),
            'income_categorized' => $salespositions->map(fn($pos) => $pos->Einnahmen_Kategorisiert),
            'expenses_categorized' => $salespositions->map(fn($pos) => $pos->Ausgaben_Kategorisiert),
            'profit_categorized' => $salespositions->map(fn($pos) => $pos->Gewinn_Kategorisiert),
        ];

        // Debug-Informationen
        \Log::info('Sales Analysis Debug', [
            'categorized_income' => $categorizedIncome->toArray(),
            'uncategorized_income' => $uncategorizedIncome->toArray(),
            'categorized_expenses' => $expenses->toArray(),
            'uncategorized_expenses' => $uncategorizedExpenses->toArray(),
            'category_breakdown' => $categoryBreakdown->take(10)->toArray(),
            'selected_year' => $selectedYear,
            'client_id' => $currentClientId
        ]);

        return view('sales.index', compact('salespositions', 'expenses', 'uncategorizedExpenses', 'categorizedIncome', 'uncategorizedIncome', 'categoryBreakdown', 'chartData', 'availableYears', 'selectedYear'));
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
