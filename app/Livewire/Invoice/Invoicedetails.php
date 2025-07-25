<?php


namespace App\Livewire\Invoice;

use App\Models\Invoices;
use App\Models\Condition;
use App\Models\Clients;
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
    public $client;

    public $taxrateid;
    public $reverse_charge = false;
    public $invoiceDate;
    public $invoiceNumber;
    public $periodfrom;
    public $periodto;
    
    protected $stored_taxrateid; // Speichert ursprünglichen Steuersatz für Wiederherstellung


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
        
        // Client-Daten für Kleinunternehmer-Prüfung laden
        $this->client = Clients::findOrFail($user->client_id);

        $this->taxrateid = $this->details->tax_id;
        $this->reverse_charge = (bool) ($this->details->reverse_charge ?? false);
        
        // Bei Kleinunternehmern ist Reverse Charge nicht möglich
        if ($this->client->smallbusiness == 1) {
            $this->reverse_charge = false;
        }
        
        // Speichere ursprünglichen Steuersatz nur wenn reverse_charge false ist
        if (!$this->reverse_charge) {
            $this->stored_taxrateid = $this->details->tax_id;
        }
        
        $this->depositamount = $this->details->depositamount;
        $this->invoiceDate = $this->details->date ? Carbon::parse($this->details->date)->format('Y-m-d') : '';
        $this->invoiceNumber = $this->details->number;
        $this->condition_id = $this->details->condition_id;
        $this->periodfrom = $this->details->periodfrom ? Carbon::parse($this->details->periodfrom)->format('Y-m-d') : '';
        $this->periodto = $this->details->periodto ? Carbon::parse($this->details->periodto)->format('Y-m-d') : '';
        //dd($this->taxrateid);
    }

    public function handleReverseChargeChange()
    {
        if ($this->reverse_charge) {
            // Aktuellen Steuersatz speichern, falls noch nicht gespeichert
            if (!$this->stored_taxrateid) {
                $this->stored_taxrateid = $this->taxrateid;
            }
            
            // Steuersatz auf 0% setzen (Annahme: 0% Steuersatz hat ID 1)
            // Hier müsste man die richtige ID für 0% aus der Datenbank holen
            $this->taxrateid = 1; // Anpassen je nach Datenbank-Setup
            
            $this->message = 'Reverse Charge aktiviert - Steuersatz auf 0% gesetzt.';
        } else {
            // Ursprünglichen Steuersatz wiederherstellen
            if ($this->stored_taxrateid) {
                $this->taxrateid = $this->stored_taxrateid;
                $this->message = 'Reverse Charge deaktiviert - ursprünglicher Steuersatz wiederhergestellt.';
            }
        }
    }

    public function updateInvoiceDetails()
    {
        try {
            \Log::info('updateInvoiceDetails gestartet', [
                'invoiceId' => $this->invoiceId,
                'condition_id' => $this->condition_id,
                'taxrateid' => $this->taxrateid,
                'reverse_charge' => $this->reverse_charge,
                'invoiceDate' => $this->invoiceDate,
                'invoiceNumber' => $this->invoiceNumber,
            ]);

            // Sicherheitsprüfung: Kleinunternehmer können kein Reverse Charge verwenden
            if ($this->client->smallbusiness == 1 && $this->reverse_charge) {
                $this->reverse_charge = false;
                $this->message = 'Reverse Charge ist für Kleinunternehmer nicht verfügbar.';
                return;
            }

            $user = Auth::user();

            // Validierung mit ausführlichem Logging
            try {
                $this->validate([
                    'taxrateid' => 'required|integer',
                    'reverse_charge' => 'boolean',
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
                'alte_reverse_charge' => $invoice->reverse_charge,
                'neue_reverse_charge' => $this->reverse_charge,
            ]);
            
            $invoice->tax_id = $this->taxrateid;
            $invoice->reverse_charge = (bool) $this->reverse_charge;
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
            
            // Event dispatchen, um andere Komponenten über die Änderung zu informieren
            $this->dispatch('updateSums');

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
