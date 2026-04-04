<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\KhaltiPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class KhaltiPaymentController extends Controller
{
    protected $khaltiService;

    public function __construct(KhaltiPaymentService $khaltiService)
    {
        $this->khaltiService = $khaltiService;
    }

    /**
     * Initiate payment for a booking
     */
    public function initiate($bookingId)
    {
        try {
            // Get booking for logged-in user
            $booking = Booking::where('id', $bookingId)
                ->where('user_id', auth()->id())
                ->first();

            if (!$booking) {
                return redirect()->back()->with('error', 'Booking not found or unauthorized');
            }

            // Check if booking already paid
            if ($booking->payment_status === 'paid') {
                return redirect()->back()->with('error', 'This booking is already paid');
            }

            // Check if booking is in valid status for payment
            if (!in_array($booking->status, ['pending', 'awaiting_payment'])) {
                return redirect()->back()->with('error', 'This booking cannot be paid at this time');
            }

            // Call service to initiate payment
            $result = $this->khaltiService->initiatePayment($booking);

            if (!$result['success']) {
                return redirect()->back()->with('error', 'Failed to initiate payment. Please try again.');
            }

            // Redirect user to payment URL
            return redirect()->to($result['payment_url']);

        } catch (Exception $e) {
            Log::error('Payment Initiation Error', [
                'booking_id' => $bookingId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', $e->getMessage() ?? 'Payment initiation failed');
        }
    }

    /**
     * Handle successful payment callback
     */
    public function success(Request $request)
    {
        try {
            // Get pidx from query parameters
            $pidx = $request->query('pidx');

            if (!$pidx) {
                return redirect()->route('user.dashboard')->with('error', 'Invalid payment reference');
            }

            // Call service to verify payment
            $result = $this->khaltiService->verifyPayment($pidx);

            if ($result['success'] && $result['status'] === 'completed') {
                Log::info('Payment successful', [
                    'user_id' => auth()->id(),
                    'pidx' => $pidx,
                    'booking_id' => $result['booking_id'],
                ]);

                return redirect()->route('user.bookings.index')->with('success', 'Payment completed successfully! Your booking is confirmed.');
            } else {
                return redirect()->route('user.bookings.index')->with('warning', $result['message'] ?? 'Payment status could not be verified');
            }

        } catch (Exception $e) {
            Log::error('Payment Success Verification Error', [
                'pidx' => $pidx ?? null,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('user.bookings.index')->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    /**
     * Handle failed payment
     */
    public function failure(Request $request)
    {
        Log::warning('Payment failure', [
            'user_id' => auth()->id(),
            'query' => $request->all(),
        ]);

        return redirect()->route('user.bookings.index')->with('error', 'Payment was cancelled or failed. Please try again.');
    }
}
