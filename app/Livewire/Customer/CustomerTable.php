<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Pagination\Paginator;

class CustomerTable extends Component
{
    use WithPagination;

    public $perPage = 12;
    public $search = '';
    public $viewMode = 'table'; // 'cards' oder 'table'
    public $sortBy = 'newest'; // 'newest', 'oldest', 'name', 'company'

    public function boot()
    {
        Paginator::useTailwind();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
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

        $query = Customer::where('client_id', $clientId)
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('customername', 'like', "%$search%")
                        ->orWhere('companyname', 'like', "%$search%")
                        ->orWhere('address', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('postalcode', 'like', "%$search%")
                        ->orWhere('location', 'like', "%$search%")
                        ->orWhere('customer_number', 'like', "%$search%");
                });
            });

        // Sortierung anwenden
        switch ($this->sortBy) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name':
                $query->orderBy('customername', 'asc');
                break;
            case 'company':
                $query->orderBy('companyname', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $customers = $query->paginate($this->perPage);

        return view('livewire.customer.customer-table', [
            'customers' => $customers,
        ]);
    }
} 