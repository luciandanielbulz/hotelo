<?php

namespace App\Modules\Booking\Livewire;

use App\Modules\Booking\Models\Reservation;
use Livewire\Component;
use Livewire\WithPagination;

class ReservationList extends Component
{
    use WithPagination;

    public $statusFilter = '';
    public $search = '';

    protected $queryString = [
        'statusFilter' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    public function render()
    {
        $reservations = Reservation::query()
            ->with(['room', 'payments'])
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, function($q) {
                $q->where(function($query) {
                    $query->where('reservation_number', 'like', '%' . $this->search . '%')
                          ->orWhere('guest_name', 'like', '%' . $this->search . '%')
                          ->orWhere('guest_email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('booking::livewire.reservation-list', [
            'reservations' => $reservations,
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
}
