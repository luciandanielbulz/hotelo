<?php

namespace App\Livewire\Invoice;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoices;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class Positiontable extends Component
{
    use WithPagination;
    public $perPage = 9;
    public $search = '';


    public function boot()
    {
        Paginator::useTailwind(); // Oder ->useBootstrap(), je nach verwendetem CSS-Framework
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

     /**
     * Archiviert ein Angebot anhand der offerId.
     *
     * @param int $offerId
     * @return void
     */
    public function archiveInvoice($invoiceId)
    {
        \Log::info("archiveOffer aufgerufen mit ID: " . $invoiceId);

        $invoice = Invoices::find($invoiceId);

        if ($invoice) {
            $invoice->archived = true;
            $invoice->archiveddate = now(); // Optional: Archivierungsdatum setzen
            $invoice->save();

            session()->flash('message', 'Rechnung erfolgreich archiviert.');
            // Keine Notwendigkeit, loadData aufzurufen, da render automatisch aufgerufen wird
        } else {
            session()->flash('error', 'Rechnung nicht gefunden.');
        }
    }
    public function render()
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        $search = $this->search;

        // Aggregation Query zur Berechnung von total_price
        $query = Invoices::join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->leftJoin('invoicepositions', 'invoices.id', '=', 'invoicepositions.invoice_id')
            ->leftJoin('outgoingemails','outgoingemails.objectnumber','=','invoices.number')
            ->where('customers.client_id', $clientId)
            ->where('invoices.archived', false) // Nur nicht archivierte Rechnungen anzeigen
            ->orderBy('invoices.number', 'desc')
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('customers.customername', 'like', "%$search%")
                          ->orWhere('customers.companyname', 'like', "%$search%")
                          ->orWhere('invoices.number', 'like', "%$search%");
                });
            })
            ->select(
                'invoices.id as invoice_id',
                'invoices.number',
                'invoices.archived',
                'invoices.created_at',
                'invoices.updated_at',
                'invoices.date',
                'invoices.description',
                'customers.customername',
                'customers.companyname',
                'outgoingemails.sentdate as sent_date',
                DB::raw('SUM(invoicepositions.amount * invoicepositions.price) as total_price')
            )
            ->groupBy(
                'invoices.id',
                'invoices.number',
                'invoices.archived',
                'invoices.created_at',
                'invoices.updated_at',
                'invoices.date',
                'invoices.description',
                'customers.customername',
                'customers.companyname',
                'outgoingemails.sentdate',
            );

        $invoices = $query->paginate($this->perPage);
        $invoices->appends(['search' => $search]);

        return view('livewire.invoice.positiontable', [
            'invoices' => $invoices,
        ]);
    }
}
