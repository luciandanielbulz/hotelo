<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Offers;

class SearchList extends Component
{
    public $searchTerm =  '';
    public $invoiceId;
    public $offerId;
    public $documentType = 'invoice'; // 'invoice' oder 'offer'

    protected $listeners = ['customerSelected' => 'handleCustomerSelected'];

    public function mount($invoiceId = null, $offerId = null)
    {
        $this->invoiceId = $invoiceId;
        $this->offerId = $offerId;
        $this->documentType = $offerId ? 'offer' : 'invoice';
    }

    public function selectCustomer($customerId)
    {
        $customer = Customer::find($customerId);
        
        if (!$customer) {
            return;
        }

        if ($this->documentType === 'offer' && $this->offerId) {
            // Aktualisiere das Angebot mit dem neuen Kunden
            $offer = Offers::find($this->offerId);
            if ($offer) {
                $offer->customer_id = $customerId;
                $offer->save();
            }
        } elseif ($this->documentType === 'invoice' && $this->invoiceId) {
            // Aktualisiere die Rechnung mit dem neuen Kunden (bestehende Logik)
            $invoice = Invoice::find($this->invoiceId);
            if ($invoice) {
                $invoice->customer_id = $customerId;
                $invoice->save();
            }
        }

        // Dispatch des Ereignisses "customer-updated"
        $this->dispatch('customer-updated', [
            'customer' => $customer
        ]);
    }
    public function performSearch()
    {
        \Log::info('Suche gestartet mit: ' . $this->searchTerm);
        // Diese Methode muss nicht zwingend Logik enthalten,
        // da das render() beim Klick aufgerufen wird und dabei $searchTerm verwendet.
        // Optional: Zusätzliche Logik zur Verarbeitung der Suche.
    }


    public function render()
    {

        \Log::info('Render aufgerufen mit Suche: ' . $this->searchTerm);

        //dd($this->searchTerm);
        $customers = Customer::query()
            ->when($this->searchTerm, function($query) {
                $query->where(function($q) {
                    $q->where('companyname', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('customername', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('address', 'like', '%' . $this->searchTerm . '%');
                });
            })
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();


        //dd($customers);

    return view('livewire.customer.search-list', compact('customers'));
    }
}
