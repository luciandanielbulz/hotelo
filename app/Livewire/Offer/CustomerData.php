<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use App\Models\Offers;

class CustomerData extends Component
{
    public $offerId;

    // Füge Listener hinzu, die auf "customer-updated" reagieren
    protected $listeners = ['customer-updated' => '$refresh'];

    public function mount($offerId)
    {
        $this->offerId = $offerId;
    }

    public function render()
    {
        $customer = Offers::join('customers', 'offers.customer_id', '=', 'customers.id')
            ->where('offers.id', $this->offerId)
            ->select('customers.*')
            ->first();

        return view('livewire.offer.customer-data', [
            'customer' => $customer
        ]);
    }
} 