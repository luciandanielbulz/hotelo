<?php

namespace App\Modules\Booking\Livewire;

use App\Modules\Booking\Models\Room;
use App\Modules\Booking\Models\Reservation;
use Livewire\Component;
use Carbon\Carbon;

class BookingDashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_rooms' => Room::count(),
            'active_rooms' => Room::where('is_active', true)->count(),
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'confirmed_reservations' => Reservation::where('status', 'confirmed')->count(),
            'today_checkins' => Reservation::where('check_in', Carbon::today())
                ->where('status', '!=', 'cancelled')
                ->count(),
            'today_checkouts' => Reservation::where('check_out', Carbon::today())
                ->where('status', '!=', 'cancelled')
                ->count(),
            'upcoming_reservations' => Reservation::where('check_in', '>=', Carbon::today())
                ->where('status', '!=', 'cancelled')
                ->count(),
        ];

        // Recent reservations
        $recentReservations = Reservation::with('room')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Upcoming reservations
        $upcomingReservations = Reservation::with('room')
            ->where('check_in', '>=', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->orderBy('check_in', 'asc')
            ->limit(10)
            ->get();

        return view('booking::livewire.booking-dashboard', [
            'stats' => $stats,
            'recentReservations' => $recentReservations,
            'upcomingReservations' => $upcomingReservations,
        ])->layout('components.layout');
    }
}
