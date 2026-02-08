# Booking Module

A separate, optional module for handling room reservations and bookings in the QuickBill application.

## Architecture

This module is **completely separate** from the billing system and follows strict architectural rules:

- **Location**: `app/Modules/Booking`
- **No modifications** to existing billing models or tables
- **Service layer integration**: All billing interactions go through `BookingBillingService`
- **Optional**: Can be removed without affecting the core billing system

## Database Entities

### Rooms
- Room definitions with pricing, capacity, and features
- Availability calculated by date range overlap (never by flags)

### Reservations
- Status flow: `pending → paid → confirmed → cancelled`
- Date-based availability checking
- Automatic reservation number generation

### Reservation Payments
- Online payments only (no cash)
- Payment provider agnostic structure
- Transaction tracking

## Installation

1. Run migrations:
```bash
php artisan migrate --path=app/Modules/Booking/Migrations
```

2. The module is automatically registered via `BookingServiceProvider` in `config/app.php`

## Usage

### Public Routes
- `/booking/rooms` - Room listing
- `/booking/reserve/{room?}` - Reservation form
- `/booking/reservation/{id}` - Reservation details

### Admin Routes
- `/admin/booking/reservations` - Reservation management
- `/admin/booking/rooms/{id}/availability` - Availability calendar

## Livewire Components

- `RoomList` - Display available rooms
- `ReservationForm` - Create new reservations
- `ReservationList` - Admin reservation management
- `AvailabilityCalendar` - Room availability visualization

## Billing Integration

The `BookingBillingService` handles all interactions with the billing system:

- Creates invoices for paid reservations
- Creates customers from reservation data
- Never modifies existing billing logic directly

## Removing the Module

To remove this module:

1. Remove `BookingServiceProvider` from `config/app.php`
2. Delete `app/Modules/Booking` directory
3. Run migrations to drop tables (or keep data if needed)

## Status Flow

```
pending → paid → confirmed
   ↓         ↓
cancelled  cancelled
```

- **pending**: Initial reservation state
- **paid**: Payment completed (online only)
- **confirmed**: Reservation confirmed
- **cancelled**: Reservation cancelled (from any state)

## Availability Calculation

Availability is calculated using date range overlap:

- No flags or boolean fields
- Checks for overlapping reservations
- Considers check-in and check-out dates
- Excludes cancelled reservations
