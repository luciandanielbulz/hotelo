<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\OfferPositions;

class PositionsTable extends Component
{
    public $offerId; // Angebot ID

    public $positions;

    public function mount($offerId)
    {
        $this->offerId = $offerId;
        $this->loadPositions();
    }

    public function loadPositions()
    {
        $this->positions = Offerpositions::where('offer_id', $this->offerId)
        ->join('units','offerpositions.unit_id','=','units.id')
        ->select('units.unitdesignation as unit_desigtnation', 'offerpositions.*', 'units.unitdesignation as unit_name')
        ->get();
        //dd($this->positions);
    }

    public function addPosition()
    {


        OfferPositions::create([
            'offer_id' => $this->offerId,
            'designation' => "Beschreibung",
            'details' => "",
            'unit_id' => 1,
            'amount' => 1,
            'price' => 0,
        ]);

        // Eingabefelder zurücksetzen
        //$this->reset(['description', 'quantity', 'price']);

        // Positionen neu laden
        $this->loadPositions();
    }

    public function render()
    {
        return view('livewire.positions-table');
    }
}
