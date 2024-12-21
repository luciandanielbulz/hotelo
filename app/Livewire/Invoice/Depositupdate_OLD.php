<?php

namespace App\Livewire\Invoice;

use Livewire\Component;
use App\Models\Invoices;
use Illuminate\Support\Facades\DB;

class Depositupdate extends Component
{

    public $invoiceId;
    public $depositamount;
    public $details;
    public $total_price;




    public function mount($invoiceId)
    {

        $this->invoiceId = $invoiceId;
        $this->loadData($invoiceId);
    }

    public function loadData($invoiceId)
    {
        $this->details = Invoices::join('taxrates', 'invoices.tax_id','=','taxrates.id')
            ->where('invoices.id','=', $invoiceId)
            ->select('invoices.*', 'taxrates.*')
            ->first();

        $this->total_price = Invoices::join('invoicepositions', 'invoices.id', '=', 'invoicepositions.invoice_id')
        ->where('invoicepositions.invoice_id', '=', $invoiceId)
        ->select(DB::raw('SUM(invoicepositions.amount * invoicepositions.price) as total_price'))
        ->first();


        $this->depositamount = $this->details->depositamount;
        //dd($this->depositamount);
    }


    public function updateDepositAmount(){

        //dd("test");
        $this->validate([
            'depositamount' => 'nullable|numeric',
        ]);
        //dd("bis dahin");

        $invoice = Invoices::findOrFail($this->invoiceId);
        $invoice->depositamount = $this->depositamount;
        $invoice->save();

        // Erfolgsnachricht setzen
        $this->dispatch('depositupdated', [
            'message' => 'Anzahlung erfolgreich aktualisiert.'
        ]);

        \Log::info('Daten aktualisiert:', [
            'depositamount' => $this->depositamount

        ]);

        $this->loadData($this->invoiceId);
    }

    public function render()
    {


        //dd($this->details);
        return view('livewire.invoice.depositupdate', [
            'details' => $this->details, // Überprüfen, ob $details gefüllt ist
        ]);
    }


}
