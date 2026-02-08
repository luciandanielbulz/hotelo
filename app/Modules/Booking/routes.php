<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Booking\Livewire\RoomList;
use App\Modules\Booking\Livewire\ReservationForm;
use App\Modules\Booking\Livewire\ReservationList;
use App\Modules\Booking\Livewire\AvailabilityCalendar;
use App\Modules\Booking\Models\Reservation;

/*
|--------------------------------------------------------------------------
| Booking Module Routes
|--------------------------------------------------------------------------
|
| These routes are for the Booking module only.
| They are separate from the main application routes.
|
*/

// Public routes (for frontend booking)
Route::prefix('booking')->name('booking.')->group(function () {
    // Room listing
    Route::get('/rooms', RoomList::class)->name('rooms.index');
    
    // Reservation form
    Route::get('/reserve/{room?}', ReservationForm::class)->name('reserve');
    
    // Reservation details
    Route::get('/reservation/{reservation}', function (Reservation $reservation) {
        return view('booking::reservation.show', compact('reservation'));
    })->name('reservation.show');
});

// Admin routes (protected)
Route::middleware(['auth', 'verified'])->prefix('admin/booking')->name('admin.booking.')->group(function () {
    // Booking Dashboard
    Route::get('/', \App\Modules\Booking\Livewire\BookingDashboard::class)->name('dashboard');
    
    // Reservations management
    Route::get('/reservations', ReservationList::class)->name('reservations.index');
    
    // Availability calendar
    Route::get('/rooms/{room}/availability', AvailabilityCalendar::class)->name('rooms.availability');
    
    // Reservation actions
    Route::patch('/reservations/{reservation}/confirm', function (Reservation $reservation) {
        $reservation->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Reservierung bestätigt.');
    })->name('reservations.confirm');
    
    Route::patch('/reservations/{reservation}/cancel', function (Reservation $reservation) {
        request()->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);
        
        $reservation->update([
            'status' => 'cancelled',
            'cancellation_reason' => request('cancellation_reason'),
            'cancelled_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Reservierung storniert.');
    })->name('reservations.cancel');
});
