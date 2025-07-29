<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Offers;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class ArchivedPositiontable extends Component
{
    use WithPagination;

    public $perPage = 12;
    public $search = '';
    public $viewMode = 'table'; // 'cards' oder 'table'
    public $sortBy = 'newest'; // 'newest', 'oldest', 'number', 'customer'

    public function boot()
    {
        Paginator::useTailwind();
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
        $this->viewMode = $mode;
    }

    /**
     * Stellt ein archiviertes Angebot wieder her.
     *
     * @param int $offerId
     * @return void
     */
    public function unarchiveOffer($offerId)
    {
        \Log::info("unarchiveOffer aufgerufen mit ID: " . $offerId);

        $offer = Offers::find($offerId);

        if ($offer) {
            $offer->archived = false;
            $offer->archiveddate = null;
            $offer->save();

            session()->flash('message', 'Angebot erfolgreich wiederhergestellt.');
        } else {
            session()->flash('error', 'Angebot nicht gefunden.');
        }
    }

    /**
     * Render-Methode der Komponente für archivierte Angebote.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        $search = $this->search;

        $query = Offers::join('customers', 'offers.customer_id', '=', 'customers.id')
            ->leftJoin('offerpositions', 'offers.id', '=', 'offerpositions.offer_id')
            ->leftJoin('clients', 'offers.client_version_id', '=', 'clients.id') // Join für Client-Version
            ->leftJoin(DB::raw('(SELECT objectnumber, MAX(sentdate) as latest_sentdate FROM outgoingemails GROUP BY objectnumber) as latest_emails'), 'latest_emails.objectnumber', '=', 'offers.number')
            ->where(function($query) use ($clientId) {
                // Zeige Angebote an, wenn:
                // 1. Der Customer zu diesem Client gehört (alte Logik)
                // 2. ODER das Angebot mit einer Client-Version erstellt wurde, die zu diesem Client gehört (neue Logik)
                $query->where('customers.client_id', $clientId)
                      ->orWhere('clients.id', $clientId)
                      ->orWhere('clients.parent_client_id', $clientId);
            })
            ->where('offers.archived', true) // NUR archivierte Angebote anzeigen
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('customers.customername', 'like', "%$search%")
                        ->orWhere('customers.companyname', 'like', "%$search%")
                        ->orWhere('offers.number', 'like', "%$search%");
                });
            });

        // Sortierung anwenden
        switch ($this->sortBy) {
            case 'oldest':
                $query->orderBy('offers.archiveddate', 'asc')
                      ->orderBy('offers.number', 'asc');
                break;
            case 'number':
                $query->orderBy('offers.number', 'asc');
                break;
            case 'customer':
                $query->orderBy('customers.customername', 'asc')
                      ->orderBy('customers.companyname', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('offers.archiveddate', 'desc')
                      ->orderBy('offers.number', 'desc');
                break;
        }

        $query->select(
            'offers.id as offer_id',
            'offers.number',
            'offers.archived',
            'offers.archiveddate',
            'offers.created_at',
            'offers.updated_at',
            'offers.date',
            DB::raw("CASE 
                WHEN LENGTH(offers.description) > 25 
                THEN CONCAT(LEFT(offers.description, 25), '...') 
                ELSE offers.description 
            END as description"),
            'customers.customername',
            'customers.companyname',
            DB::raw("DATE_FORMAT(latest_emails.latest_sentdate, '%Y-%m-%d %H:%i:%s') as sent_date"),
            DB::raw('SUM(offerpositions.amount * offerpositions.price) as total_price')
        )
        ->groupBy(
            'offers.id',
            'offers.number',
            'offers.archived',
            'offers.archiveddate',
            'offers.created_at',
            'offers.updated_at',
            'offers.date',
            'offers.description',
            'customers.customername',
            'customers.companyname',
            'latest_emails.latest_sentdate'
        );

        $offers = $query->paginate($this->perPage);

        return view('livewire.offer.archived-positiontable', [
            'offers' => $offers,
        ]);
    }
} 