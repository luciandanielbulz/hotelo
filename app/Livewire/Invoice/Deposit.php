<?php

namespace App\Livewire\Invoice;

use Livewire\Component;
use App\Models\Invoices;
use Illuminate\Support\Facades\DB;

class Deposit extends Component
{
    public $invoiceId;
    public $depositamount;
    public $details;

    public $total_price;
    public function mount($invoiceId)
    {

        $this->invoiceId = $invoiceId;
        $this->loadData($invoiceId);
    }

    public function loadData($invoiceId)
    {
        $this->details = Invoices::join('taxrates', 'invoices.tax_id','=','taxrates.id')
            ->where('invoices.id','=', $invoiceId)
            ->select('invoices.*', 'taxrates.*')
            ->first();

        $this->total_price = Invoices::join('invoicepositions', 'invoices.id', '=', 'invoicepositions.invoice_id')
        ->where('invoicepositions.invoice_id', '=', $invoiceId)
        ->select(DB::raw('SUM(invoicepositions.amount * invoicepositions.price) as total_price'))
        ->first();


        $this->depositamount = $this->details->depositamount;
        //dd($this->depositamount);
    }

    public function updateDepositAmount(){

        dd("test");
        $this->validate([
            'depositamount' => 'nullable|numeric|min:0',
        ]);
        dd("bis dahin");
        try {
            // Beispiel: Update in der Tabelle "invoices" (Rechnungen)
            \DB::table('invoices') // oder $yourModelInstance->save()
                ->where('id', $this->invoiceId) // falls du eine spezifische Rechnung updaten möchtest
                ->update([
                    'depositamount' => $this->depositamount,
                    'updated_at' => now(), // optional: setzt das Änderungsdatum
                ]);

            // Erfolgsnachricht setzen
            $this->dispatch('comment-updated', [
                'message' => 'Zusätzliche Informationen erfolgreich aktualisiert.'
            ]);

            $this->loadData($this->invoiceId);

        } catch (\Exception $e) {
            dd($e);
            session()->flash('error', 'Ein Fehler ist aufgetreten: ' . $e->getMessage());
        }

    }
    public function render()
    {


        //dd($this->details);
        return view('livewire.invoice.deposit', [
            'details' => $this->details, // Überprüfen, ob $details gefüllt ist
        ]);
    }
}
