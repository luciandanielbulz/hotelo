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
        $this->validate([
            'taxrateid' => 'required|integer',
            'offerDate' => 'required|date',
            'offerNumber' => 'required|integer',
        ]);

        $offer = Offers::findOrFail($this->offerId);
        $offer->tax_id = $this->taxrateid;
        $offer->date = $this->offerDate;
        $offer->number = $this->offerNumber;
        $offer->save();

        $this->dispatch('comment-updated', [
            'message' => 'Details erfolgreich aktualisiert.'
        ]);
        // Debugging hinzufügen
        \Log::info('Daten aktualisiert:', [
            'taxrateid' => $this->taxrateid,
            'offerDate' => $this->offerDate,
            'offerNumber' => $this->offerNumber,
        ]);

        $this->loadData($this->offerId);
    }

    public function render()
    {
        \Log::info('Rendering details:', ['details' => $this->details]);

        return view('livewire.offer.offerdetails', [
            'details' => $this->details, // Überprüfen, ob $details gefüllt ist
        ]);
    }
}
