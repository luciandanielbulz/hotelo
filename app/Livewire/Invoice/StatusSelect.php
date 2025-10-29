<?php

namespace App\Livewire\Invoice;

use App\Models\Invoices;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StatusSelect extends Component
{
    public int $invoiceId;
    public int $status = 0;
    public ?string $message = null;

    public function mount(int $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
        $invoice = Invoices::findOrFail($invoiceId);
        $this->status = (int) ($invoice->status ?? 0);
    }

    public function getStatusOptionsProperty(): array
    {
        $invoice = Invoices::findOrFail($this->invoiceId);
        $currentStatus = (int) ($invoice->status ?? 0);
        $canDowngrade = Auth::user() && Auth::user()->hasPermission('unlock_invoices');
        $canSend = Auth::user() && Auth::user()->hasPermission('send_emails');

        $options = [
            0 => 'Entwurf',
            1 => 'Offen',
            2 => 'Gesendet',
            3 => 'Teilweise bezahlt',
            4 => 'Bezahlt',
            6 => 'Storniert',
            7 => 'Archiviert',
        ];

        // Filter Optionen je nach Berechtigung und aktuellem Status
        $filtered = [];
        foreach ($options as $value => $label) {
            if (!$canSend && (int)$value === 2 && $currentStatus !== 2) {
                continue;
            }
            if ($canDowngrade || (int)$value >= $currentStatus) {
                $filtered[$value] = $label;
            }
        }

        return $filtered;
    }

    // Wird automatisch aufgerufen, wenn sich $status ändert (durch wire:model)
    public function updatedStatus(int $newStatus): void
    {
        $user = Auth::user();
        $invoice = Invoices::findOrFail($this->invoiceId);

        // Wenn bezahlt und kein Entsperr-Recht, blockieren
        if ((int)($invoice->status ?? 0) === 4 && !$user->hasPermission('unlock_invoices')) {
            $this->status = (int) ($invoice->status ?? 0);
            $this->message = 'Diese Rechnung ist bezahlt. Ihnen fehlt die Berechtigung zur Bearbeitung.';
            $this->dispatch('notify', message: $this->message, type: 'error');
            return;
        }

        $oldStatus = (int) ($invoice->status ?? 0);

        // Downgrade nur mit Berechtigung
        if ($newStatus < $oldStatus && !$user->hasPermission('unlock_invoices')) {
            $this->status = $oldStatus;
            $this->message = 'Kein Recht zum Herabstufen des Status.';
            $this->dispatch('notify', message: $this->message, type: 'error');
            return;
        }

        // Speichern
        $invoice->status = $newStatus;
        $invoice->save();

        $this->message = 'Status erfolgreich aktualisiert.';
        $this->dispatch('notify', message: $this->message, type: 'success');

        // Optional Events für andere Komponenten
        $this->dispatch('statusUpdated', status: $newStatus, invoiceId: $this->invoiceId);
    }

    public function render()
    {
        return view('livewire.invoice.status-select', [
            'statusOptions' => $this->statusOptions,
        ]);
    }
}


