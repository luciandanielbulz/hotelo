<?php

namespace App\Livewire\Invoice;

use Livewire\Component;
use App\Models\Invoicepositions;

class InvoicepositionsTable extends Component
{
    public $invoiceId; // Angebot ID

    public $positions;

    public function mount($invoiceId)
    {
        $this->invoiceId = $invoiceId;
        $this->loadPositions();
    }

    public function loadPositions()
    {
        $this->positions = Invoicepositions::join('units','invoicepositions.unit_id','=','units.id')
        ->where('invoicepositions.invoice_id', $this->invoiceId)
        ->select('units.unitdesignation as unit_desigtnation', 'invoicepositions.*', 'units.unitdesignation as unit_name')
        ->orderBy('sequence')
        ->get();
        //dd($this->positions);
    }

    public function addPosition()
    {
        $highestseqnumber = Invoicepositions::where('invoice_id', $this->invoiceId)
            ->max('sequence') ?? 0;

        $highestseqnumber = $highestseqnumber + 2;

        //dd($highestseqnumber);

        $test = Invoicepositions::create([
            'invoice_id' => $this->invoiceId,
            'designation' => "Beschreibung",
            'sequence' => $highestseqnumber,
            'details' => "",
            'unit_id' => 1,
            'amount' => 1,
            'price' => 0,
            'positiontext' =>0,
        ]);

        //dd($test);
        // Eingabefelder zurücksetzen
        //$this->reset(['description', 'quantity', 'price']);

        // Positionen neu laden
        $this->loadPositions();
    }

    public function addTextPosition()
    {
        $highestseqnumber = Invoicepositions::where('invoice_id', $this->invoiceId)
            ->max('sequence') ?? 0;

        $highestseqnumber = $highestseqnumber + 2;

        Invoicepositions::create([
            'invoice_id' => $this->invoiceId,
            'designation' => "",
            'sequence' => $highestseqnumber,
            'details' => "",
            'unit_id' => 1,
            'amount' => 1,
            'price' => 0,
            'positiontext' => 1
        ]);

        // Eingabefelder zurücksetzen
        //$this->reset(['description', 'quantity', 'price']);

        // Positionen neu laden
        $this->loadPositions();
    }

    public function render()
    {
        return view('livewire.invoice.invoicepositions-table');
    }

    public function deletePosition($positionId)
    {
        //dd($positionId);
        // Prüfen, ob die Position existiert
        $position = Invoicepositions::find($positionId);

        if ($position) {
            $position->delete(); // Datensatz löschen
        }

        // Positionen neu laden
        $this->loadPositions();
    }

    public function movePositionUp($positionId)
    {
        try {
            $position = Invoicepositions::find($positionId);
            if (!$position || $position->invoice_id != $this->invoiceId) {
                return;
            }
            if ($position->sequence === null) {
                return;
            }

            // Finde die Position mit der nächstniedrigeren sequence
            $previousPosition = Invoicepositions::where('invoice_id', $this->invoiceId)
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
            $position = Invoicepositions::find($positionId);
            if (!$position || $position->invoice_id != $this->invoiceId) {
                return;
            }
            if ($position->sequence === null) {
                return;
            }

            // Finde die Position mit der nächsthöheren sequence
            $nextPosition = Invoicepositions::where('invoice_id', $this->invoiceId)
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
}
