<?php

namespace App\Livewire\InvoiceUpload;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InvoiceUpload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceUploadTable extends Component
{
    use WithPagination;

    public $search = '';
    public $dateFrom = '';
    public $dateTo = '';
    public $confirmingDeletion = false;
    public $itemToDelete = null;

    public function mount()
    {
        $this->dateFrom = now()->startOfYear()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function clearDateFilter()
    {
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->itemToDelete = $id;
        $this->confirmingDeletion = true;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->itemToDelete = null;
    }

    public function deleteInvoice()
    {
        if (!$this->itemToDelete) {
            return;
        }

        $user = Auth::user();
        $clientId = $user->client_id;

        // Prüfen ob Rechnung zum Client gehört
        $invoice = InvoiceUpload::where('client_id', $clientId)
            ->where('id', $this->itemToDelete)
            ->first();

        if (!$invoice) {
            session()->flash('error', 'Sie haben keine Berechtigung, diese Rechnung zu löschen.');
            return;
        }

        try {
            // Physische Datei löschen, falls vorhanden
            if ($invoice->filepath && Storage::exists($invoice->filepath)) {
                Storage::delete($invoice->filepath);
            }

            // Datensatz aus der Datenbank löschen
            $invoice->delete();

            session()->flash('success', 'Rechnung erfolgreich gelöscht!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Fehler beim Löschen der Rechnung: ' . $e->getMessage());
        }

        $this->confirmingDeletion = false;
        $this->itemToDelete = null;
    }

    public function render()
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        $query = InvoiceUpload::where('client_id', $clientId)
                              ->with('currency') // Laden der Währungsbeziehung
                              ->orderBy('invoice_date', 'desc');

        // Falls ein Suchtext vorhanden ist
        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('invoice_number', 'LIKE', "%{$this->search}%")
                  ->orWhere('description', 'LIKE', "%{$this->search}%")
                  ->orWhere('invoice_vendor', 'LIKE', "%{$this->search}%")
                  ->orWhere('amount', 'LIKE', "%{$this->search}%");
                
                // Nur payment_type durchsuchen wenn Spalte existiert
                if (\Schema::hasColumn('invoice_uploads', 'payment_type')) {
                    $q->orWhere('payment_type', 'LIKE', "%{$this->search}%");
                }
            });
        }

        // Datumfilter anwenden
        if (!empty($this->dateFrom)) {
            $query->whereDate('invoice_date', '>=', $this->dateFrom);
        }

        if (!empty($this->dateTo)) {
            $query->whereDate('invoice_date', '<=', $this->dateTo);
        }

        $invoiceuploads = $query->paginate(10);

        return view('livewire.invoice-upload.invoice-upload-table', [
            'invoiceuploads' => $invoiceuploads
        ]);
    }
}
