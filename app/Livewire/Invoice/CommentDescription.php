<?php

namespace App\Livewire\Invoice;
use App\Models\Invoices;

use Livewire\Component;

class CommentDescription extends Component
{
    public $invoiceId;
    public $comment;
    public $description;
    public $message;
    public $details;

    public function mount($invoiceId)
    {
        $this->invoiceId = $invoiceId;
        $this->loadData($invoiceId);
    }

    public function loadData($invoiceId)
    {
        dd($invoiceId);
        $this->details = Invoices::findOrFail($invoiceId);

        $this->comment = $this->details->comment;
        $this->description = $this->details->description;
    }

    public function updateCommentDescription()
    {
        $this->validate([
            'comment' => 'required|string',
            'description' => 'required|string'
        ]);

        $invoice = Invoices::findOrFail($this->invoiceId);
        $invoice->comment = $this->comment;
        $invoice->description = $this->description;
        $invoice->save();

        $this->message = 'Zusätzliche Informationen erfolgreich aktualisiert.';

        // Debugging hinzufügen
        \Log::info('Daten aktualisiert:', [
            'comment' => $this->comment,
            'description' => $this->description
        ]);

        $this->loadData($this->invoiceId);
    }

    public function render()
    {
        //dd($this->details);
        return view('livewire.invoice.comment-description', [
            'details' => $this->details, // Überprüfen, ob $details gefüllt ist
        ]);
    }
}
