<?php

namespace App\Livewire\Invoice;

use Livewire\Component;
use App\Models\Invoices;
use Illuminate\Support\Facades\DB;

class Depositamount extends Component
{
    public $invoiceId;
    public $depositAmount;
    protected bool $isSaving = false;

    public function mount($invoiceId)
    {

        $this->invoiceId = $invoiceId;
        $this->loadData($invoiceId);
    }

    public function sendUpdateAmount()
    {
        if ($this->isSaving) {
            return;
        }
        $this->isSaving = true;

        try {
            $invoiceId = $this->invoiceId;

            // Wert normalisieren (dezimales Komma, Tausenderpunkte, Leerzeichen)
            $raw = is_string($this->depositAmount) ? trim($this->depositAmount) : $this->depositAmount;
            if ($raw === null || $raw === '') {
                $normalized = 0;
            } else {
                if (is_string($raw)) {
                    // Entferne Tausenderpunkte und Leerzeichen, ersetze Komma durch Punkt
                    $raw = str_replace([' ', "\u{00A0}"], '', $raw);
                    $raw = str_replace(['.'], '', $raw); // Tausenderpunkte
                    $raw = str_replace([','], '.', $raw); // Dezimalkomma
                }
                $normalized = (float) $raw;
            }

            // Nicht negativ
            if ($normalized < 0) {
                $normalized = 0;
            }

            // Auf 2 Nachkommastellen runden
            $normalized = round($normalized, 2);

            // Property auf den bereinigten Wert setzen, damit die UI konsistent ist
            $this->depositAmount = $normalized;

            // Validierung nach Normalisierung
            $this->validate([
                'depositAmount' => 'nullable|numeric|min:0',
            ]);

            // Speichern des Wertes in der DB
            DB::table('invoices')
                ->where('id', $invoiceId)
                ->update(['depositamount' => $this->depositAmount]);

            // Event dispatchen, um die Summen zu aktualisieren und Toast anzeigen
            $this->dispatch('updateSums');
            $this->dispatch('notify', message: 'Anzahlung gespeichert.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Fehler beim Speichern der Anzahlung: ' . $e->getMessage(), type: 'error');
        } finally {
            $this->isSaving = false;
        }
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

    // Auto-Save nach Eingabe-Pause
    public function updatedDepositAmount()
    {
        $this->sendUpdateAmount();
    }
}
