<?php


namespace App\Livewire\Invoice;

use App\Models\Invoices;
use App\Models\Condition;
use App\Models\Clients;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use Illuminate\Validation\ValidationException;

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
    public $status;
    
    protected $stored_taxrateid; // Speichert ursprünglichen Steuersatz für Wiederherstellung

    protected bool $isSaving = false;
    protected ?string $lastChangedProperty = null;


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
        
        // Client-Daten für Kleinunternehmer-Prüfung laden (immer aktive Version verwenden)
        $userClient = Clients::findOrFail($user->client_id);
        $this->client = $userClient->getCurrentVersion() ?? $userClient;

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
        
        // Lade die Nummer und füge Präfix hinzu, falls es fehlt
        $invoiceNumber = $this->details->number;
        
        // Hole das Präfix aus der Client-Version (versioniert)
        $invoiceClient = null;
        if ($this->details->client_version_id) {
            $invoiceClient = Clients::find($this->details->client_version_id);
        }
        
        // Fallback: Aktuelle aktive Client-Version
        if (!$invoiceClient) {
            $invoiceClient = $this->client;
        }
        
        // Hole Präfix aus Client (versioniert) oder ClientSettings
        $originalClientId = ($invoiceClient ? ($invoiceClient->parent_client_id ?? $invoiceClient->id) : null) ?? $user->client_id;
        $clientSettings = \App\Models\ClientSettings::where('client_id', $originalClientId)->first();
        
        $invoicePrefix = null;
        if ($invoiceClient) {
            $invoicePrefix = $invoiceClient->invoice_prefix ?? null;
        }
        if (empty($invoicePrefix) && $clientSettings) {
            $invoicePrefix = $clientSettings->invoice_prefix ?? null;
        }
        
        // Füge Präfix hinzu, falls es fehlt und ein Präfix vorhanden ist
        if (!empty($invoicePrefix) && !empty($invoiceNumber)) {
            // Prüfe, ob die Nummer bereits das Präfix hat
            if (strpos($invoiceNumber, $invoicePrefix) !== 0) {
                // Präfix fehlt, füge es hinzu
                $invoiceNumber = $invoicePrefix . $invoiceNumber;
            }
        }
        
        $this->invoiceNumber = $invoiceNumber;
        $this->condition_id = $this->details->condition_id;
        $this->periodfrom = $this->details->periodfrom ? Carbon::parse($this->details->periodfrom)->format('Y-m-d') : '';
        $this->periodto = $this->details->periodto ? Carbon::parse($this->details->periodto)->format('Y-m-d') : '';
        $this->status = (int) ($this->details->status ?? 0);
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
        // Änderungen sofort speichern
        $this->updateInvoiceDetails();
    }

    public function updateInvoiceDetails()
    {
        try {
            if ($this->isSaving) {
                return;
            }
            $this->isSaving = true;
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
                $rules = [
                    'taxrateid' => 'required|integer',
                    'reverse_charge' => 'boolean',
                    'invoiceDate' => 'required|date',
                    'invoiceNumber' => 'required|string|max:100',
                    'condition_id' => 'required|integer',
                    'periodfrom' => 'nullable|date|before_or_equal:periodto',
                    'periodto' => 'nullable|date|after_or_equal:periodfrom',
                ];
                $this->validate($rules);
                \Log::info('Validierung erfolgreich');
            } catch (ValidationException $e) {
                $firstError = $e->validator->errors()->first();
                \Log::error('Validierung fehlgeschlagen: ' . $firstError);
                $this->message = $firstError;
                $this->dispatch('notify', message: $this->message, type: 'error');
                // Bei ungültigem Zeitraum (Bis vor Von oder Von nach Bis): nicht speichern, ursprüngliche Datumsfelder wiederherstellen
                $this->periodfrom = $this->details->periodfrom ? Carbon::parse($this->details->periodfrom)->format('Y-m-d') : '';
                $this->periodto = $this->details->periodto ? Carbon::parse($this->details->periodto)->format('Y-m-d') : '';
                return;
            } catch (\Exception $e) {
                \Log::error('Validierung fehlgeschlagen: ' . $e->getMessage());
                $this->message = 'Validierung fehlgeschlagen: ' . $e->getMessage();
                $this->dispatch('notify', message: $this->message, type: 'error');
                $this->periodfrom = $this->details->periodfrom ? Carbon::parse($this->details->periodfrom)->format('Y-m-d') : '';
                $this->periodto = $this->details->periodto ? Carbon::parse($this->details->periodto)->format('Y-m-d') : '';
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
            // Wenn Rechnung bereits bezahlt ist und kein Entsperr-Recht, keine Änderungen erlauben
            if ((int)($invoice->status ?? 0) === 4 && !Auth::user()->hasPermission('unlock_invoices')) {
                $this->message = 'Diese Rechnung ist bezahlt. Ihnen fehlt die Berechtigung zur Bearbeitung.';
                return;
            }
            
            \Log::info('Vor dem Update:', [
                'alte_condition_id' => $invoice->condition_id,
                'neue_condition_id' => $this->condition_id,
                'alte_reverse_charge' => $invoice->reverse_charge,
                'neue_reverse_charge' => $this->reverse_charge,
            ]);
            
            $invoice->tax_id = $this->taxrateid;
            $invoice->reverse_charge = (bool) $this->reverse_charge;
            $invoice->date = $this->invoiceDate;
            
            // Stelle sicher, dass das Präfix vorhanden ist
            $invoiceNumber = $this->invoiceNumber;
            
            // Hole das Präfix aus der Client-Version (versioniert)
            $invoiceClient = null;
            if ($invoice->client_version_id) {
                $invoiceClient = Clients::find($invoice->client_version_id);
            }
            
            // Fallback: Aktuelle aktive Client-Version
            if (!$invoiceClient) {
                $invoiceClient = $this->client;
            }
            
            // Hole Präfix aus Client (versioniert) oder ClientSettings
            $originalClientId = ($invoiceClient ? ($invoiceClient->parent_client_id ?? $invoiceClient->id) : null) ?? $user->client_id;
            $clientSettings = \App\Models\ClientSettings::where('client_id', $originalClientId)->first();
            
            $invoicePrefix = null;
            if ($invoiceClient) {
                $invoicePrefix = $invoiceClient->invoice_prefix ?? null;
            }
            if (empty($invoicePrefix) && $clientSettings) {
                $invoicePrefix = $clientSettings->invoice_prefix ?? null;
            }
            
            // Füge Präfix hinzu, falls es fehlt und ein Präfix vorhanden ist
            if (!empty($invoicePrefix) && !empty($invoiceNumber)) {
                // Prüfe, ob die Nummer bereits das Präfix hat
                if (strpos($invoiceNumber, $invoicePrefix) !== 0) {
                    // Präfix fehlt, füge es hinzu
                    $invoiceNumber = $invoicePrefix . $invoiceNumber;
                }
            }
            
            $invoice->number = $invoiceNumber;
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
            $this->dispatch('notify', message: $this->message, type: 'success');
            // Browser-Event, damit z. B. der Header die neue Rechnungsnummer sofort anzeigt
            $this->dispatch('invoiceNumberChanged', number: $this->invoiceNumber);
            
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
            $this->dispatch('notify', message: $this->message, type: 'error');
        } finally {
            $this->isSaving = false;
        }
    }

    // Auto-Save nach debounce-Änderungen der relevanten Felder
    public function updated($propertyName, $value = null)
    {
        if ($this->isSaving) {
            return;
        }
        $autoSaveFields = [
            'taxrateid', 'invoiceDate', 'invoiceNumber', 'periodfrom', 'periodto', 'condition_id'
        ];
        if (in_array($propertyName, $autoSaveFields, true)) {
            $this->lastChangedProperty = $propertyName;
            $this->updateInvoiceDetails();
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
