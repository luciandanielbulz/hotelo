<?php

namespace App\Livewire\OutgoingEmail;

use App\Models\OutgoingEmail;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use DateTime;
use DateTimeZone;

class OutgoingEmailTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'newest';
    public $viewMode = 'table';
    public $typeFilter = 'all'; // all, invoice, offer

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function render()
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        $search = $this->search;

        // Base query mit Join zu customers
        $query = OutgoingEmail::join('customers', 'customers.id', '=', 'outgoingemails.customer_id')
            ->select('customers.*', 'outgoingemails.*', 'customers.customername', 'customers.companyname')
            ->where('outgoingemails.client_id', '=', $clientId);

        // Suchfilter anwenden
        $query->when($search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('customers.customername', 'like', "%$search%")
                    ->orWhere('customers.companyname', 'like', "%$search%")
                    ->orWhere('outgoingemails.objectnumber', 'like', "%$search%")
                    ->orWhere('outgoingemails.getteremail', 'like', "%$search%");
            });
        });

        // Typ-Filter anwenden
        if ($this->typeFilter !== 'all') {
            $typeValue = $this->typeFilter === 'invoice' ? 1 : 2;
            $query->where('outgoingemails.type', $typeValue);
        }

        // Sortierung anwenden
        switch ($this->sortBy) {
            case 'oldest':
                $query->orderBy('outgoingemails.sentdate', 'asc');
                break;
            case 'customer':
                $query->orderBy('customers.customername', 'asc');
                break;
            case 'type':
                $query->orderBy('outgoingemails.type', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('outgoingemails.sentdate', 'desc');
                break;
        }

        $outgoingEmails = $query->paginate(18);

        return view('livewire.outgoing-email.outgoing-email-table', [
            'outgoingEmails' => $outgoingEmails
        ]);
    }
}