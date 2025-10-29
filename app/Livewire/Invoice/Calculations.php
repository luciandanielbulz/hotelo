<?php

namespace App\Livewire\Invoice;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Invoices;
use App\Models\Taxrates;
use App\Models\Clients;
use App\Models\User;
use App\Helpers\TemplateHelper;
use Illuminate\Support\Facades\DB;

class Calculations extends Component
{
    public $invoiceId;

    public $depositAmount;

    public $invoice;
    public $total_price;
    public $tax_rate;
    public $documentFooter;

    public function mount($invoiceId)
    {

        $this->invoiceId = $invoiceId;
        $this->loadData($invoiceId);
    }

    #[On('updateSums')]
    public function updateSums(){
        //dd('test');
        $this->loadData($this->invoiceId);
    }

    public function loadData($invoiceId)
    {
        // Lade die Rechnung mit den zugehörigen Positionen
        $invoice = Invoices::with(['invoicePositions'])->find($invoiceId);

        $this->tax_rate = Taxrates::join('invoices', 'invoices.tax_id', '=', 'taxrates.id')
            ->where('invoices.id', '=', $invoiceId)
            ->value('taxrates.taxrate');


        //dd($tax_rate);
        // Berechne den Gesamtpreis in PHP
        $this->total_price = $invoice->invoicePositions->sum(function ($position) {
            return $position->amount * $position->price;
        });

        // Weitere Daten laden
        $this->invoice = $invoice;  // Hier kannst du das komplette Invoice-Objekt weitergeben
        $this->depositAmount = $invoice->depositamount;

        // Dokument-Fußzeile: bevorzugt aus der Rechnung, sonst aus der Client-Version
        $this->documentFooter = null;
        if ($invoice && !empty($invoice->document_footer)) {
            $this->documentFooter = $invoice->document_footer;
        } elseif ($invoice && $invoice->client_version_id) {
            $clientVersion = Clients::find($invoice->client_version_id);
            if ($clientVersion) {
                $this->documentFooter = $clientVersion->document_footer;
            }
        }

        // Platzhalter verarbeiten
        if (!empty($this->documentFooter)) {
            $creatorFullName = null;
            if (!empty($invoice->created_by)) {
                $creator = User::find($invoice->created_by);
                if ($creator) {
                    $creatorFullName = trim(($creator->name ?? '') . ' ' . ($creator->lastname ?? ''));
                }
            }
            $authUser = auth()->user();
            $authFullName = $authUser ? trim(($authUser->name ?? '') . ' ' . ($authUser->lastname ?? '')) : '';
            $variables = [
                '{creator}' => $creatorFullName ?: $authFullName,
            ];
            $this->documentFooter = TemplateHelper::replacePlaceholders($this->documentFooter, $variables);
        }

        //dd($this->total_price);
    }




    public function render()
    {
        return view('livewire.invoice.calculations');
    }
}
