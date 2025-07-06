<?php

namespace App\Livewire\Category;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Pagination\Paginator;

class CategoryTable extends Component
{
    use WithPagination;

    public $perPage = 15;
    public $search = '';
    public $filterActive = 'all'; // all, active, inactive

    public function boot()
    {
        Paginator::useTailwind();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterActive()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        $clientId = $user->client_id;

        $search = $this->search;
        $filterActive = $this->filterActive;

        $categories = Category::forClient($clientId)
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%");
                });
            })
            ->when($filterActive !== 'all', function ($query) use ($filterActive) {
                return $query->where('is_active', $filterActive === 'active');
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.category.category-table', [
            'categories' => $categories,
        ]);
    }
} 