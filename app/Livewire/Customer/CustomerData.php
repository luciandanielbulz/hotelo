<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Invoice;

class CustomerData extends Component
{
    public $invoiceId;

    // Füge Listener hinzu, die auf "customer-updated" reagieren
    protected $listeners = ['customer-updated' => '$refresh'];

    public function mount($invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }

    public function render()
    {
        $customer = Invoice::join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->find($this->invoiceId);

        return view('livewire.customer.customer-data', [
            'customer' => $customer
        ]);
    }
}
