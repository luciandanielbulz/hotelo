<?php

namespace App\Modules\Booking\Livewire;

use App\Modules\Booking\Models\Room;
use Livewire\Component;
use Livewire\WithPagination;

class RoomList extends Component
{
    use WithPagination;

    public $search = '';
    public $showActiveOnly = true;

    protected $queryString = [
        'search' => ['except' => ''],
        'showActiveOnly' => ['except' => true],
    ];

    public function render()
    {
        $rooms = Room::query()
            ->when($this->showActiveOnly, fn($q) => $q->where('is_active', true))
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12);

        return view('booking::livewire.room-list', [
            'rooms' => $rooms,
        ])->layout('booking::layouts.booking');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
