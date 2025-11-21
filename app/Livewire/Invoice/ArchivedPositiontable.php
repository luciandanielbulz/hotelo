<?php

namespace App\Livewire\Invoice;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Invoices;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class ArchivedPositiontable extends Component
{
    use WithPagination;

    public $perPage = 12;
    public $search = '';
    public $viewMode = 'table'; // 'cards' oder 'table' - Standard: table für desktop
    public $sortBy = 'newest'; // 'newest', 'oldest', 'number', 'customer'

    public function boot()
    {
        Paginator::useTailwind();
    }

    public function mount()
    {
        // Prüfe Query-Parameter 'view' (wird von Navbar oder Sidebar gesetzt)
        $viewParam = request()->query('view');
        
        if ($viewParam === 'cards') {
            // Aufruf über Navbar (Tablet/Mobile) → Kartenansicht
            $this->viewMode = 'cards';
        } elseif ($viewParam === 'table') {
            // Aufruf über Sidebar (Desktop) → Tabellenansicht
            $this->viewMode = 'table';
        } else {
            // Kein Query-Parameter: Standard basierend auf User-Agent
            $userAgent = request()->userAgent();
            $isMobile = preg_match('/(iPhone|Android|Mobile|webOS|BlackBerry|IEMobile|Opera Mini)/i', $userAgent);
            $isTablet = preg_match('/(iPad|Android|Tablet|PlayBook|Silk)/i', $userAgent);
            
            // Standard: Tabellenansicht (Desktop)
            $this->viewMode = 'table';
            
            // Nur wenn Tablet/Mobile erkannt, auf Kartenansicht wechseln
            if ($isMobile || $isTablet) {
                $this->viewMode = 'cards';
            }
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function setViewMode($mode)
    {
        // Auf mobilen Geräten und Tablets ViewMode nicht ändern - immer cards
        $userAgent = request()->userAgent();
        $isMobile = preg_match('/(iPhone|Android|Mobile|webOS|BlackBerry|IEMobile|Opera Mini)/i', $userAgent);
        $isTablet = preg_match('/(iPad|Android|Tablet|PlayBook|Silk)/i', $userAgent);
        $screenWidth = session('screen_width');
        $isTabletByWidth = $screenWidth && $screenWidth >= 768 && $screenWidth < 1024;
        
        // Nur auf Desktop (>= 1024px) ViewMode ändern lassen
        if (!$isMobile && !$isTablet && !$isTabletByWidth) {
            $this->viewMode = $mode;
        }
    }

    /**
     * Stellt eine archivierte Rechnung wieder her.
     *
     * @param int $invoiceId
     * @return void
     */
    public function unarchiveInvoice($invoiceId)
    {
        \Log::info("unarchiveInvoice aufgerufen mit ID: " . $invoiceId);

        $invoice = Invoices::find($invoiceId);

        if ($invoice) {
            $invoice->status = 0; // Entwurf (oder gewünschter Zielstatus)
            $invoice->save();

            session()->flash('message', 'Rechnung erfolgreich wiederhergestellt.');
        } else {
            session()->flash('error', 'Rechnung nicht gefunden.');
        }
    }

    /**
     * Render-Methode der Komponente für archivierte Rechnungen.
     *
     * @return \Illuminate\View\View
     */
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
            ->where('invoices.status', 7) // NUR Status 7 (Archiviert)
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('customers.customername', 'like', "%$search%")
                        ->orWhere('customers.companyname', 'like', "%$search%")
                        ->orWhere('invoices.number', 'like', "%$search%");
                });
            });

        // Sortierung anwenden
        switch ($this->sortBy) {
            case 'oldest':
                $query->orderBy('invoices.updated_at', 'asc')
                      ->orderBy('invoices.number', 'asc');
                break;
            case 'number':
                $query->orderBy('invoices.number', 'asc');
                break;
            case 'customer':
                $query->orderBy('customers.customername', 'asc')
                      ->orderBy('customers.companyname', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('invoices.updated_at', 'desc')
                      ->orderBy('invoices.number', 'desc');
                break;
        }

        $query->select(
                'invoices.id as invoice_id',
                'invoices.number',
                'invoices.status',
                DB::raw('invoices.updated_at as archiveddate'),
                'invoices.created_at',
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
                'invoices.status',
                'invoices.updated_at',
                'invoices.created_at',
                'invoices.date',
                'invoices.description',
                'customers.customername',
                'customers.companyname',
                'latest_emails.latest_sentdate'
            );

        $invoices = $query->paginate($this->perPage);
        $invoices->appends(['search' => $search]);

        return view('livewire.invoice.archived-positiontable', [
            'invoices' => $invoices,
        ]);
    }
} 