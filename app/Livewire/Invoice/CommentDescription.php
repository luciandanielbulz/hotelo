<?php

namespace App\Livewire\Invoice;

use Livewire\Component;
use App\Models\Invoices;

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
        //dd($invoiceId);
        $this->details = Invoices::findOrFail($invoiceId);

        $this->comment = $this->details->comment;
        $this->description = $this->details->description;
    }

    public function updateCommentDescription()
    {
        try {
            $this->validate([
                'comment' => 'nullable|string',
                'description' => 'nullable|string'
            ]);

            $invoice = Invoices::findOrFail($this->invoiceId);
            $invoice->comment = $this->comment;
            $invoice->description = $this->description;
            $invoice->save();

            $this->message = 'Zusätzliche Informationen erfolgreich aktualisiert.';

            // Event-Dispatch entfernt - Erfolgsmeldung wird jetzt nur noch in der Komponente angezeigt

            // Debugging hinzufügen
            \Log::info('Daten aktualisiert:', [
                'comment' => $this->comment,
                'description' => $this->description
            ]);

            $this->loadData($this->invoiceId);
            
        } catch (\Exception $e) {
            \Log::error('Fehler beim Speichern der Zusätzlichen Informationen: ' . $e->getMessage(), [
                'exception' => $e,
                'invoiceId' => $this->invoiceId,
            ]);
            
            $this->message = 'Fehler beim Speichern: ' . $e->getMessage();
        }
    }

    public function render()
    {
        //dd($this->details);
        return view('livewire.invoice.comment-description', [
            'details' => $this->details, // Überprüfen, ob $details gefüllt ist
        ]);
    }
}
