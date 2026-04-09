<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\InitiatePaymentRequest;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\KhaltiPaymentService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * PaymentController
 * 
 * Handles all payment-related workflows:
 * - Display payment form
 * - Initiate payment with payment gateway
 * - Handle payment callbacks/webhooks
 * - Manage payment status and retries
 */
class PaymentController extends Controller
{
    protected PaymentService $paymentService;
    protected KhaltiPaymentService $khaltiService;

    public function __construct(
        PaymentService $paymentService,
        KhaltiPaymentService $khaltiService
    ) {
        $this->paymentService = $paymentService;
        $this->khaltiService = $khaltiService;
    }

    /**
     * Display payment form for a booking.
     * Shows payment amount, methods, and initiates payment flow.
     */
    public function show(Booking $booking)
    {
        // Authorize
        $this->authorize('view', $booking);

        // Verify booking is pending
        if ($booking->status !== 'pending') {
            return redirect()->route('user.bookings.show', $booking)
                ->with('warning', "This booking cannot be paid (status: {$booking->status})");
        }

        // Check for existing successful payment
        if ($booking->payments()->where('payment_status', 'success')->exists()) {
            return redirect()->route('user.bookings.show', $booking)
                ->with('success', 'Payment already completed for this booking');
        }

        // Get payment requirements
        $requirements = $this->paymentService->getPaymentRequirements($booking);

        // Check for existing pending payment
        $pendingPayment = $booking->payments()
            ->where('payment_status', 'pending')
            ->latest()
            ->first();

        // Get failed payments to show retry message
        $failedPayments = $booking->payments()
            ->where('payment_status', 'failed')
            ->latest()
            ->limit(3)
            ->get();

        $booking->load('property', 'room');

        return view('user.payments.form', compact(
            'booking',
            'requirements',
            'pendingPayment',
            'failedPayments'
        ));
    }

    /**
     * Initiate payment with payment gateway.
     * Creates Payment record and redirects to Khalti or payment processor.
     */
    public function initiate(InitiatePaymentRequest $request, Booking $booking)
    {
        // Authorize
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        try {
            // Create pending payment
            $payment = $this->paymentService->createPendingPayment($booking, [
                'amount' => $request->validated('amount'),
                'payment_method' => $request->validated('payment_method'),
            ]);

            // Initialize payment with Khalti (or alternative gateway)
            if ($request->validated('payment_method') === 'khalti') {
                return $this->initiateKhaltiPayment($booking, $payment);
            }

            // Add support for other payment methods here
            throw new \Exception('Payment method not supported yet');
        } catch (\Exception $e) {
            Log::error("Payment initiation failed for booking {$booking->id}: {$e->getMessage()}");
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Initiate Khalti payment.
     */
    private function initiateKhaltiPayment(Booking $booking, Payment $payment)
    {
        try {
            $khaltiDetails = $this->khaltiService->initiatePayment(
                $booking,
                $payment->amount,
                route('payment.callback', ['booking' => $booking, 'payment' => $payment])
            );

            // Redirect to Khalti payment page
            return view('user.payments.khalti-checkout', [
                'khaltiDetails' => $khaltiDetails,
                'booking' => $booking,
                'payment' => $payment,
            ]);
        } catch (\Exception $e) {
            Log::error("Khalti payment initiation failed: {$e->getMessage()}");
            $payment->update(['payment_status' => 'failed']);
            throw $e;
        }
    }

    /**
     * Handle payment callback from Khalti webhook.
     * Processes successful or failed payments and updates booking status.
     * 
     * NOTE: This is called from our client-side after returning from Khalti.
     * The actual webhook/server verification happens in webhook handler.
     */
    public function callback(Request $request, Booking $booking, Payment $payment)
    {
        // Verify callback parameters
        if (!$request->has(['tidx', 'pidx'])) {
            Log::warning("Invalid payment callback", ['request' => $request->all()]);
            return redirect()->route('user.bookings.show', $booking)
                ->with('error', 'Invalid payment callback');
        }

        try {
            // Verify payment with Khalti API
            $response = $this->khaltiService->verifyPayment(
                $request->input('pidx'),
                $booking,
                $payment->amount
            );

            if ($response['success']) {
                // Handle payment success
                $this->paymentService->handlePaymentSuccess($payment, [
                    'transaction_id' => $response['transaction_id'] ?? $request->input('tidx'),
                    'response' => $response,
                ]);

                Log::info("Payment successful for booking {$booking->id}", [
                    'payment_id' => $payment->id,
                    'transaction_id' => $response['transaction_id'] ?? null,
                ]);

                return redirect()->route('user.bookings.show', $booking)
                    ->with('success', 'Payment completed successfully! Your booking is confirmed.');
            } else {
                // Handle payment failure
                $this->paymentService->handlePaymentFailure($payment, [
                    'reason' => $response['message'] ?? 'Verification failed',
                    'response' => $response,
                ]);

                Log::warning("Payment verification failed for booking {$booking->id}", [
                    'payment_id' => $payment->id,
                    'response' => $response,
                ]);

                return redirect()->route('payment.show', $booking)
                    ->with('error', 'Payment verification failed. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error("Payment callback error for booking {$booking->id}: {$e->getMessage()}");

            // Mark payment as failed
            $payment->update(['payment_status' => 'failed']);

            return redirect()->route('payment.show', $booking)
                ->with('error', 'Payment processing error. Please contact support.');
        }
    }

    /**
     * Payment webhook endpoint for Khalti server-to-server verification.
     * This is called by Khalti's server to confirm payment success.
     * Must be kept separate from client callback for security.
     * 
     * POST /api/webhook/payment/khalti
     */
    public function khaltiWebhook(Request $request)
    {
        return $this->handleKhaltiWebhook($request);
    }

    /**
     * Handle Khalti webhook - verify and confirm payment.
     * This endpoint is called by Khalti servers, not the client.
     */
    private function handleKhaltiWebhook(Request $request)
    {
        Log::info("Khalti webhook received", ['data' => $request->all()]);

        try {
            // Validate webhook token (from Khalti)
            if (!$this->validateKhaltiWebhookToken($request)) {
                Log::warning("Invalid Khalti webhook token");
                return response()->json(['success' => false, 'message' => 'Invalid token'], 401);
            }

            $pidx = $request->input('pidx');

            if (!$pidx) {
                return response()->json(['success' => false, 'message' => 'Missing pidx'], 400);
            }

            // Find payment by Khalti transaction reference
            $payment = Payment::where('transaction_id', $pidx)
                ->orWhere(function ($q) use ($pidx) {
                    // Search in gateway response if stored there
                    $q->where('payment_gateway_response', 'like', "%{$pidx}%");
                })
                ->first();

            if (!$payment) {
                Log::warning("Payment not found for Khalti webhook", ['pidx' => $pidx]);
                return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
            }

            // Verify payment with Khalti API
            $verifyResponse = $this->khaltiService->verifyPayment(
                $pidx,
                $payment->booking,
                $payment->amount
            );

            if (!$verifyResponse['success']) {
                Log::warning("Khalti verification failed", ['payment_id' => $payment->id]);
                return response()->json(['success' => false, 'message' => 'Verification failed'], 400);
            }

            // Confirm payment (idempotent)
            $this->paymentService->handlePaymentSuccess($payment, [
                'transaction_id' => $verifyResponse['transaction_id'] ?? $pidx,
                'response' => $verifyResponse,
            ]);

            Log::info("Khalti webhook processed successfully", [
                'payment_id' => $payment->id,
                'booking_id' => $payment->booking_id,
            ]);

            return response()->json(['success' => true, 'message' => 'Payment confirmed']);
        } catch (\Exception $e) {
            Log::error("Khalti webhook error: {$e->getMessage()}", [
                'exception' => $e,
            ]);
            return response()->json(['success' => false, 'message' => 'Server error'], 500);
        }
    }

    /**
     * Validate Khalti webhook token.
     */
    private function validateKhaltiWebhookToken(Request $request): bool
    {
        // Implement Khalti webhook token validation
        // See: https://docs.khalti.com/web/webhook/
        $token = $request->header('X-Khalti-Token');
        $expectedToken = config('services.khalti.webhook_token');

        return $token === $expectedToken;
    }

    /**
     * Retry failed payment.
     * User can retry failed payment attempt.
     */
    public function retry(Payment $payment)
    {
        $booking = $payment->booking;

        // Authorize
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Verify payment is failed
        if ($payment->payment_status !== 'failed') {
            return redirect()->route('user.bookings.show', $booking)
                ->with('warning', 'Only failed payments can be retried');
        }

        // Redirect to payment form to retry
        return redirect()->route('payment.show', $booking)
            ->with('info', 'Please try payment again');
    }

    /**
     * Get payment history for a booking.
     */
    public function history(Booking $booking)
    {
        // Authorize
        $this->authorize('view', $booking);

        $payments = $booking->payments()
            ->orderByDesc('created_at')
            ->get();

        return view('user.payments.history', compact('booking', 'payments'));
    }

    /**
     * Display payment receipt/invoice.
     */
    public function receipt(Payment $payment)
    {
        $booking = $payment->booking;

        // Authorize
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $payment->load('booking.property', 'booking.room');

        return view('user.payments.receipt', compact('payment', 'booking'));
    }
}
