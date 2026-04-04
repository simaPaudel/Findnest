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
     */
    public function initiatePayment(Booking $booking)
    {
        try {
            // Validate booking exists and hasn't been paid
            if (!$booking) {
                throw new Exception('Booking not found');
            }

            // Check if payment already completed
            $existingPayment = Payment::where('booking_id', $booking->id)
                ->where('payment_status', 'paid')
                ->first();

            if ($existingPayment) {
                throw new Exception('Payment already completed for this booking');
            }

            // Calculate payment amount (20% of total_rent for partial payment)
            $amountInRupees = $booking->total_rent * 0.20;
            
            // Convert to paisa (amount * 100)
            $amountInPaisa = intval($amountInRupees * 100);

            // Generate unique transaction ID
            $transactionId = 'TXN_' . $booking->id . '_' . now()->timestamp;

            // Prepare payload for Khalti ePayment
            $payload = [
                'return_url' => route('payment.khalti.success'),
                'website_url' => config('app.url'),
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
                'body' => $response->body(),
            ]);

            if (!$response->successful()) {
                Log::error('Khalti API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new Exception('Failed to initiate payment with Khalti: ' . $response->body());
            }

            $responseData = $response->json();

            if (!isset($responseData['pidx']) || !isset($responseData['payment_url'])) {
                Log::error('Invalid Khalti Response', ['response' => $responseData]);
                throw new Exception('Invalid response from Khalti');
            }

            $pidx = $responseData['pidx'];
            $paymentUrl = $responseData['payment_url'];

            // Store payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'property_id' => $booking->property_id,
                'amount' => $amountInRupees,
                'payment_method' => 'khalti',
                'payment_type' => 'advance',
                'transaction_id' => $transactionId,
                'payment_status' => 'pending',
                'payment_gateway_response' => json_encode([
                    'pidx' => $pidx,
                    'initiated_at' => now(),
                ]),
            ]);

            Log::info('Payment initiated successfully', [
                'booking_id' => $booking->id,
                'transaction_id' => $transactionId,
                'amount' => $amountInRupees,
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
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Verify payment with Khalti
     */
    public function verifyPayment($pidx)
    {
        try {
            if (!$pidx) {
                throw new Exception('PIDX not provided');
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
                    'body' => $response->body(),
                    'pidx' => $pidx,
                ]);
                throw new Exception('Failed to verify payment');
            }

            $responseData = $response->json();

            if (!isset($responseData['status'])) {
                Log::error('Invalid Khalti Lookup Response', ['response' => $responseData]);
                throw new Exception('Invalid response from Khalti');
            }

            $paymentStatus = $responseData['status'];
            
            // Find the payment record by pidx in the response
            $payment = Payment::whereJsonContains('payment_gateway_response->pidx', $pidx)->first();

            if (!$payment) {
                Log::error('Payment record not found', ['pidx' => $pidx]);
                throw new Exception('Payment record not found');
            }

            if ($paymentStatus === 'Completed') {
                // Update payment status
                $payment->update([
                    'payment_status' => 'success',
                    'paid_at' => now(),
                    'payment_gateway_response' => json_encode($responseData),
                ]);

                // Update booking status
                $booking = $payment->booking;
                $booking->update([
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                    'confirmed_at' => now(),
                ]);

                Log::info('Payment verified and completed', [
                    'payment_id' => $payment->id,
                    'booking_id' => $booking->id,
                ]);

                return [
                    'success' => true,
                    'status' => 'completed',
                    'booking_id' => $booking->id,
                ];
            } elseif ($paymentStatus === 'Pending') {
                Log::info('Payment still pending', ['pidx' => $pidx]);
                return [
                    'success' => false,
                    'status' => 'pending',
                    'message' => 'Payment is still pending',
                ];
            } else {
                // Payment failed or in other status
                $payment->update([
                    'payment_status' => 'failed',
                    'payment_gateway_response' => json_encode($responseData),
                ]);

                Log::warning('Payment failed', [
                    'payment_id' => $payment->id,
                    'status' => $paymentStatus,
                ]);

                return [
                    'success' => false,
                    'status' => 'failed',
                    'message' => 'Payment was not completed',
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
