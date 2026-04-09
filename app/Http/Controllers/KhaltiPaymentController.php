<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\NotificationService;
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
     * 
     * @param int $bookingId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function initiate($bookingId)
    {
        try {
            // Validate booking exists and belongs to authenticated user
            $booking = Booking::where('id', $bookingId)
                ->where('user_id', auth()->id())
                ->with(['user', 'property'])
                ->first();

            if (!$booking) {
                Log::warning('Unauthorized payment attempt', [
                    'booking_id' => $bookingId,
                    'user_id' => auth()->id(),
                ]);
                return redirect()->back()->with('error', 'Booking not found or you are not authorized to pay for it');
            }

            // Validate booking is not already paid
            if ($booking->hasSuccessfulPayment()) {
                return redirect()->back()->with('error', 'This booking is already paid');
            }

            // Validate booking is in valid status for payment
            if (!in_array($booking->status, ['pending', 'awaiting_payment'])) {
                return redirect()->back()->with('error', 'This booking cannot be paid at this time. Current status: ' . $booking->status);
            }

            // Validate booking has required data
            if (!$booking->user || !$booking->property || !$booking->total_rent) {
                Log::error('Invalid booking data for payment', [
                    'booking_id' => $bookingId,
                    'has_user' => !!$booking->user,
                    'has_property' => !!$booking->property,
                    'total_rent' => $booking->total_rent,
                ]);
                return redirect()->back()->with('error', 'Booking has incomplete information. Please contact support.');
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
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Payment initiation failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle successful payment callback from Khalti
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success(Request $request)
    {
        try {
            // Get pidx from query parameters
            $pidx = $request->query('pidx');

            if (!$pidx) {
                Log::warning('Payment success with missing PIDX', [
                    'user_id' => auth()->id(),
                    'query' => $request->all(),
                ]);
                return redirect()->route('user.dashboard')->with('error', 'Invalid payment reference. Please contact support.');
            }

            $paymentForNotification = Payment::query()
                ->where(function ($query) use ($pidx) {
                    $query->whereJsonContains('payment_gateway_response->pidx', $pidx)
                        ->orWhere('payment_gateway_response', 'like', "%{$pidx}%")
                        ->orWhere('transaction_id', $pidx);
                })
                ->latest('id')
                ->first();

            $shouldNotify = $paymentForNotification?->payment_status === 'pending';

            // Verify payment with Khalti
            $result = $this->khaltiService->verifyPayment($pidx);

            if ($result['success'] && $result['status'] === 'completed') {
                Log::info('Payment completed successfully', [
                    'user_id' => auth()->id(),
                    'payment_id' => $result['payment_id'],
                    'booking_id' => $result['booking_id'],
                    'pidx' => $pidx,
                ]);

                // Get payment to show details
                $payment = Payment::find($result['payment_id']);

                // Access user and property through booking relationship
                if ($payment && $payment->booking) {
                    $payment->loadMissing(['booking.user', 'booking.property']);

                    Log::info('Payment booking details', [
                        'payment_id' => $payment->id,
                        'user_name' => $payment->booking->user?->name,
                        'property_name' => $payment->booking->property?->title,
                        'amount' => $payment->amount,
                    ]);

                    if ($shouldNotify) {
                        try {
                            NotificationService::sendNotification(
                                (int) $payment->booking->user_id,
                                'payment',
                                'Payment completed',
                                'Your payment has been recorded successfully.',
                                route('user.bookings.show', $payment->booking)
                            );

                            if (
                                $payment->booking->property?->owner_id
                                && (int) $payment->booking->property->owner_id !== (int) $payment->booking->user_id
                            ) {
                                NotificationService::sendNotification(
                                    (int) $payment->booking->property->owner_id,
                                    'payment',
                                    'Booking payment received',
                                    'The booking payment for your property has been completed.',
                                    route('owner.bookings.index')
                                );
                            }
                        } catch (\Throwable $notificationError) {
                            // Notification failures must not block payment completion.
                        }
                    }
                }

                return redirect()->route('user.bookings.index')
                    ->with('success', 'Payment completed successfully! Your booking is confirmed.');
            } else {
                return redirect()->route('user.bookings.index')
                    ->with('warning', $result['message'] ?? 'Payment status could not be verified. Please contact support.');
            }

        } catch (Exception $e) {
            Log::error('Payment Success Verification Error', [
                'pidx' => $pidx ?? null,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('user.bookings.index')
                ->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle failed or cancelled payment
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function failure(Request $request)
    {
        Log::warning('Payment failure or cancellation', [
            'user_id' => auth()->id(),
            'query_params' => $request->all(),
        ]);

        return redirect()->route('user.bookings.index')
            ->with('error', 'Payment was cancelled or failed. Please try again.');
    }
}
