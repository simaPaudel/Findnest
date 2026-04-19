<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class KhaltiPaymentService
{
    private $apiKey;
    private $baseUrl;
    private $publicKey;

    public function __construct()
    {
        $this->apiKey = config('services.khalti.secret_key');
        $this->baseUrl = config('services.khalti.base_url');
        $this->publicKey = config('services.khalti.public_key');
    }

    /**
     * Initiate a payment for a booking
     * 
     * @param Booking $booking
     * @return array
     * @throws Exception
     */
    public function initiatePayment(Booking $booking)
    {
        try {
            // Validate booking exists
            if (!$booking) {
                throw new Exception('Booking not found');
            }

            // Validate booking has required relationships
            if (!$booking->user_id || !$booking->property_id) {
                throw new Exception('Booking must have associated user and property');
            }

            // Check if payment already completed
            $existingPayment = $booking->payments()
                ->where('payment_status', 'success')
                ->first();

            if ($existingPayment) {
                throw new Exception('Payment already completed for this booking');
            }

            // Calculate payment amount (20% of total_rent for partial payment)
            $amountInRupees = $booking->total_rent * 0.20;
            
            // Validate amount is valid
            if ($amountInRupees <= 0) {
                throw new Exception('Invalid payment amount. Booking total rent must be greater than 0');
            }

            // Convert to paisa (amount * 100)
            $amountInPaisa = intval($amountInRupees * 100);

            // Generate unique transaction ID
            $transactionId = 'TXN_' . $booking->id . '_' . now()->timestamp;

            // Khalti pidx values are single-use and expire quickly, so retire any
            // existing pending Khalti session before creating a fresh one.
            $stalePayments = $booking->payments()
                ->where('payment_method', 'khalti')
                ->where('payment_status', 'pending')
                ->get();

            foreach ($stalePayments as $stalePayment) {
                $gatewayResponse = $stalePayment->payment_gateway_response ?? [];
                $gatewayResponse['session_state'] = 'expired';
                $gatewayResponse['expired_at'] = now()->toIso8601String();
                $gatewayResponse['replaced_by_transaction_id'] = $transactionId;

                $stalePayment->update([
                    'payment_status' => 'failed',
                    'payment_gateway_response' => $gatewayResponse,
                ]);
            }

            $baseUrl = rtrim(request()->getSchemeAndHttpHost() . request()->getBaseUrl(), '/');

            // Prepare payload for Khalti ePayment
            $payload = [
                'return_url' => $baseUrl . route('payment.khalti.success', [], false),
                'website_url' => $baseUrl,
                'amount' => $amountInPaisa,
                'purchase_order_id' => $transactionId,
                'purchase_order_name' => 'Room Booking',
            ];

            // Send POST request to Khalti
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->apiKey,
            ])->post($this->baseUrl . 'epayment/initiate/', $payload);

            Log::info('Khalti API Response', [
                'status' => $response->status(),
                'booking_id' => $booking->id,
            ]);

            if (!$response->successful()) {
                Log::error('Khalti API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'booking_id' => $booking->id,
                ]);
                throw new Exception('Failed to initiate payment with Khalti');
            }

            $responseData = $response->json();

            if (!isset($responseData['pidx']) || !isset($responseData['payment_url'])) {
                Log::error('Invalid Khalti Response', [
                    'response' => $responseData,
                    'booking_id' => $booking->id,
                ]);
                throw new Exception('Invalid response from Khalti API');
            }

            $pidx = $responseData['pidx'];
            $paymentUrl = $responseData['payment_url'];

            // Store payment record - NO MORE user_id or property_id!
            // Access them through booking relationship instead
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $amountInRupees,
                'payment_method' => 'khalti',
                'payment_type' => 'advance',
                'transaction_id' => $transactionId,
                'payer_email' => $booking->user?->email,
                'payment_status' => 'pending',
                'payment_gateway_response' => [
                    'pidx' => $pidx,
                    'initiated_at' => now()->toIso8601String(),
                    'purchase_order_id' => $transactionId,
                ],
            ]);

            Log::info('Payment initiated successfully', [
                'booking_id' => $booking->id,
                'payment_id' => $payment->id,
                'transaction_id' => $transactionId,
                'amount' => $amountInRupees,
                'user_id' => $booking->user_id,
            ]);

            return [
                'success' => true,
                'payment_url' => $paymentUrl,
                'pidx' => $pidx,
                'payment_id' => $payment->id,
            ];
        } catch (Exception $e) {
            Log::error('Payment Initiation Error', [
                'booking_id' => $booking->id ?? null,
                'user_id' => $booking->user_id ?? null,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Verify payment with Khalti
     * 
     * @param string $pidx
     * @return array
     * @throws Exception
     */
    public function verifyPayment($pidx)
    {
        try {
            if (!$pidx) {
                throw new Exception('PIDX not provided for payment verification');
            }

            // Send POST request to Khalti lookup endpoint
            $response = Http::withHeaders([
                'Authorization' => 'Key ' . $this->apiKey,
            ])->post($this->baseUrl . 'epayment/lookup/', [
                'pidx' => $pidx,
            ]);

            if (!$response->successful()) {
                Log::error('Khalti Lookup Error', [
                    'status' => $response->status(),
                    'pidx' => $pidx,
                ]);
                throw new Exception('Failed to verify payment with Khalti');
            }

            $responseData = $response->json();

            if (!isset($responseData['status'])) {
                Log::error('Invalid Khalti Lookup Response', [
                    'response' => $responseData,
                    'pidx' => $pidx,
                ]);
                throw new Exception('Invalid response from Khalti API');
            }

            // Find the payment record by pidx, with fallback for older double-encoded records
            $payment = Payment::where(function ($query) use ($pidx) {
                $query->whereJsonContains('payment_gateway_response->pidx', $pidx)
                    ->orWhere('payment_gateway_response', 'like', "%{$pidx}%")
                    ->orWhere('transaction_id', $pidx);
            })->latest('id')->first();

            if (!$payment) {
                Log::error('Payment record not found', ['pidx' => $pidx]);
                throw new Exception('Payment record not found in system');
            }

            // Validate payment has a booking
            if (!$payment->booking_id) {
                Log::error('Payment missing booking relationship', ['payment_id' => $payment->id]);
                throw new Exception('Payment record is incomplete - missing booking');
            }

            $paymentStatus = $responseData['status'];
            
            if ($paymentStatus === 'Completed') {
                // Use PaymentService to handle success atomically
                /** @var PaymentService $paymentService */
                $paymentService = app(PaymentService::class);
                
                try {
                    $booking = $paymentService->handlePaymentSuccess($payment, [
                        'transaction_id' => $responseData['transaction_id'] ?? null,
                        'response' => $responseData,
                    ]);
                    
                    Log::info('Payment verified and completed', [
                        'payment_id' => $payment->id,
                        'booking_id' => $booking->id,
                        'user_id' => $booking->user_id,
                        'amount' => $payment->amount,
                    ]);
                    
                    return [
                        'success' => true,
                        'status' => 'completed',
                        'booking_id' => $booking->id,
                        'payment_id' => $payment->id,
                    ];
                } catch (Exception $e) {
                    Log::error('Failed to confirm booking after payment', [
                        'payment_id' => $payment->id,
                        'error' => $e->getMessage(),
                    ]);
                    throw $e;
                }
            } elseif ($paymentStatus === 'Pending') {
                Log::info('Payment still pending', [
                    'pidx' => $pidx,
                    'payment_id' => $payment->id,
                ]);
                
                return [
                    'success' => false,
                    'status' => 'pending',
                    'message' => 'Payment is still pending. Please try again later.',
                ];
            } else {
                // Payment failed or in other status - use PaymentService for consistency
                /** @var PaymentService $paymentService */
                $paymentService = app(PaymentService::class);
                
                $paymentService->handlePaymentFailure($payment, [
                    'reason' => 'Payment status: ' . $paymentStatus,
                    'response' => $responseData,
                ]);

                Log::warning('Payment failed or cancelled', [
                    'payment_id' => $payment->id,
                    'status' => $paymentStatus,
                    'booking_id' => $payment->booking_id,
                ]);

                return [
                    'success' => false,
                    'status' => 'failed',
                    'message' => 'Payment was not completed. Status: ' . $paymentStatus,
                ];
            }
        } catch (Exception $e) {
            Log::error('Payment Verification Error', [
                'pidx' => $pidx ?? null,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
