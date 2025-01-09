<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Invoice;

class SearchList extends Component
{
    public $searchTerm =  '';
    public $invoiceId;

    protected $listeners = ['customerSelected' => 'handleCustomerSelected'];



    public function selectCustomer($customerId)
    {
        //dd($customerId);
        // Aktualisiere die Rechnung mit dem neuen Kunden
        $invoice = Invoice::find($this->invoiceId);
        $customer = Customer::find($customerId);

        if ($invoice && $customer) {
            $invoice->customer_id = $customerId;
            $invoice->save();

            // Dispatch des Ereignisses "customer-updated"
            $this->dispatch('customer-updated', [
                'customer' => $customer
            ]);
        }
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
            ->limit(7)
            ->get();


        //dd($customers);

    return view('livewire.customer.search-list', compact('customers'));
    }
}
