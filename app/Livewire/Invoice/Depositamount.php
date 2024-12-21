<?php

namespace App\Livewire\Invoice;

use Livewire\Component;
use App\Models\Invoices;
use Illuminate\Support\Facades\DB;

class Depositamount extends Component
{
    public $invoiceId;
    public $depositAmount;

    public function mount($invoiceId)
    {

        $this->invoiceId = $invoiceId;
        $this->loadData($invoiceId);
    }

    public function sendUpdateAmount()
    {
        $invoiceId = $this->invoiceId;

        $this->validate([
            'depositAmount' => 'nullable|numeric|min:0',  // Validierung
        ]);

        // Wenn depositAmount leer oder null ist, setze es auf 0
        if (empty($this->depositAmount)) {
            $this->depositAmount = 0;
        }

        // Speichern des Wertes in der DB
        DB::table('invoices')
            ->where('id', $invoiceId)  // Sicherstellen, dass die richtige Rechnung aktualisiert wird
            ->update(['depositamount' => $this->depositAmount]);

        // Event dispatchen, um die Summen zu aktualisieren
        $this->dispatch('updateSums');
    }

    public function loadData($invoiceId)
    {
        $this->depositAmount = Invoices::join('taxrates', 'invoices.tax_id', '=', 'taxrates.id')
            ->where('invoices.id', '=', $invoiceId)
            ->select('invoices.depositamount')
            ->first()
            ->depositamount;

        $this->total_price = Invoices::join('invoicepositions', 'invoices.id', '=', 'invoicepositions.invoice_id')
            ->where('invoicepositions.invoice_id', '=', $invoiceId)
            ->select(DB::raw('SUM(invoicepositions.amount * invoicepositions.price) as total_price'))
            ->first()
            ->total_price;
    }

         //dd($this->depositamount->depositamount);

    public function render()
    {
        return view('livewire.invoice.depositamount');
    }
}
