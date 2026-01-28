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
    public $sortField = 'invoice_date';
    public $sortDirection = 'desc';

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

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->dateFrom = now()->startOfYear()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
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
            $this->dispatch('notify', message: 'Sie haben keine Berechtigung, diese Rechnung zu löschen.', type: 'error');
            return;
        }

        try {
            // Physische Datei löschen, falls vorhanden
            if ($invoice->filepath && Storage::exists($invoice->filepath)) {
                Storage::delete($invoice->filepath);
            }

            // Datensatz aus der Datenbank löschen
            $invoice->delete();

            $this->dispatch('notify', message: 'Rechnung erfolgreich gelöscht!', type: 'success');
            
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Fehler beim Löschen der Rechnung: ' . $e->getMessage(), type: 'error');
        }

        $this->confirmingDeletion = false;
        $this->itemToDelete = null;
    }

    public function render()
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        $query = InvoiceUpload::where('client_id', $clientId)
                              ->with('currency'); // Laden der Währungsbeziehung

        // Sortierung anwenden
        $query->orderBy($this->sortField, $this->sortDirection);

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
