<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Pagination\Paginator;

class CustomerTable extends Component
{
    use WithPagination;

    public $perPage = 12;
    public $search = '';
    public $viewMode = 'table'; // 'cards' oder 'table' - Standard: table für desktop
    public $sortBy = 'newest'; // 'newest', 'oldest', 'name', 'company'

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
            // Kein Query-Parameter: Standard basierend auf User-Agent und Bildschirmbreite
            $userAgent = request()->userAgent();
            $isMobile = preg_match('/(iPhone|Android|Mobile|webOS|BlackBerry|IEMobile|Opera Mini)/i', $userAgent);
            $isTablet = preg_match('/(iPad|Android|Tablet|PlayBook|Silk)/i', $userAgent);
            
            // Prüfe Session für Bildschirmbreite (wird per JavaScript gesetzt)
            $screenWidth = session('screen_width');
            
            // Tablet-Erkennung: User-Agent ODER Bildschirmbreite zwischen 768px und 1024px (md bis lg)
            $isTabletByWidth = $screenWidth && $screenWidth >= 768 && $screenWidth < 1024;
            
            // Standard: Tabellenansicht (Desktop)
            $this->viewMode = 'table';
            
            // Nur wenn Tablet/Mobile erkannt, auf Kartenansicht wechseln
            if ($isMobile || $isTablet || $isTabletByWidth) {
                $this->viewMode = 'cards';
            }
        }
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

    public function render()
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        $search = $this->search;

        $query = Customer::where('client_id', $clientId)
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('customername', 'like', "%$search%")
                        ->orWhere('companyname', 'like', "%$search%")
                        ->orWhere('address', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('postalcode', 'like', "%$search%")
                        ->orWhere('location', 'like', "%$search%")
                        ->orWhere('customer_number', 'like', "%$search%");
                });
            });

        // Sortierung anwenden
        switch ($this->sortBy) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name':
                $query->orderBy('customername', 'asc');
                break;
            case 'company':
                $query->orderBy('companyname', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $customers = $query->paginate($this->perPage);

        return view('livewire.customer.customer-table', [
            'customers' => $customers,
        ]);
    }
} 