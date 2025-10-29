<?php

namespace App\Livewire\Invoice;

use Livewire\Component;
use App\Models\Invoices;
use App\Models\Clients;

class DocumentFooter extends Component
{
    public int $invoiceId;
    public string $footerContent = '';
    protected bool $isSaving = false;

    public function mount(int $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
        $invoice = Invoices::findOrFail($invoiceId);
        $this->footerContent = (string) ($invoice->document_footer ?? '');
        // Fallback: wenn noch kein rechnungsspezifischer Fußtext vorhanden ist, von der Client-Version anzeigen
        if ($this->footerContent === '' && $invoice->client_version_id) {
            $client = Clients::find($invoice->client_version_id);
            if ($client && !empty($client->document_footer)) {
                $this->footerContent = (string) $client->document_footer;
            }
        }
    }

    public function saveFooter(): void
    {
        if ($this->isSaving) {
            return;
        }
        $this->isSaving = true;
        try {
            $this->validate([
                'footerContent' => 'nullable|string',
            ]);

            $invoice = Invoices::findOrFail($this->invoiceId);
            $invoice->document_footer = $this->footerContent ?? '';
            $invoice->save();

            // Zusammenfassung aktualisieren (lädt footer neu)
            $this->dispatch('updateSums');
            $this->dispatch('notify', message: 'Fußtext gespeichert.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Fehler beim Speichern des Fußtexts: ' . $e->getMessage(), type: 'error');
        } finally {
            $this->isSaving = false;
        }
    }

    // Keine Auto-Speicherung mehr über Property-Änderung

    public function render()
    {
        return view('livewire.invoice.document-footer');
    }
}


