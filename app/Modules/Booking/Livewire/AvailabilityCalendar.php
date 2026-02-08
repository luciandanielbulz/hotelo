<?php

namespace App\Modules\Booking\Livewire;

use App\Modules\Booking\Models\Room;
use App\Modules\Booking\Models\Reservation;
use Livewire\Component;
use Carbon\Carbon;

class AvailabilityCalendar extends Component
{
    public $roomId;
    public $currentMonth;
    public $room;

    public function mount($roomId)
    {
        $this->roomId = $roomId;
        $this->room = Room::find($roomId);
        $this->currentMonth = Carbon::now()->format('Y-m');
    }

    public function getAvailabilityDataProperty()
    {
        if (!$this->room) {
            return [];
        }

        $start = Carbon::parse($this->currentMonth . '-01')->startOfMonth();
        $end = $start->copy()->endOfMonth();

        // Get all reservations for this room in the month
        $reservations = Reservation::where('room_id', $this->roomId)
            ->where('status', '!=', 'cancelled')
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('check_in', [$start, $end])
                  ->orWhereBetween('check_out', [$start, $end])
                  ->orWhere(function($query) use ($start, $end) {
                      $query->where('check_in', '<=', $start)
                            ->where('check_out', '>=', $end);
                  });
            })
            ->get();

        $availability = [];
        $current = $start->copy();

        while ($current <= $end) {
            $isBooked = $reservations->contains(function($reservation) use ($current) {
                return $current->between($reservation->check_in, $reservation->check_out->copy()->subDay());
            });

            $availability[$current->format('Y-m-d')] = [
                'date' => $current->copy(),
                'available' => !$isBooked,
                'isPast' => $current->isPast(),
            ];

            $current->addDay();
        }

        return $availability;
    }

    public function previousMonth()
    {
        $this->currentMonth = Carbon::parse($this->currentMonth . '-01')->subMonth()->format('Y-m');
    }

    public function nextMonth()
    {
        $this->currentMonth = Carbon::parse($this->currentMonth . '-01')->addMonth()->format('Y-m');
    }

    public function render()
    {
        return view('booking::livewire.availability-calendar', [
            'availability' => $this->availabilityData,
        ]);
    }
}
