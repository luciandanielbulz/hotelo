<?php

namespace App\Livewire;

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
        $this->positions = Invoicepositions::where('invoice_id', $this->invoiceId)
        ->join('units','invoicepositions.unit_id','=','units.id')
        ->select('units.unitdesignation as unit_desigtnation', 'invoicepositions.*', 'units.unitdesignation as unit_name')
        ->get();
        //dd($this->positions);
    }

    public function addPosition()
    {


        InvoicePositions::create([
            'invoice_id' => $this->invoiceId,
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

    public function addTextPosition()
    {


        InvoicePositions::create([
            'invoice_id' => $this->invoiceId,
            'designation' => "",
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
        return view('livewire.invoicepositions-table');
    }
}
