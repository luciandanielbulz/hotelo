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
		// Verwende den in der Komponente gehaltenen Status statt DB-Read,
		// um Race-Conditions und kurzes "Wegschnappen" der Auswahl zu vermeiden
		$currentStatus = (int) ($this->status ?? 0);
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
		// Stabile, feste Reihenfolge beibehalten; nur "Gesendet" ggf. ausblenden
		$filtered = $options;
		if (!$canSend && $currentStatus !== 2) {
			unset($filtered[2]);
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

		// "Gesendet" (2) nur mit send_emails erlaubt
		if ($newStatus === 2 && !$user->hasPermission('send_emails')) {
			$this->status = $oldStatus;
			$this->message = 'Keine Berechtigung, den Status "Gesendet" zu setzen.';
			$this->dispatch('notify', message: $this->message, type: 'error');
			return;
		}

        // Speichern
        $invoice->status = $newStatus;
        $invoice->save();

		// UI-Status nach erfolgreichem Speichern sicher auf neuen Wert setzen
		$this->status = $newStatus;
		// Zur Sicherheit aus der DB nachladen (verhindert Inkonsistenzen bei parallelen Updates)
		$this->status = (int) (Invoices::find($this->invoiceId)->status ?? $newStatus);

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


