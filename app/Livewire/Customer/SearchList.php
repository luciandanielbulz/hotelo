<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Offers;
use Illuminate\Support\Facades\Auth;

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
        // Aktuell eingeloggter Benutzer
        $user = Auth::user();
        $clientId = $user->client_id;

        // Kunde laden und Berechtigung prüfen
        $customer = Customer::where('id', $customerId)
            ->where('client_id', $clientId) // Nur Kunden des aktuellen Clients
            ->first();
        
        if (!$customer) {
            session()->flash('error', 'Kunde nicht gefunden oder keine Berechtigung.');
            return;
        }

        if ($this->documentType === 'offer' && $this->offerId) {
            // Aktualisiere das Angebot mit dem neuen Kunden - mit Berechtigungsprüfung
            $offer = Offers::join('customers as c', 'offers.customer_id', '=', 'c.id')
                ->leftJoin('clients as cl', 'offers.client_version_id', '=', 'cl.id')
                ->where('offers.id', $this->offerId)
                ->where(function($query) use ($clientId) {
                    // Berechtigung für das Angebot prüfen
                    $query->where('c.client_id', $clientId)
                          ->orWhere('cl.id', $clientId)
                          ->orWhere('cl.parent_client_id', $clientId);
                })
                ->select('offers.*')
                ->first();
                
            if ($offer) {
                $offer->customer_id = $customerId;
                $offer->save();
            } else {
                session()->flash('error', 'Angebot nicht gefunden oder keine Berechtigung.');
                return;
            }
        } elseif ($this->documentType === 'invoice' && $this->invoiceId) {
            // Aktualisiere die Rechnung mit dem neuen Kunden - mit Berechtigungsprüfung
            $invoice = Invoice::join('customers as c', 'invoices.customer_id', '=', 'c.id')
                ->where('invoices.id', $this->invoiceId)
                ->where('c.client_id', $clientId) // Nur Rechnungen des aktuellen Clients
                ->select('invoices.*')
                ->first();
                
            if ($invoice) {
                $invoice->customer_id = $customerId;
                $invoice->save();
            } else {
                session()->flash('error', 'Rechnung nicht gefunden oder keine Berechtigung.');
                return;
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
        // Aktuell eingeloggter Benutzer
        $user = Auth::user();
        $clientId = $user->client_id;

        $customers = Customer::query()
            ->where('client_id', $clientId) // Nur Kunden des aktuellen Clients anzeigen
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

        return view('livewire.customer.search-list', compact('customers'));
    }
}
