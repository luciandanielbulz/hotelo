<?php

namespace App\Modules\Booking\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'room_id',
        'reservation_number',
        'guest_name',
        'guest_email',
        'guest_phone',
        'check_in',
        'check_out',
        'number_of_guests',
        'total_amount',
        'status',
        'special_requests',
        'cancellation_reason',
        'confirmed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'number_of_guests' => 'integer',
        'total_amount' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reservation) {
            if (empty($reservation->reservation_number)) {
                $reservation->reservation_number = static::generateReservationNumber();
            }
        });
    }

    /**
     * Generate unique reservation number
     */
    protected static function generateReservationNumber(): string
    {
        do {
            $number = 'RES-' . strtoupper(Str::random(8));
        } while (static::where('reservation_number', $number)->exists());

        return $number;
    }

    /**
     * Get the room for this reservation
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get all payments for this reservation
     */
    public function payments()
    {
        return $this->hasMany(ReservationPayment::class);
    }

    /**
     * Check if reservation is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid' || $this->status === 'confirmed';
    }

    /**
     * Check if reservation can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'paid', 'confirmed']);
    }

    /**
     * Calculate number of nights
     */
    public function getNightsAttribute(): int
    {
        return $this->check_in->diffInDays($this->check_out);
    }

    /**
     * Scope: Active reservations (not cancelled)
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }

    /**
     * Scope: Pending reservations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Confirmed reservations
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
}
