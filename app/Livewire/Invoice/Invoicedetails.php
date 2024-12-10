<?php


namespace App\Livewire\Invoice;

use App\Models\Invoices;

use Livewire\Component;

use Carbon\Carbon;

class Invoicedetails extends Component
{
    public $invoiceId; // Angebot ID

    public $details;
    public $tax_id;
    public $date;
    public $number;
    public $condition_id;
    public $message;


    public $taxrateid;
    public $invoiceDate;
    public $invoiceNumber;


    public function mount($invoiceId)
    {
        $this->invoiceId = $invoiceId;
        $this->loadData($invoiceId);
    }

    public function loadData($invoiceId)
    {
        $this->details = Invoices::findOrFail($invoiceId);

        $this->taxrateid = $this->details->tax_id;
        $this->invoiceDate = $this->details->date ? Carbon::parse($this->details->date)->format('Y-m-d') : '';
        $this->invoiceNumber = $this->details->number;
        $this->condition_id = $this->details->condition_id;
        //dd($this->taxrateid);
    }

    public function updateInvoiceDetails()
    {

        $this->validate([
            'taxrateid' => 'required|integer',
            'invoiceDate' => 'required|date',
            'invoiceNumber' => 'required|numeric',
            'condition_id' => 'required|integer',
        ]);

        //dd("test");
        $invoice = Invoices::findOrFail($this->invoiceId);
        $invoice->tax_id = $this->taxrateid;
        $invoice->date = $this->invoiceDate;
        $invoice->number = $this->invoiceNumber;
        $invoice->condition_id = $this->condition_id;
        $invoice->save();

        //dd($invoice);

        $this->dispatch('comment-updated', [
            'message' => 'Details erfolgreich aktualisiert.'
        ]);

        //dd($this->message);
        // Debugging hinzufügen
        \Log::info('Daten aktualisiert:', [
            'taxrateid' => $this->taxrateid,
            'invoiceDate' => $this->invoiceDate,
            'invoiceNumber' => $this->invoiceNumber,
            'condition' => $this->condition_id,
        ]);

        $this->loadData($this->invoiceId);
    }

    public function render()
    {
        \Log::info('Rendering details:', ['details' => $this->details]);

        return view('livewire.invoice.invoicedetails', [
            'details' => $this->details // Überprüfen, ob $details gefüllt ist
        ]);
    }
}
