<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\OfferPositions;

class OfferpositionsTable extends Component
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
        ->orderBy('sequence','asc')
        ->select('units.unitdesignation as unit_desigtnation', 'offerpositions.*', 'units.unitdesignation as unit_name')
        ->get();
        //dd($this->positions);
    }

    public function deletePosition($positionId)
    {
        dd($positionId);
        // Prüfen, ob die Position existiert
        $position = OfferPositions::find($positionId);

        if ($position) {
            $position->delete(); // Datensatz löschen
        }

        // Positionen neu laden
        $this->loadPositions();
    }

    public function addPosition()
    {
        $highestseqnumber = Offerpositions::where('offer_id', $this->offerId)
            ->max('sequence') ?? 0;

        $highestseqnumber = $highestseqnumber + 2;


        OfferPositions::create([
            'offer_id' => $this->offerId,
            'designation' => "Beschreibung",
            'sequence' => $highestseqnumber,
            'details' => "",
            'unit_id' => 1,
            'amount' => 1,
            'price' => 0,
            'positiontext' => 0
        ]);

        // Eingabefelder zurücksetzen
        //$this->reset(['description', 'quantity', 'price']);

        // Positionen neu laden
        $this->loadPositions();
    }

    public function addTextPosition()
    {
        $highestseqnumber = Offerpositions::where('offer_id', $this->offerId)
            ->max('sequence') ?? 0;

        $highestseqnumber = $highestseqnumber + 2;

        OfferPositions::create([
            'offer_id' => $this->offerId,
            'sequence' => $highestseqnumber,
            'designation' => "",
            'details' => "Beschreibung",
            'unit_id' => 1,
            'amount' => 1,
            'price' => 0,
            'positiontext' => 1,
        ]);

        // Eingabefelder zurücksetzen
        //$this->reset(['description', 'quantity', 'price']);

        // Positionen neu laden
        $this->loadPositions();
    }

    public function render()
    {
        return view('livewire.offerpositions-table');
    }
}
