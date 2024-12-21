<?php

namespace App\Livewire\Invoice;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Invoices;
use Illuminate\Support\Facades\DB;

class Calculations extends Component
{
    public $invoiceId;

    public $depositAmount;

    public $invoice;
    public $total_price;

    public function mount($invoiceId)
    {

        $this->invoiceId = $invoiceId;
        $this->loadData($invoiceId);
    }

    #[On('updateSums')]
    public function updateSums(){
        //dd('test');

    }

    public function loadData($invoiceId)
    {
        // Lade die Rechnung mit den zugehörigen Positionen
        $invoice = Invoices::with('invoicePositions')->find($invoiceId);

        // Berechne den Gesamtpreis in PHP
        $this->total_price = $invoice->invoicePositions->sum(function ($position) {
            return $position->amount * $position->price;
        });

        // Weitere Daten laden
        $this->invoice = $invoice;  // Hier kannst du das komplette Invoice-Objekt weitergeben
        $this->depositAmount = $invoice->depositamount;

        //dd($this->total_price);
    }




    public function render()
    {
        return view('livewire.invoice.calculations');
    }
}
