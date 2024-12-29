<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Offers;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

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
    public function render(Request $request)
    {
        //dd($request);
        $user = Auth::user();
        $clientId = $user->client_id;

        $search = $request->input('search');

        $offers = Offers::join('customers', 'offers.customer_id', '=', 'customers.id')
            ->where('customers.client_id', $clientId)
            ->where('offers.archived', false) // Nur nicht archivierte Angebote anzeigen
            ->orderBy('offers.number', 'desc')
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('customers.customername', 'like', "%$search%")
                          ->orWhere('customers.companyname', 'like', "%$search%");
                });
            })
            ->select('offers.id as offer_id', 'offers.*', 'customers.*')
            ->paginate($this->perPage);

        return view('livewire.offer.positiontable', [
            'offers' => $offers,
        ]);
    }
}
