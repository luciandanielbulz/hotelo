<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use App\Models\Offerpositions;

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
        $this->positions = Offerpositions::join('units','offerpositions.unit_id','=','units.id')
        ->where('offerpositions.offer_id', $this->offerId)
        ->select('units.unitdesignation as unit_desigtnation', 'offerpositions.*', 'units.unitdesignation as unit_name')
        ->orderBy('sequence','asc')
        ->get();
        //dd($this->positions);
    }

    public function deletePosition($positionId)
    {
        //dd($positionId);
        // Prüfen, ob die Position existiert
        $position = Offerpositions::find($positionId);

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


        Offerpositions::create([
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

        Offerpositions::create([
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

    public function movePositionUp($positionId)
    {
        try {
            $position = Offerpositions::find($positionId);
            if (!$position || $position->offer_id != $this->offerId) {
                return;
            }
            if ($position->sequence === null) {
                return;
            }

            // Finde die Position mit der nächstniedrigeren sequence
            $previousPosition = Offerpositions::where('offer_id', $this->offerId)
                ->where('sequence', '<', $position->sequence)
                ->orderBy('sequence', 'desc')
                ->first();

            if ($previousPosition) {
                // Tausche die sequence-Werte
                $tempSequence = $position->sequence;
                $position->sequence = $previousPosition->sequence;
                $previousPosition->sequence = $tempSequence;
                
                $position->save();
                $previousPosition->save();
            }

            // Positionen neu laden
            $this->loadPositions();
        } catch (\Exception $e) {
            \Log::error('Fehler beim Verschieben nach oben: ' . $e->getMessage());
        }
    }

    public function movePositionDown($positionId)
    {
        try {
            $position = Offerpositions::find($positionId);
            if (!$position || $position->offer_id != $this->offerId) {
                return;
            }
            if ($position->sequence === null) {
                return;
            }

            // Finde die Position mit der nächsthöheren sequence
            $nextPosition = Offerpositions::where('offer_id', $this->offerId)
                ->where('sequence', '>', $position->sequence)
                ->orderBy('sequence', 'asc')
                ->first();

            if ($nextPosition) {
                // Tausche die sequence-Werte
                $tempSequence = $position->sequence;
                $position->sequence = $nextPosition->sequence;
                $nextPosition->sequence = $tempSequence;
                
                $position->save();
                $nextPosition->save();
            }

            // Positionen neu laden
            $this->loadPositions();
        } catch (\Exception $e) {
            \Log::error('Fehler beim Verschieben nach unten: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.offer.offerpositions-table');
    }
}
