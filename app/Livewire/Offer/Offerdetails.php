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
        
        // Lade die Nummer und füge Präfix hinzu, falls es fehlt
        $offerNumber = $this->details->number;
        
        // Hole das Präfix aus der Client-Version (versioniert)
        $user = \Illuminate\Support\Facades\Auth::user();
        $offerClient = null;
        if ($this->details->client_version_id) {
            $offerClient = \App\Models\Clients::find($this->details->client_version_id);
        }
        
        // Fallback: Aktuelle aktive Client-Version
        if (!$offerClient) {
            $userClient = \App\Models\Clients::find($user->client_id);
            $offerClient = $userClient ? $userClient->getCurrentVersion() : null;
        }
        
        // Hole Präfix aus Client (versioniert) oder ClientSettings
        $originalClientId = ($offerClient ? ($offerClient->parent_client_id ?? $offerClient->id) : null) ?? $user->client_id;
        $clientSettings = \App\Models\ClientSettings::where('client_id', $originalClientId)->first();
        
        $offerPrefix = null;
        if ($offerClient) {
            $offerPrefix = $offerClient->offer_prefix ?? null;
        }
        if (empty($offerPrefix) && $clientSettings) {
            $offerPrefix = $clientSettings->offer_prefix ?? null;
        }
        
        // Füge Präfix hinzu, falls es fehlt und ein Präfix vorhanden ist
        if (!empty($offerPrefix) && !empty($offerNumber)) {
            // Prüfe, ob die Nummer bereits das Präfix hat
            if (strpos($offerNumber, $offerPrefix) !== 0) {
                // Präfix fehlt, füge es hinzu
                $offerNumber = $offerPrefix . $offerNumber;
            }
        }
        
        $this->offerNumber = $offerNumber;
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
                'offerNumber' => 'required|string|max:100',
            ]);
            
            \Log::info('=== ANGEBOT Validierung erfolgreich ===', []);

            $offer = Offers::findOrFail($this->offerId);
            
            \Log::info('Vor dem Update:', [
                'alte_nummer' => $offer->number,
                'neue_nummer' => $this->offerNumber,
            ]);
            
            $offer->tax_id = $this->taxrateid;
            $offer->date = $this->offerDate;
            
            // Stelle sicher, dass das Präfix vorhanden ist
            $offerNumber = $this->offerNumber;
            
            // Hole das Präfix aus der Client-Version (versioniert)
            $user = \Illuminate\Support\Facades\Auth::user();
            $offerClient = null;
            if ($offer->client_version_id) {
                $offerClient = \App\Models\Clients::find($offer->client_version_id);
            }
            
            // Fallback: Aktuelle aktive Client-Version
            if (!$offerClient) {
                $userClient = \App\Models\Clients::find($user->client_id);
                $offerClient = $userClient ? $userClient->getCurrentVersion() : null;
            }
            
            // Hole Präfix aus Client (versioniert) oder ClientSettings
            $originalClientId = ($offerClient ? ($offerClient->parent_client_id ?? $offerClient->id) : null) ?? $user->client_id;
            $clientSettings = \App\Models\ClientSettings::where('client_id', $originalClientId)->first();
            
            $offerPrefix = null;
            if ($offerClient) {
                $offerPrefix = $offerClient->offer_prefix ?? null;
            }
            if (empty($offerPrefix) && $clientSettings) {
                $offerPrefix = $clientSettings->offer_prefix ?? null;
            }
            
            // Füge Präfix hinzu, falls es fehlt und ein Präfix vorhanden ist
            if (!empty($offerPrefix) && !empty($offerNumber)) {
                // Prüfe, ob die Nummer bereits das Präfix hat
                if (strpos($offerNumber, $offerPrefix) !== 0) {
                    // Präfix fehlt, füge es hinzu
                    $offerNumber = $offerPrefix . $offerNumber;
                }
            }
            
            $offer->number = $offerNumber;
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
                'offerNumber' => 'required|string|max:100',
            ]);
            
            $offer = Offers::findOrFail($this->offerId);
            
            // Stelle sicher, dass das Präfix vorhanden ist
            $offerNumber = $this->offerNumber;
            
            // Hole das Präfix aus der Client-Version (versioniert)
            $user = \Illuminate\Support\Facades\Auth::user();
            $offerClient = null;
            if ($offer->client_version_id) {
                $offerClient = \App\Models\Clients::find($offer->client_version_id);
            }
            
            // Fallback: Aktuelle aktive Client-Version
            if (!$offerClient) {
                $userClient = \App\Models\Clients::find($user->client_id);
                $offerClient = $userClient ? $userClient->getCurrentVersion() : null;
            }
            
            // Hole Präfix aus Client (versioniert) oder ClientSettings
            $originalClientId = ($offerClient ? ($offerClient->parent_client_id ?? $offerClient->id) : null) ?? $user->client_id;
            $clientSettings = \App\Models\ClientSettings::where('client_id', $originalClientId)->first();
            
            $offerPrefix = null;
            if ($offerClient) {
                $offerPrefix = $offerClient->offer_prefix ?? null;
            }
            if (empty($offerPrefix) && $clientSettings) {
                $offerPrefix = $clientSettings->offer_prefix ?? null;
            }
            
            // Füge Präfix hinzu, falls es fehlt und ein Präfix vorhanden ist
            if (!empty($offerPrefix) && !empty($offerNumber)) {
                // Prüfe, ob die Nummer bereits das Präfix hat
                if (strpos($offerNumber, $offerPrefix) !== 0) {
                    // Präfix fehlt, füge es hinzu
                    $offerNumber = $offerPrefix . $offerNumber;
                }
            }
            
            $offer->number = $offerNumber;
            $offer->save();
            
            // Aktualisiere die lokale Variable
            $this->offerNumber = $offerNumber;
            
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
