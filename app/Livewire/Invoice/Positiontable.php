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
            ->leftJoin('clients', 'invoices.client_version_id', '=', 'clients.id') // Join für Client-Version
            ->leftJoin(DB::raw('(SELECT objectnumber, MAX(sentdate) as latest_sentdate FROM outgoingemails GROUP BY objectnumber) as latest_emails'), 'latest_emails.objectnumber', '=', 'invoices.number')
            ->where(function($query) use ($clientId) {
                // Zeige Rechnungen an, wenn:
                // 1. Der Customer zu diesem Client gehört (alte Logik)
                // 2. ODER die Rechnung mit einer Client-Version erstellt wurde, die zu diesem Client gehört (neue Logik)
                $query->where('customers.client_id', $clientId)
                      ->orWhere('clients.id', $clientId)
                      ->orWhere('clients.parent_client_id', $clientId);
            })
            ->where('invoices.archived', false) // Nur nicht archivierte Rechnungen anzeigen
            ->orderBy('invoices.id', 'desc')
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
                DB::raw('latest_emails.latest_sentdate as sent_date'),
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
                'latest_emails.latest_sentdate'
            );

        $invoices = $query->paginate($this->perPage);
        $invoices->appends(['search' => $search]);

        return view('livewire.invoice.positiontable', [
            'invoices' => $invoices,
        ]);
    }
}
