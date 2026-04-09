<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * PaymentWebhookController
 * 
 * Handles webhooks from payment gateways.
 * These are server-to-server calls from Khalti, Stripe, etc.
 * Must be kept separate from client-facing payment callbacks for security.
 * 
 * Webhook security:
 * - Verify webhook signature/token
 * - Log all webhook calls
 * - Implement idempotency (process once, even if called multiple times)
 * - Return 2xx immediately (don't do heavy processing)
 */
class PaymentWebhookController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Khalti Payment Success Webhook
     * 
     * POST /webhooks/khalti/payment-success
     * Called when Khalti payment is completed successfully.
     * 
     * Expected payload:
     * {
     *   "pidx": "GBc45Ep",
     *   "transaction_id": "12345",
     *   "amount": "1500",
     *   "mobile": "98XXXXXXXX",
     *   "status": "Completed"
     * }
     */
    public function khaltiSuccess(Request $request)
    {
        return $this->handleWebhookAsync(function () use ($request) {
            Log::info("Khalti success webhook received", [
                'pidx' => $request->input('pidx'),
                'transaction_id' => $request->input('transaction_id'),
            ]);

            // Verify webhook signature
            if (!$this->verifyKhaltiSignature($request)) {
                Log::warning("Invalid Khalti webhook signature");
                return response()->json(['success' => false], 401);
            }

            $pidx = $request->input('pidx');

            // Find payment by Khalti transaction reference
            $payment = $this->findPaymentByKhaltiId($pidx);
            if (!$payment) {
                Log::warning("Payment not found for Khalti pidx: {$pidx}");
                return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
            }

            // Confirm payment (idempotent)
            try {
                $this->paymentService->handlePaymentSuccess($payment, [
                    'transaction_id' => $request->input('transaction_id') ?? $pidx,
                    'response' => $request->all(),
                ]);

                Log::info("Khalti webhook processed", [
                    'payment_id' => $payment->id,
                    'booking_id' => $payment->booking_id,
                ]);

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Error processing Khalti webhook: {$e->getMessage()}", [
                    'payment_id' => $payment->id,
                ]);
                // Return success anyway (Khalti will retry if we return error)
                return response()->json(['success' => true, 'message' => 'Processing']);
            }
        });
    }

    /**
     * Khalti Payment Failure Webhook
     * 
     * POST /webhooks/khalti/payment-failure
     * Called when Khalti payment fails.
     */
    public function khaltiFailure(Request $request)
    {
        return $this->handleWebhookAsync(function () use ($request) {
            Log::info("Khalti failure webhook received", [
                'pidx' => $request->input('pidx'),
            ]);

            // Verify webhook signature
            if (!$this->verifyKhaltiSignature($request)) {
                return response()->json(['success' => false], 401);
            }

            $pidx = $request->input('pidx');
            $payment = $this->findPaymentByKhaltiId($pidx);

            if (!$payment) {
                return response()->json(['success' => false], 404);
            }

            try {
                $this->paymentService->handlePaymentFailure($payment, [
                    'reason' => $request->input('reason', 'Unknown failure'),
                    'response' => $request->all(),
                ]);

                Log::info("Khalti failure webhook processed", [
                    'payment_id' => $payment->id,
                ]);

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                Log::error("Error processing Khalti failure webhook: {$e->getMessage()}");
                return response()->json(['success' => true]);
            }
        });
    }

    /**
     * Generic webhook handler with async processing.
     * Returns immediately, processes in background.
     */
    private function handleWebhookAsync(callable $handler)
    {
        try {
            // Process synchronously for now (can queue to job later)
            return $handler();
        } catch (\Throwable $e) {
            Log::error("Webhook handler error: {$e->getMessage()}", [
                'exception' => $e,
            ]);
            // Always return 200 to prevent retries for unexpected errors
            return response()->json(['success' => true], 200);
        }
    }

    /**
     * Find payment by Khalti payment ID (pidx).
     */
    private function findPaymentByKhaltiId(string $pidx): ?Payment
    {
        return Payment::where('transaction_id', $pidx)
            ->orWhere(function ($query) use ($pidx) {
                $query->where('payment_gateway_response', 'like', "%\"pidx\":\"{$pidx}\"%");
            })
            ->first();
    }

    /**
     * Verify Khalti webhook signature.
     * 
     * Khalti sends signature in X-Khalti-Signature header.
     * We need to verify it against our webhook secret.
     */
    private function verifyKhaltiSignature(Request $request): bool
    {
        $signature = $request->header('X-Khalti-Signature');
        $secret = config('services.khalti.webhook_secret');

        if (!$signature || !$secret) {
            return false;
        }

        // Build the message to verify (body + timestamp)
        // Khalti documentation should specify exact format
        $message = $request->getContent(); // Raw body
        $timestamp = $request->header('X-Khalti-Timestamp', '');

        // Compute HMAC-SHA256
        $expectedSignature = hash_hmac(
            'sha256',
            $message . $timestamp,
            $secret,
            false
        );

        return hash_equals($expectedSignature, $signature);
    }
}
