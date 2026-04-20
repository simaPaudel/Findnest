<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'amount',
        'payment_method',
        'payment_type',
        'transaction_id',
        'payer_email',
        'payment_status',
        'payout_status',
        'payment_gateway_response',
        'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_gateway_response' => 'json',
        'paid_at' => 'datetime',
    ];

    protected $attributes = [
        'payout_status' => 'pending',
    ];

    /**
     * Get the booking associated with this payment.
     * Access user and property through booking relationship.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    /**
     * Get the user associated with this payment through the booking.
     * Use this accessor instead of querying directly.
     */
    public function getUser()
    {
        return $this->booking?->user;
    }

    /**
     * Get the property associated with this payment through the booking.
     * Use this accessor instead of querying directly.
     */
    public function getProperty()
    {
        return $this->booking?->property;
    }

    /**
     * Scope to filter payments by user (through booking relationship).
     */
    public function scopeByUser($query, $userId)
    {
        return $query->whereHas('booking', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    /**
     * Scope to filter payments by property (through booking relationship).
     */
    public function scopeByProperty($query, $propertyId)
    {
        return $query->whereHas('booking', function ($q) use ($propertyId) {
            $q->where('property_id', $propertyId);
        });
    }

    /**
     * Scope to filter paid payments only.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'success');
    }

    /**
     * Scope to filter payments whose owner payout is still pending.
     */
    public function scopePayoutPending($query)
    {
        return $query->where('payout_status', 'pending');
    }

    /**
     * Scope to filter payments whose owner payout is completed.
     */
    public function scopePayoutCompleted($query)
    {
        return $query->where('payout_status', 'completed');
    }

    /**
     * Scope to filter pending payments only.
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope to filter failed payments.
     */
    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    /**
     * Scope to filter refunded payments.
     */
    public function scopeRefunded($query)
    {
        return $query->where('payment_status', 'refunded');
    }

    /**
     * Scope to filter by payment method.
     */
    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope to filter by payment type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('payment_type', $type);
    }

    /**
     * Scope to filter payments within a date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope to filter recent payments.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days))
            ->orderByDesc('created_at');
    }

    // ==================== PAYMENT STATUS METHODS ====================

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if payment is successful.
     */
    public function isSuccessful(): bool
    {
        return $this->payment_status === 'success';
    }

    /**
     * Check if payment is failed.
     */
    public function isFailed(): bool
    {
        return $this->payment_status === 'failed';
    }

    /**
     * Check if payment is refunded.
     */
    public function isRefunded(): bool
    {
        return $this->payment_status === 'refunded';
    }

    /**
     * Check if payout to the owner is pending.
     */
    public function isPayoutPending(): bool
    {
        return ($this->payout_status ?? 'pending') === 'pending';
    }

    /**
     * Check if payout to the owner is completed.
     */
    public function isPayoutCompleted(): bool
    {
        return ($this->payout_status ?? 'pending') === 'completed';
    }

    /**
     * Get human-readable payment status label.
     */
    public function getStatusLabel(): string
    {
        $labels = [
            'pending' => 'Pending',
            'success' => 'Successful',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
        ];

        return $labels[$this->payment_status] ?? $this->payment_status;
    }

    /**
     * Get human-readable payout status label.
     */
    public function getPayoutStatusLabel(): string
    {
        $labels = [
            'pending' => 'Pending',
            'completed' => 'Completed',
        ];

        return $labels[$this->payout_status ?? 'pending'] ?? ucfirst((string) ($this->payout_status ?? 'pending'));
    }

    /**
     * Get human-readable payment method label.
     */
    public function getMethodLabel(): string
    {
        $labels = [
            'esewa' => 'eSewa',
            'khalti' => 'Khalti',
            'cash' => 'Cash',
        ];

        return $labels[$this->payment_method] ?? ucfirst($this->payment_method);
    }

    /**
     * Get human-readable payment type label.
     */
    public function getTypeLabel(): string
    {
        $labels = [
            'advance' => 'Advance Payment',
            'rent' => 'Rent Payment',
            'security_deposit' => 'Security Deposit',
        ];

        return $labels[$this->payment_type] ?? ucfirst(str_replace('_', ' ', $this->payment_type));
    }

    // ==================== PAYMENT VERIFICATION METHODS ====================

    /**
     * Mark payment as successful.
     * 
     * Should be called after payment gateway confirms successful payment.
     */
    public function markAsSuccessful(string $transactionId = null): void
    {
        $this->update([
            'payment_status' => 'success',
            'transaction_id' => $transactionId ?? $this->transaction_id,
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark payment as failed.
     * 
     * Called when payment gateway reports failure.
     */
    public function markAsFailed(array $response = null): void
    {
        $this->update([
            'payment_status' => 'failed',
            'payment_gateway_response' => $response,
        ]);
    }

    /**
     * Mark payment as refunded.
     * 
     * Called when payment is refunded after successful transaction.
     */
    public function markAsRefunded($refundAmount = null): void
    {
        $this->update([
            'payment_status' => 'refunded',
        ]);
    }

    /**
     * Mark owner payout as completed.
     */
    public function markAsPaidOut(): void
    {
        $this->update([
            'payout_status' => 'completed',
        ]);
    }

    // ==================== BUSINESS LOGIC METHODS ====================

    /**
     * Check if payment can be cancelled.
     * 
     * Only pending payments can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return $this->isPending();
    }

    /**
     * Check if payment can be refunded.
     * 
     * Only successful payments can be refunded.
     */
    public function canBeRefunded(): bool
    {
        return $this->isSuccessful();
    }

    /**
     * Store payment gateway response after payment.
     */
    public function storeGatewayResponse(array $response): void
    {
        $this->update([
            'payment_gateway_response' => $response,
        ]);
    }

    /**
     * Get the original payment gateway response as array.
     */
    public function getGatewayResponse(): array
    {
        return $this->payment_gateway_response ?? [];
    }

    /**
     * Check if this payment is part of the booking confirmation.
     */
    public function isConfirmationPayment(): bool
    {
        return $this->isSuccessful() && $this->booking?->isConfirmed();
    }
}
