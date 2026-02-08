<?php

namespace App\Modules\Booking\Livewire;

use App\Modules\Booking\Models\Room;
use App\Modules\Booking\Models\Reservation;
use App\Modules\Booking\Services\BookingBillingService;
use Livewire\Component;
use Carbon\Carbon;

class ReservationForm extends Component
{
    public $roomId;
    public $checkIn;
    public $checkOut;
    public $numberOfGuests = 1;
    public $guestName = '';
    public $guestEmail = '';
    public $guestPhone = '';
    public $specialRequests = '';
    
    public $room;
    public $totalAmount = 0;
    public $nights = 0;
    public $isAvailable = false;

    protected $rules = [
        'roomId' => 'required|exists:rooms,id',
        'checkIn' => 'required|date|after_or_equal:today',
        'checkOut' => 'required|date|after:checkIn',
        'numberOfGuests' => 'required|integer|min:1',
        'guestName' => 'required|string|max:255',
        'guestEmail' => 'required|email|max:255',
        'guestPhone' => 'nullable|string|max:50',
        'specialRequests' => 'nullable|string|max:1000',
    ];

    public function mount($roomId = null)
    {
        if ($roomId) {
            $this->roomId = $roomId;
            $this->loadRoom();
        }
        
        $this->checkIn = Carbon::today()->format('Y-m-d');
        $this->checkOut = Carbon::today()->addDay()->format('Y-m-d');
    }

    public function updatedRoomId()
    {
        $this->loadRoom();
        $this->checkAvailability();
    }

    public function updatedCheckIn()
    {
        $this->checkAvailability();
        $this->calculateTotal();
    }

    public function updatedCheckOut()
    {
        $this->checkAvailability();
        $this->calculateTotal();
    }

    public function updatedNumberOfGuests()
    {
        $this->checkAvailability();
    }

    public function loadRoom()
    {
        if ($this->roomId) {
            $this->room = Room::find($this->roomId);
            if ($this->room && $this->numberOfGuests > $this->room->max_guests) {
                $this->numberOfGuests = $this->room->max_guests;
            }
        }
    }

    public function checkAvailability()
    {
        if (!$this->room || !$this->checkIn || !$this->checkOut) {
            $this->isAvailable = false;
            return;
        }

        $checkIn = Carbon::parse($this->checkIn);
        $checkOut = Carbon::parse($this->checkOut);

        $this->isAvailable = $this->room->isAvailableForDates($checkIn, $checkOut) 
            && $this->room->max_guests >= $this->numberOfGuests;
    }

    public function calculateTotal()
    {
        if (!$this->room || !$this->checkIn || !$this->checkOut) {
            $this->totalAmount = 0;
            $this->nights = 0;
            return;
        }

        $checkIn = Carbon::parse($this->checkIn);
        $checkOut = Carbon::parse($this->checkOut);
        $this->nights = $checkIn->diffInDays($checkOut);
        $this->totalAmount = $this->nights * $this->room->price_per_night;
    }

    public function submit()
    {
        $this->validate();
        $this->checkAvailability();

        if (!$this->isAvailable) {
            $this->addError('availability', 'Das Zimmer ist für den gewählten Zeitraum nicht verfügbar.');
            return;
        }

        // Create reservation
        $reservation = Reservation::create([
            'room_id' => $this->roomId,
            'guest_name' => $this->guestName,
            'guest_email' => $this->guestEmail,
            'guest_phone' => $this->guestPhone,
            'check_in' => $this->checkIn,
            'check_out' => $this->checkOut,
            'number_of_guests' => $this->numberOfGuests,
            'total_amount' => $this->totalAmount,
            'special_requests' => $this->specialRequests,
            'status' => 'pending',
        ]);

        // Redirect to payment or confirmation
        return redirect()->route('booking.reservation.show', $reservation);
    }

    public function render()
    {
        $this->calculateTotal();
        
        return view('booking::livewire.reservation-form');
    }
}
