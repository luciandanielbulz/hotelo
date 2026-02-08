<?php

namespace App\Modules\Booking\Services;

use App\Modules\Booking\Models\Reservation;
use Illuminate\Support\Facades\Log;

/**
 * Service layer for integrating Booking module with QuickBill billing system
 * 
 * This service acts as the ONLY interface between Booking and Billing modules.
 * It ensures that Booking never directly modifies billing logic.
 */
class BookingBillingService
{
    /**
     * Create an invoice for a reservation
     * 
     * @param Reservation $reservation
     * @return array|null Returns invoice data or null if billing is not available
     */
    public function createInvoiceForReservation(Reservation $reservation): ?array
    {
        try {
            // Check if billing module is available
            if (!$this->isBillingAvailable()) {
                Log::info('Billing module not available, skipping invoice creation', [
                    'reservation_id' => $reservation->id,
                ]);
                return null;
            }

            // Get customer or create if needed
            $customer = $this->getOrCreateCustomer($reservation);

            if (!$customer) {
                Log::error('Could not get or create customer for reservation', [
                    'reservation_id' => $reservation->id,
                ]);
                return null;
            }

            // Create invoice using billing service
            $invoiceData = [
                'customer_id' => $customer['id'],
                'description' => "Zimmerreservierung: {$reservation->room->name}",
                'positions' => [
                    [
                        'description' => "Zimmerreservierung {$reservation->reservation_number}",
                        'quantity' => $reservation->nights,
                        'price' => $reservation->room->price_per_night,
                        'check_in' => $reservation->check_in->format('Y-m-d'),
                        'check_out' => $reservation->check_out->format('Y-m-d'),
                    ],
                ],
                'total_amount' => $reservation->total_amount,
                'reference' => $reservation->reservation_number,
            ];

            return $this->createInvoice($invoiceData);

        } catch (\Exception $e) {
            Log::error('Error creating invoice for reservation', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Check if billing module is available
     */
    protected function isBillingAvailable(): bool
    {
        // Check if billing models exist
        return class_exists(\App\Models\Customer::class) 
            && class_exists(\App\Models\Invoice::class);
    }

    /**
     * Get or create customer from reservation data
     */
    protected function getOrCreateCustomer(Reservation $reservation): ?array
    {
        if (!class_exists(\App\Models\Customer::class)) {
            return null;
        }

        $user = auth()->user();
        $clientId = $user ? $user->client_id : null;

        if (!$clientId) {
            return null;
        }

        // Try to find existing customer by email
        $customer = \App\Models\Customer::where('client_id', $clientId)
            ->where('email', $reservation->guest_email)
            ->first();

        if ($customer) {
            return [
                'id' => $customer->id,
                'name' => $customer->customername ?? $customer->companyname,
            ];
        }

        // Create new customer
        try {
            $customer = \App\Models\Customer::create([
                'client_id' => $clientId,
                'customername' => $reservation->guest_name,
                'email' => $reservation->guest_email,
                'phone' => $reservation->guest_phone,
                // Add other required fields with defaults
                'address' => '',
                'postalcode' => '',
                'location' => '',
                'tax_id' => 1, // Default tax rate
                'condition_id' => 1, // Default condition
                'salutation_id' => 1, // Default salutation
            ]);

            return [
                'id' => $customer->id,
                'name' => $customer->customername,
            ];
        } catch (\Exception $e) {
            Log::error('Error creating customer for reservation', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Create invoice using billing system
     */
    protected function createInvoice(array $invoiceData): ?array
    {
        if (!class_exists(\App\Models\Invoice::class)) {
            return null;
        }

        try {
            $user = auth()->user();
            $clientId = $user ? $user->client_id : null;

            if (!$clientId) {
                return null;
            }

            // Create invoice
            $invoice = \App\Models\Invoice::create([
                'client_id' => $clientId,
                'customer_id' => $invoiceData['customer_id'],
                'description' => $invoiceData['description'],
                'reference' => $invoiceData['reference'] ?? null,
                // Add other required invoice fields
                'invoicedate' => now(),
                'condition_id' => 1, // Default condition
                'status' => 'draft',
            ]);

            // Create invoice positions if needed
            if (isset($invoiceData['positions']) && class_exists(\App\Models\Invoiceposition::class)) {
                foreach ($invoiceData['positions'] as $position) {
                    \App\Models\Invoiceposition::create([
                        'invoice_id' => $invoice->id,
                        'description' => $position['description'],
                        'quantity' => $position['quantity'] ?? 1,
                        'price' => $position['price'],
                        'tax_id' => 1, // Default tax
                    ]);
                }
            }

            return [
                'id' => $invoice->id,
                'number' => $invoice->number ?? $invoice->id,
                'total' => $invoiceData['total_amount'],
            ];

        } catch (\Exception $e) {
            Log::error('Error creating invoice', [
                'invoice_data' => $invoiceData,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Mark reservation as paid and create invoice if needed
     */
    public function markReservationAsPaid(Reservation $reservation): bool
    {
        try {
            // Update reservation status
            $reservation->update([
                'status' => 'paid',
            ]);

            // Create invoice if billing is available
            $invoice = $this->createInvoiceForReservation($reservation);

            if ($invoice) {
                Log::info('Invoice created for paid reservation', [
                    'reservation_id' => $reservation->id,
                    'invoice_id' => $invoice['id'],
                ]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Error marking reservation as paid', [
                'reservation_id' => $reservation->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
