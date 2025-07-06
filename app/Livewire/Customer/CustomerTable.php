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

    public $perPage = 15;
    public $search = '';

    public function boot()
    {
        Paginator::useTailwind();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        $search = $this->search;

        $customers = Customer::where('client_id', $clientId)
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
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.customer.customer-table', [
            'customers' => $customers,
        ]);
    }
} 