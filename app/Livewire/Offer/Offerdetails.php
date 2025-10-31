<?php

namespace App\Livewire\Offer;
use App\Models\Offers;

use Livewire\Component;

class Offerdetails extends Component
{
    public $offerId; // Angebot ID

    public $details;
    public $tax_id;
    public $date;
    public $number;
    public $message;


    public $taxrateid;
    public $offerDate;
    public $offerNumber;
    
    // Test-Funktion für Debugging
    public function testFunction()
    {
        $this->message = 'TEST: Button funktioniert! Livewire ist aktiv.';
        \Log::info('=== TEST FUNKTION AUFGERUFEN ===', []);
    }


    public function mount($offerId)
    {
        $this->offerId = $offerId;
        $this->loadData($offerId);
    }

    public function loadData($offerId)
    {
        $this->details = Offers::findOrFail($offerId);

        $this->taxrateid = $this->details->tax_id;
        $this->offerDate = $this->details->date ? $this->details->date->format('Y-m-d') : '';
        $this->offerNumber = $this->details->number;
    }

    public function updateDetails()
    {
        try {
            // Debug Message sofort setzen um zu sehen ob die Funktion überhaupt aufgerufen wird
            $this->message = 'Funktion wurde aufgerufen - Verarbeitung läuft...';
            
            
            \Log::info('=== ANGEBOT updateDetails gestartet ===', [
                'offerId' => $this->offerId,
                'taxrateid' => $this->taxrateid,
                'offerDate' => $this->offerDate,
                'offerNumber' => $this->offerNumber,
                'all_properties' => [
                    'offerId' => $this->offerId,
                    'taxrateid' => $this->taxrateid,
                    'offerDate' => $this->offerDate,
                    'offerNumber' => $this->offerNumber,
                    'message' => $this->message,
                ]
            ]);

            \Log::info('=== ANGEBOT vor Validierung ===', [
                'taxrateid' => $this->taxrateid,
                'taxrateid_type' => gettype($this->taxrateid),
                'offerDate' => $this->offerDate,
                'offerDate_type' => gettype($this->offerDate),
                'offerNumber' => $this->offerNumber,
                'offerNumber_type' => gettype($this->offerNumber),
            ]);

            $this->validate([
                'taxrateid' => 'required|integer',
                'offerDate' => 'required|date',
                'offerNumber' => 'required|numeric',
            ]);
            
            \Log::info('=== ANGEBOT Validierung erfolgreich ===', []);

            $offer = Offers::findOrFail($this->offerId);
            
            \Log::info('Vor dem Update:', [
                'alte_nummer' => $offer->number,
                'neue_nummer' => $this->offerNumber,
            ]);
            
            $offer->tax_id = $this->taxrateid;
            $offer->date = $this->offerDate;
            $offer->number = $this->offerNumber;
            $saved = $offer->save();
            
            \Log::info('Nach dem Update:', [
                'gespeichert' => $saved,
                'nummer' => $offer->number,
            ]);

            $this->message = 'Details erfolgreich aktualisiert.';
            $this->dispatch('notify', message: $this->message, type: 'success');
            
            // Event dispatchen, um die Seite zu aktualisieren (da Zusammenfassung im Blade-Template berechnet wird)
            $this->dispatch('updateOfferSummary');
            
            $this->loadData($this->offerId);
            
        } catch (\Exception $e) {
            \Log::error('Fehler beim Speichern der Offer-Details: ' . $e->getMessage(), [
                'exception' => $e,
                'offerId' => $this->offerId,
                'offerNumber' => $this->offerNumber,
            ]);
            
            $this->message = 'Fehler beim Speichern: ' . $e->getMessage();
            $this->dispatch('notify', message: $this->message, type: 'error');
        }
    }

    // Auto-Save: Nur Steuersatz separat speichern, ohne andere Felder zu verlangen
    public function updatedTaxrateid($value)
    {
        try {
            $this->validate([
                'taxrateid' => 'required|integer',
            ]);

            $offer = Offers::findOrFail($this->offerId);
            $offer->tax_id = (int) $this->taxrateid;
            $offer->save();

            $this->message = 'Steuersatz gespeichert.';
            $this->dispatch('notify', message: $this->message, type: 'success');
        } catch (\Exception $e) {
            $this->message = 'Fehler beim Speichern des Steuersatzes: ' . $e->getMessage();
            $this->dispatch('notify', message: $this->message, type: 'error');
        }
    }

    public function updatedOfferDate($value)
    {
        try {
            $this->validate([
                'offerDate' => 'required|date',
            ]);
            $offer = Offers::findOrFail($this->offerId);
            $offer->date = $this->offerDate; // Y-m-d
            $offer->save();
            $this->message = 'Datum gespeichert.';
            $this->dispatch('notify', message: $this->message, type: 'success');
        } catch (\Exception $e) {
            $this->message = 'Fehler beim Speichern des Datums: ' . $e->getMessage();
            $this->dispatch('notify', message: $this->message, type: 'error');
        }
    }

    public function updatedOfferNumber($value)
    {
        try {
            $this->validate([
                'offerNumber' => 'required|numeric',
            ]);
            $offer = Offers::findOrFail($this->offerId);
            $offer->number = $this->offerNumber;
            $offer->save();
            $this->message = 'Nummer gespeichert.';
            $this->dispatch('notify', message: $this->message, type: 'success');
            $this->dispatch('updateOfferSummary');
        } catch (\Exception $e) {
            $this->message = 'Fehler beim Speichern der Nummer: ' . $e->getMessage();
            $this->dispatch('notify', message: $this->message, type: 'error');
        }
    }

    // Öffentliche Methoden für direkte Aufrufe aus dem Template
    public function saveOfferDate($value)
    {
        $this->offerDate = $value;
        $this->updatedOfferDate($value);
    }

    public function saveTaxrate($value)
    {
        $this->taxrateid = (int) $value;
        $this->updatedTaxrateid($value);
    }

    public function render()
    {
        \Log::info('Rendering details:', ['details' => $this->details]);

        return view('livewire.offer.offerdetails', [
            'details' => $this->details, // Überprüfen, ob $details gefüllt ist
        ]);
    }
}
