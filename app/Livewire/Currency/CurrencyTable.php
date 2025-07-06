<?php

namespace App\Livewire\Currency;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Currency;
use Illuminate\Pagination\Paginator;

class CurrencyTable extends Component
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

        $currencies = Currency::where('client_id', $clientId)
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%")
                        ->orWhere('symbol', 'like', "%$search%");
                });
            })
            ->orderBy('is_default', 'desc')
            ->orderBy('code')
            ->paginate($this->perPage);

        return view('livewire.currency.currency-table', [
            'currencies' => $currencies,
        ]);
    }
} 