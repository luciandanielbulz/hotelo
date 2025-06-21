<?php


namespace App\Livewire\Invoice;

use App\Models\Invoices;
use App\Models\Condition;
use Illuminate\Support\Facades\Auth;

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

    public $depositamount;
    public $message;

    public $conditions;


    public $taxrateid;
    public $invoiceDate;
    public $invoiceNumber;
    public $periodfrom;
    public $periodto;


    public function mount($invoiceId)
    {
        $this->invoiceId = $invoiceId;
        $this->loadData($invoiceId);
    }

    public function loadData($invoiceId)
    {
        $this->details = Invoices::findOrFail($invoiceId);

        // Nur aktive Konditionen des aktuellen Clients laden
        $user = Auth::user();
        $this->conditions = Condition::where('client_id', $user->client_id)->get();

        $this->taxrateid = $this->details->tax_id;
        $this->depositamount = $this->details->depositamount;
        $this->invoiceDate = $this->details->date ? Carbon::parse($this->details->date)->format('Y-m-d') : '';
        $this->invoiceNumber = $this->details->number;
        $this->condition_id = $this->details->condition_id;
        $this->periodfrom = $this->details->periodfrom ? Carbon::parse($this->details->periodfrom)->format('Y-m-d') : '';
        $this->periodto = $this->details->periodto ? Carbon::parse($this->details->periodto)->format('Y-m-d') : '';
        //dd($this->taxrateid);
    }

    public function updateInvoiceDetails()
    {
        try {
            \Log::info('updateInvoiceDetails gestartet', [
                'invoiceId' => $this->invoiceId,
                'condition_id' => $this->condition_id,
                'taxrateid' => $this->taxrateid,
                'invoiceDate' => $this->invoiceDate,
                'invoiceNumber' => $this->invoiceNumber,
            ]);

            $user = Auth::user();

            // Validierung mit ausführlichem Logging
            try {
                $this->validate([
                    'taxrateid' => 'required|integer',
                    'invoiceDate' => 'required|date',
                    'invoiceNumber' => 'required|string|max:100',
                    'condition_id' => 'required|integer',
                    'periodfrom' => 'nullable|date',
                    'periodto' => 'nullable|date|after_or_equal:periodfrom',
                ]);
                \Log::info('Validierung erfolgreich');
            } catch (\Exception $e) {
                \Log::error('Validierung fehlgeschlagen: ' . $e->getMessage());
                $this->message = 'Validierung fehlgeschlagen: ' . $e->getMessage();
                return;
            }

            // Sicherheitsprüfung: Condition gehört zum aktuellen Client
            $condition = Condition::find($this->condition_id);
            if (!$condition) {
                \Log::error('Condition nicht gefunden: ' . $this->condition_id);
                $this->addError('condition_id', 'Diese Bedingung wurde nicht gefunden.');
                $this->message = 'Fehler: Bedingung nicht gefunden.';
                return;
            }
            
            if ($condition->client_id !== $user->client_id) {
                \Log::error('Condition gehört nicht zum Client', [
                    'condition_client_id' => $condition->client_id,
                    'user_client_id' => $user->client_id
                ]);
                $this->addError('condition_id', 'Diese Bedingung ist nicht verfügbar.');
                $this->message = 'Fehler: Bedingung nicht verfügbar.';
                return;
            }

            $invoice = Invoices::findOrFail($this->invoiceId);
            
            \Log::info('Vor dem Update:', [
                'alte_condition_id' => $invoice->condition_id,
                'neue_condition_id' => $this->condition_id,
            ]);
            
            $invoice->tax_id = $this->taxrateid;
            $invoice->date = $this->invoiceDate;
            $invoice->number = $this->invoiceNumber;
            $invoice->condition_id = $this->condition_id;
            
            // Null-Werte für leere Datumsfelder setzen
            $invoice->periodfrom = !empty($this->periodfrom) ? $this->periodfrom : null;
            $invoice->periodto = !empty($this->periodto) ? $this->periodto : null;
            
            $saved = $invoice->save();
            
            \Log::info('Nach dem Update:', [
                'gespeichert' => $saved,
                'condition_id' => $invoice->condition_id,
                'invoice_data' => $invoice->toArray()
            ]);

            $this->message = 'Details erfolgreich aktualisiert.';
            
            $this->dispatch('comment-updated', [
                'message' => 'Details erfolgreich aktualisiert.'
            ]);

            // Daten neu laden, um sicherzustellen, dass die Änderungen angezeigt werden
            $this->loadData($this->invoiceId);
            
        } catch (\Exception $e) {
            \Log::error('Fehler beim Speichern der Invoice-Details: ' . $e->getMessage(), [
                'exception' => $e,
                'invoiceId' => $this->invoiceId,
                'condition_id' => $this->condition_id,
            ]);
            
            $this->message = 'Fehler beim Speichern: ' . $e->getMessage();
        }
    }

    public function render()
    {
        \Log::info('Rendering details:', ['details' => $this->details]);

        return view('livewire.invoice.invoicedetails', [
            'details' => $this->details // Überprüfen, ob $details gefüllt ist
        ]);
    }
}
