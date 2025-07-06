<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Offers;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class Positiontable extends Component
{
    use WithPagination;

    public $perPage = 9;
    public $search = '';

    // Optional: Suchparameter in der URL behalten    protected $queryString = ['search'];

    public function boot()
    {
        Paginator::useTailwind(); // Oder ->useBootstrap(), je nach verwendetem CSS-Framework
    }

    // Reset der Seite, wenn sich die Suchanfrage ändert
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
    public function archiveOffer($offerId)
    {
        \Log::info("archiveOffer aufgerufen mit ID: " . $offerId);

        $offer = Offers::find($offerId);

        if ($offer) {
            $offer->archived = true;
            $offer->archiveddate = now(); // Optional: Archivierungsdatum setzen
            $offer->save();

            session()->flash('message', 'Angebot erfolgreich archiviert.');
            // Keine Notwendigkeit, loadData aufzurufen, da render automatisch aufgerufen wird
        } else {
            session()->flash('error', 'Angebot nicht gefunden.');
        }
    }

    /**
     * Render-Methode der Komponente.
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
            ->where('offers.archived', false)
            ->orderBy('offers.id', 'desc')
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('customers.customername', 'like', "%$search%")
                        ->orWhere('customers.companyname', 'like', "%$search%")
                        ->orWhere('offers.number', 'like', "%$search%");
                });
            })
            ->select(
                'offers.id as offer_id',
                'offers.number',
                'offers.archived',
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
                'offers.created_at',
                'offers.updated_at',
                'offers.date',
                'offers.description',
                'customers.customername',
                'customers.companyname',
                'latest_emails.latest_sentdate'
            );


        $offers = $query->paginate($this->perPage);
        $offers->appends(['search' => $search]);

        //dd($offers);

        return view('livewire.offer.positiontable', [
            'offers' => $offers,
        ]);
    }
}
