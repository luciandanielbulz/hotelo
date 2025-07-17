<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use App\Models\Offers;
use App\Models\Customer;

class CustomerData extends Component
{
    public $offerId;
    public $offer;
    public $customer;
    
    protected $listeners = ['customer-updated' => 'handleCustomerUpdated'];

    public function mount($offerId)
    {
        $this->offerId = $offerId;
        $this->loadData();
    }

    public function loadData()
    {
        $this->offer = Offers::with('customer')->findOrFail($this->offerId);
        $this->customer = $this->offer->customer;
    }

    public function handleCustomerUpdated($data)
    {
        // Lade die Daten neu nach Kundenänderung
        $this->loadData();
        
        // Optional: Erfolgsmeldung
        session()->flash('success', 'Kunde erfolgreich geändert!');
    }

    public function render()
    {
        return view('livewire.offer.customer-data');
    }
} 