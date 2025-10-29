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

            // Teams/Editor-Markup bereinigen
            $clean = $this->footerContent ?? '';
            // Entferne umschließendes <span data-teams="true"> innerhalb eines <p>
            $clean = preg_replace('/<p>\s*<span[^>]*data-teams=\"true\"[^>]*>/i', '<p>', $clean);
            $clean = preg_replace('/<\/span>\s*<\/p>/i', '</p>', $clean);
            // Style-Attribute margin-left o.Ä. entfernen
            $clean = preg_replace('/\sstyle=\"[^\"]*\"/i', '', $clean);
            // Mehrfache verschachtelte <p><p> -> ein <p>
            $clean = preg_replace('/<p>\s*<p>/i', '<p>', $clean);
            $clean = preg_replace('/<\/p>\s*<\/p>/i', '</p>', $clean);
            // Überflüssige Wrapper-<p> um nur <br/>&nbsp; etc. optional glätten (vorsichtig)
            $clean = preg_replace('/<p>\s*&nbsp;\s*<\/p>/i', '<p>&nbsp;</p>', $clean);

            $invoice = Invoices::findOrFail($this->invoiceId);
            $invoice->document_footer = $clean;
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

    public function updatedFooterContent(): void
    {
        $this->saveFooter();
    }

    public function render()
    {
        return view('livewire.invoice.document-footer');
    }
}


