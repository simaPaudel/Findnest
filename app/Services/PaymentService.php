<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * PaymentService
 * 
 * Handles all payment processing logic for bookings.
 * Integrates with payment gateways and manages booking confirmation flow.
 * All operations use database transactions for atomicity.
 */
class PaymentService
{
    public function __construct(
        protected BookingService $bookingService
    ) {
    }

    /**
     * Get payment requirements for a booking.
     * 
     * @param Booking $booking
     * @return array ['minimum_amount' => decimal, 'recommended_amount' => decimal, 'max_amount' => decimal]
     */
    public function getPaymentRequirements(Booking $booking): array
    {
        return [
            'minimum_amount' => $booking->advance_payment,
            'recommended_amount' => $booking->advance_payment,
            'total_amount' => $booking->total_rent,
            'currency' => 'NPR'
        ];
    }

    /**
     * Create a pending payment for a booking.
     * 
     * @param Booking $booking
     * @param array $paymentData ['amount' => decimal, 'payment_method' => string]
     * @return Payment
     * @throws Exception
     */
    public function createPendingPayment(Booking $booking, array $paymentData): Payment
    {
        return DB::transaction(function () use ($booking, $paymentData) {
            // Validate booking is in correct status
            if ($booking->status !== 'pending') {
                throw new Exception("Cannot create payment for booking with status: {$booking->status}");
            }

            // Validate amount
            if ($paymentData['amount'] < $booking->advance_payment) {
                throw new Exception(
                    "Payment amount ({$paymentData['amount']}) must be at least advance payment ({$booking->advance_payment})"
                );
            }

            // Check if payment already exists and is pending
            $pendingPayment = $booking->payments()
                ->where('payment_status', 'pending')
                ->first();

            if ($pendingPayment) {
                throw new Exception("A pending payment already exists for this booking");
            }

            // Check if payment already succeeded
            $successPayment = $booking->payments()
                ->where('payment_status', 'success')
                ->first();

            if ($successPayment) {
                throw new Exception("Booking payment already completed");
            }

            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $paymentData['amount'],
                'payment_method' => $paymentData['payment_method'] ?? 'khalti',
                'payment_status' => 'pending',
                'transaction_id' => null,
                'paid_at' => null,
            ]);

            Log::info("Payment created for booking {$booking->id}", [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'method' => $payment->payment_method,
            ]);

            return $payment;
        });
    }

    /**
     * Handle successful payment callback.
     * Confirms booking and marks payment as successful.
     * 
     * @param Payment $payment
     * @param array $gatewayData ['transaction_id' => string, 'response' => array]
     * @return Booking (confirmed)
     * @throws Exception
     */
    public function handlePaymentSuccess(Payment $payment, array $gatewayData): Booking
    {
        return DB::transaction(function () use ($payment, $gatewayData) {
            // Lock booking row to prevent concurrent updates
            $booking = Booking::lockForUpdate()->find($payment->booking_id);

            if (!$booking) {
                throw new Exception("Booking not found for payment {$payment->id}");
            }

            // Verify payment is still in pending status
            $payment->refresh();
            if ($payment->payment_status !== 'pending') {
                Log::warning("Attempted to confirm already-processed payment", [
                    'payment_id' => $payment->id,
                    'current_status' => $payment->payment_status,
                ]);
                return $booking; // Idempotent - return existing state
            }

            // Verify booking is still pending
            if ($booking->status !== 'pending') {
                throw new Exception("Booking status has changed to {$booking->status}, cannot confirm");
            }

            // Check for booking conflicts (double-check before confirming)
            $this->validateBookingAvailability($booking);

            // Update payment
            $payment->update([
                'payment_status' => 'success',
                'transaction_id' => $gatewayData['transaction_id'] ?? null,
                'payment_gateway_response' => $gatewayData['response'] ?? [],
                'paid_at' => now(),
            ]);

            // Confirm booking
            $booking->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            // Lock the booked room immediately after successful payment.
            $this->bookingService->reserveInventory($booking);

            Log::info("Payment confirmed for booking {$booking->id}", [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'transaction_id' => $payment->transaction_id,
            ]);

            return $booking;
        });
    }

    /**
     * Handle failed payment callback.
     * Records failure but keeps booking in pending state for retry.
     * 
     * @param Payment $payment
     * @param array $failureData ['reason' => string, 'response' => array]
     * @return Payment (failed)
     * @throws Exception
     */
    public function handlePaymentFailure(Payment $payment, array $failureData): Payment
    {
        return DB::transaction(function () use ($payment, $failureData) {
            // Verify payment is still pending
            $payment->refresh();
            if ($payment->payment_status !== 'pending') {
                Log::warning("Attempted to fail already-processed payment", [
                    'payment_id' => $payment->id,
                    'current_status' => $payment->payment_status,
                ]);
                return $payment; // Idempotent
            }

            // Record failure
            $payment->update([
                'payment_status' => 'failed',
                'payment_gateway_response' => array_merge(
                    $payment->payment_gateway_response ?? [],
                    $failureData
                ),
            ]);

            // Booking remains PENDING - user can retry payment
            Log::info("Payment failed for booking {$payment->booking_id}", [
                'payment_id' => $payment->id,
                'reason' => $failureData['reason'] ?? 'Unknown',
            ]);

            return $payment;
        });
    }

    /**
     * Process refund for a booking.
     * Applies refund policy based on time to check-in.
     * 
     * @param Booking $booking
     * @return array ['refund_amount' => decimal, 'refund_percentage' => int, 'reason' => string]
     * @throws Exception
     */
    public function processRefund(Booking $booking): array
    {
        return DB::transaction(function () use ($booking) {
            // Verify booking can be refunded
            if ($booking->status === 'completed') {
                throw new Exception("Cannot refund completed booking");
            }

            if ($booking->status === 'cancelled') {
                throw new Exception("Booking already cancelled");
            }

            // Get successful payment
            $payment = $booking->payments()
                ->where('payment_status', 'success')
                ->first();

            if (!$payment) {
                throw new Exception("No successful payment found for this booking");
            }

            // Calculate refund based on time to check-in
            $daysUntilCheckIn = now()->diffInDays($booking->check_in_date);
            $refundPercentage = 0;

            if ($daysUntilCheckIn >= 15) {
                $refundPercentage = 100; // Full refund
            } elseif ($daysUntilCheckIn >= 8) {
                $refundPercentage = 50; // Half refund
            }
            // else: 0% refund for <8 days

            $refundAmount = ($payment->amount * $refundPercentage) / 100;

            if ($booking->status === 'confirmed' && $booking->isRoomSpecific()) {
                $this->bookingService->releaseInventory($booking);
            }

            // Update payment
            $payment->update([
                'payment_status' => 'refunded',
                'payment_gateway_response' => array_merge(
                    $payment->payment_gateway_response ?? [],
                    [
                        'refunded_at' => now()->toDateTimeString(),
                        'refund_percentage' => $refundPercentage,
                        'refund_amount' => $refundAmount,
                    ]
                ),
            ]);

            // Cancel booking
            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            Log::info("Booking refunded {$booking->id}", [
                'payment_id' => $payment->id,
                'refund_percentage' => $refundPercentage,
                'refund_amount' => $refundAmount,
                'reason' => "Cancelled {$daysUntilCheckIn} days before check-in",
            ]);

            return [
                'refund_amount' => $refundAmount,
                'refund_percentage' => $refundPercentage,
                'reason' => match (true) {
                    $daysUntilCheckIn >= 15 => 'Full refund - cancelled 15+ days before check-in',
                    $daysUntilCheckIn >= 8 => '50% refund - cancelled 8-14 days before check-in',
                    default => 'No refund - cancelled less than 8 days before check-in',
                },
            ];
        });
    }

    /**
     * Get all pending payments that haven't been updated in X hours.
     * Used for webhook retry logic and timeout handling.
     * 
     * @param int $hoursOld How many hours old should be considered stale
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStalePendingPayments(int $hoursOld = 24)
    {
        return Payment::where('payment_status', 'pending')
            ->where('created_at', '<', now()->subHours($hoursOld))
            ->with('booking.user', 'booking.property')
            ->get();
    }

    /**
     * Validate booking is still available (no conflicting bookings).
     * Used before confirming payment to prevent overbooking.
     * 
     * @param Booking $booking
     * @throws Exception
     */
    private function validateBookingAvailability(Booking $booking): void
    {
        $query = Booking::where('property_id', $booking->property_id)
            ->where('status', 'confirmed')
            ->where('id', '!=', $booking->id)
            ->where(function ($query) use ($booking) {
                // Overlap condition: existing booking overlaps with new booking
                $query->whereBetween('check_in_date', [$booking->check_in_date, $booking->check_out_date])
                    ->orWhereBetween('check_out_date', [$booking->check_in_date, $booking->check_out_date])
                    ->orWhere(function ($q) use ($booking) {
                        $q->where('check_in_date', '<=', $booking->check_in_date)
                            ->where('check_out_date', '>=', $booking->check_out_date);
                    });
            });

        // If room-specific booking, also check room constraints
        if ($booking->room_id) {
            $query->where(function ($q) use ($booking) {
                // Either booking is full property or same room
                $q->whereNull('room_id')
                    ->orWhere('room_id', $booking->room_id);
            });
        }

        if ($query->exists()) {
            throw new Exception('This property/room is not available for the selected dates');
        }
    }
}
