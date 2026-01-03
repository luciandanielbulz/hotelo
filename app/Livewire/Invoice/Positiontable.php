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

    public $perPage = 12;
    public $search = '';
    public $viewMode = 'table'; // 'cards' oder 'table' - Standard: table für Desktop
    public $sortBy = 'newest'; // 'newest', 'oldest', 'number', 'customer'
    public $statusFilter = 'all'; // all, draft, open, sent, partial, paid, cancelled
    public $sortField = 'number'; // 'number', 'customer', 'amount'
    public $sortDirection = 'asc'; // 'asc' oder 'desc'

    public function boot()
    {
        Paginator::useTailwind(); // Oder ->useBootstrap(), je nach verwendetem CSS-Framework
    }

    public function mount()
    {
        // Standardmäßig nach Nummer absteigend sortieren
        $this->sortField = 'number';
        $this->sortDirection = 'desc';
        
        // Prüfe Query-Parameter 'view' (wird von Navbar oder Sidebar gesetzt)
        $viewParam = request()->query('view');
        
        if ($viewParam === 'cards') {
            // Aufruf über Navbar (Tablet/Mobile) → Kartenansicht
            $this->viewMode = 'cards';
        } elseif ($viewParam === 'table') {
            // Aufruf über Sidebar (Desktop) → Tabellenansicht
            $this->viewMode = 'table';
        } else {
            // Kein Query-Parameter: Standard basierend auf Bildschirmbreite
            $userAgent = request()->userAgent();
            $isMobile = preg_match('/(iPhone|Android|Mobile|webOS|BlackBerry|IEMobile|Opera Mini)/i', $userAgent);
            $isTablet = preg_match('/(iPad|Android|Tablet|PlayBook|Silk)/i', $userAgent);
            
            // Prüfe Session für Bildschirmbreite (wird per JavaScript gesetzt)
            $screenWidth = session('screen_width');
            
            // Standard: Tabellenansicht (Desktop)
            $this->viewMode = 'table';
            
            // Nur wenn Tablet/Mobile erkannt, auf Kartenansicht wechseln
            if ($isMobile || $isTablet) {
                $this->viewMode = 'cards';
            }
            
            // Wenn Session-Breite vorhanden, diese als Priorität verwenden
            if ($screenWidth) {
                if ($screenWidth >= 1024) {
                    $this->viewMode = 'table'; // Desktop
                } elseif ($screenWidth >= 768) {
                    $this->viewMode = 'cards'; // Tablet
                } else {
                    $this->viewMode = 'cards'; // Mobile
                }
            }
        }
    }
    
    public function sortByColumn($field)
    {
        if ($this->sortField === $field) {
            // Wenn bereits nach diesem Feld sortiert, Richtung umkehren
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Neues Feld, standardmäßig aufsteigend sortieren
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }
    
    public function setScreenWidth($width)
    {
        // Setze Bildschirmbreite in Session und passe View-Mode an
        session(['screen_width' => $width]);
        
        // Desktop (lg >= 1024px): IMMER Tabellenansicht
        if ($width >= 1024) {
            $this->viewMode = 'table';
        } 
        // Tablet-Breite (768px - 1024px): Kartenansicht
        elseif ($width >= 768 && $width < 1024) {
            $this->viewMode = 'cards';
        } 
        // Mobile (< 768px): Kartenansicht
        else {
            $this->viewMode = 'cards';
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

    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
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

    public function archiveInvoice($invoiceId)
    {
        \Log::info("archiveInvoice aufgerufen mit ID: " . $invoiceId);

        $invoice = Invoices::find($invoiceId);

        if ($invoice) {
            $invoice->status = 7; // Archiviert
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
            // Standard: Archivierte (Status 7) im Index ausblenden
            ->where('invoices.status', '!=', 7)
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('customers.customername', 'like', "%$search%")
                        ->orWhere('customers.companyname', 'like', "%$search%")
                        ->orWhere('invoices.number', 'like', "%$search%");
                });
            })
            ->when($this->statusFilter !== 'all' && $this->statusFilter !== 'archived', function ($query) {
                $map = [
                    'draft' => 0,
                    'open' => 1,
                    'sent' => 2,
                    'partial' => 3,
                    'paid' => 4,
                    'cancelled' => 6,
                ];
                if (array_key_exists($this->statusFilter, $map)) {
                    $query->where('invoices.status', $map[$this->statusFilter]);
                }
            });

        $query->select(
                'invoices.id as invoice_id',
                'invoices.number',
                'invoices.status',
                'invoices.created_at',
                'invoices.updated_at',
                'invoices.date',
                'invoices.description',
                'customers.customername',
                'customers.companyname',
                DB::raw('latest_emails.latest_sentdate as sent_date'),
                DB::raw('COALESCE(SUM(invoicepositions.amount * invoicepositions.price), 0) as total_price')
            )
            ->groupBy(
                'invoices.id',
                'invoices.number',
                'invoices.status',
                'invoices.created_at',
                'invoices.updated_at',
                'invoices.date',
                'invoices.description',
                'customers.customername',
                'customers.companyname',
                'latest_emails.latest_sentdate'
            );

        // Sortierung anwenden - zuerst nach Spalten-Sortierung, dann nach alter Sortierung
        if ($this->viewMode === 'table') {
            // In Tabellenansicht: Spalten-Sortierung verwenden
            switch ($this->sortField) {
                case 'number':
                    $query->orderBy('invoices.number', $this->sortDirection);
                    break;
                case 'customer':
                    $query->orderBy('customers.customername', $this->sortDirection)
                          ->orderBy('customers.companyname', $this->sortDirection);
                    break;
                case 'amount':
                    // Für aggregierte Felder muss die Sortierung nach GROUP BY erfolgen
                    $query->orderBy(DB::raw('COALESCE(SUM(invoicepositions.amount * invoicepositions.price), 0)'), $this->sortDirection);
                    break;
                default:
                    // Fallback: Nach Nummer sortieren
                    $query->orderBy('invoices.number', 'asc');
                    break;
            }
        } else {
            // In Kartenansicht: Alte Sortierung verwenden
            switch ($this->sortBy) {
                case 'oldest':
                    $query->orderBy('invoices.date', 'asc')
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
                    $query->orderBy('invoices.date', 'desc')
                          ->orderBy('invoices.number', 'desc');
                    break;
            }
        }

        $invoices = $query->paginate($this->perPage);
        $invoices->appends(['search' => $search]);

        return view('livewire.invoice.positiontable', [
            'invoices' => $invoices,
        ]);
    }
}
