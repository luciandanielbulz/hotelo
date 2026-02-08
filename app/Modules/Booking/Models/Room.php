<?php

namespace App\Modules\Booking\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price_per_night',
        'max_guests',
        'features',
        'amenities',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'amenities' => 'array',
        'price_per_night' => 'decimal:2',
        'max_guests' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get all reservations for this room
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Check if room is available for a date range
     * Uses date range overlap calculation, never flags
     */
    public function isAvailableForDates(\Carbon\Carbon $checkIn, \Carbon\Carbon $checkOut): bool
    {
        return !$this->reservations()
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    // Check-in date overlaps
                    $q->whereBetween('check_in', [$checkIn, $checkOut->copy()->subDay()])
                      ->orWhereBetween('check_out', [$checkIn->copy()->addDay(), $checkOut]);
                })
                ->orWhere(function ($q) use ($checkIn, $checkOut) {
                    // Reservation completely contains the requested period
                    $q->where('check_in', '<=', $checkIn)
                      ->where('check_out', '>=', $checkOut);
                })
                ->orWhere(function ($q) use ($checkIn, $checkOut) {
                    // Requested period completely contains reservation
                    $q->where('check_in', '>=', $checkIn)
                      ->where('check_out', '<=', $checkOut);
                });
            })
            ->exists();
    }

    /**
     * Get available rooms for a date range
     */
    public static function getAvailableForDates(\Carbon\Carbon $checkIn, \Carbon\Carbon $checkOut, int $guests = 1)
    {
        return static::where('is_active', true)
            ->where('max_guests', '>=', $guests)
            ->get()
            ->filter(function ($room) use ($checkIn, $checkOut) {
                return $room->isAvailableForDates($checkIn, $checkOut);
            });
    }
}
