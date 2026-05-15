<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'room_id',
        'status',
        'check_in_date',
        'check_out_date',
        'advance_payment',
        'security_deposit',
        'total_rent',
        'special_requests',
        'confirmed_at',
        'rejected_at',
        'rejected_reason',
        'cancelled_at',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'confirmed_at' => 'datetime',
        'rejected_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'advance_payment' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'total_rent' => 'decimal:2',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the user who made this booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the property being booked.
     */
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    /**
     * Get the room being booked (if room-specific booking).
     */
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Get all payments associated with this booking.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_id');
    }

    /**
     * Get the most recent successful payment for this booking.
     */
    public function paidPayment()
    {
        return $this->hasOne(Payment::class, 'booking_id')
            ->where('payment_status', 'success')
            ->orderByDesc('paid_at');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'booking_id');
    }

    public function trustPoints()
    {
        return $this->hasMany(TrustPoint::class, 'booking_id');
    }

    // ==================== SCOPES ====================

    /**
     * Scope to get only full property bookings.
     */
    public function scopeFullProperty($query)
    {
        return $query->whereNull('room_id');
    }

    /**
     * Scope to get only room-specific bookings.
     */
    public function scopeRoomSpecific($query)
    {
        return $query->whereNotNull('room_id');
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get pending bookings.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get confirmed bookings.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope to get cancelled bookings.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope to get completed bookings.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to get active bookings (pending or confirmed).
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed']);
    }

    /**
     * Scope to get bookings within a date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('check_in_date', [$startDate, $endDate])
            ->orWhereBetween('check_out_date', [$startDate, $endDate])
            ->orWhere(function ($q) use ($startDate, $endDate) {
                $q->where('check_in_date', '<=', $startDate)
                    ->where('check_out_date', '>=', $endDate);
            });
    }

    /**
     * Scope to get bookings for a specific month.
     */
    public function scopeForMonth($query, $month, $year)
    {
        return $query->whereYear('check_in_date', $year)
            ->whereMonth('check_in_date', $month);
    }

    /**
     * Scope to get upcoming bookings.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('check_in_date', '>', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('check_in_date');
    }

    /**
     * Scope to get current/ongoing bookings.
     */
    public function scopeOngoing($query)
    {
        return $query->where('check_in_date', '<=', now())
            ->where('check_out_date', '>=', now())
            ->where('status', 'confirmed');
    }

    /**
     * Scope to get overdue bookings (checkout date passed but not marked completed).
     */
    public function scopeOverdue($query)
    {
        return $query->where('check_out_date', '<', now())
            ->whereNotIn('status', ['cancelled', 'completed', 'rejected']);
    }

    // ==================== BOOKING TYPE METHODS ====================

    /**
     * Check if this is a full property booking.
     */
    public function isFullProperty(): bool
    {
        return is_null($this->room_id);
    }

    /**
     * Check if this is a room-specific booking.
     */
    public function isRoomSpecific(): bool
    {
        return !is_null($this->room_id);
    }

    /**
     * Get the bookable item (Property for full property, Room for room-specific).
     */
    public function getBookable()
    {
        return $this->isRoomSpecific() ? $this->room : $this->property;
    }

    /**
     * Get the bookable type label.
     */
    public function getBookableTypeLabel(): string
    {
        return $this->isFullProperty() ? 'Full Property' : 'Room';
    }

    /**
     * Get the name of what was booked.
     */
    public function getBookableName(): string
    {
        if ($this->isRoomSpecific()) {
            return "{$this->room->room_name} in {$this->property->title}";
        }

        return $this->property->title;
    }

    // ==================== STATUS METHODS ====================

    /**
     * Check if booking is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if booking is confirmed.
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if booking is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if booking is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if booking is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if booking is active (pending or confirmed).
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    // ==================== DATE METHODS ====================

    /**
     * Calculate the duration in months from check-in to check-out dates.
     */
    public function getDurationInMonths(): int
    {
        if (!$this->check_in_date || !$this->check_out_date) {
            return 0;
        }

        return $this->check_in_date->diffInMonths($this->check_out_date);
    }

    /**
     * Calculate the duration in days from check-in to check-out dates.
     */
    public function getDurationInDays(): int
    {
        if (!$this->check_in_date || !$this->check_out_date) {
            return 0;
        }

        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    /**
     * Check if booking is currently active (between check-in and check-out).
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->check_in_date || !$this->check_out_date) {
            return false;
        }

        $now = now();
        return $now->greaterThanOrEqualTo($this->check_in_date) &&
            $now->lessThanOrEqualTo($this->check_out_date);
    }

    /**
     * Check if booking is upcoming (hasn't started yet).
     */
    public function isUpcoming(): bool
    {
        return $this->check_in_date && $this->check_in_date->isFuture();
    }

    /**
     * Check if booking has passed (check-out date is in the past).
     */
    public function hasPassed(): bool
    {
        return $this->check_out_date && $this->check_out_date->isPast();
    }

    public function isStayCompletedForFeedback(): bool
    {
        return $this->check_out_date
            && $this->check_out_date->copy()->endOfDay()->lessThanOrEqualTo(now())
            && ($this->isConfirmed() || $this->isCompleted())
            && $this->hasSuccessfulPayment()
            && (float) $this->getTotalPaid() > 0;
    }

    /**
     * Get days until check-in.
     */
    public function getDaysUntilCheckIn(): int
    {
        if (!$this->check_in_date) {
            return 0;
        }

        return now()->diffInDays($this->check_in_date);
    }

    /**
     * Get days until check-out.
     */
    public function getDaysUntilCheckOut(): int
    {
        if (!$this->check_out_date) {
            return 0;
        }

        return now()->diffInDays($this->check_out_date);
    }

    // ==================== PAYMENT METHODS ====================

    /**
     * Get total amount paid against this booking.
     */
    public function getTotalPaid(): float
    {
        return (float) ($this->payments()
            ->where('payment_status', 'success')
            ->sum('amount') ?? 0);
    }

    /**
     * Get amount pending for this booking.
     */
    public function getAmountPending(): float
    {
        $totalRent = (float) $this->total_rent;
        $totalPaid = $this->getTotalPaid();

        return max(0, $totalRent - $totalPaid);
    }

    /**
     * Check if this booking is fully paid.
     */
    public function isFullyPaid(): bool
    {
        return $this->getTotalPaid() >= $this->total_rent;
    }

    /**
     * Check if this booking has any payments.
     */
    public function hasPayments(): bool
    {
        return $this->payments()
            ->where('payment_status', 'success')
            ->exists();
    }

    /**
     * Get payment progress as percentage.
     */
    public function getPaymentProgress(): int
    {
        $totalRent = (float) $this->total_rent;
        if ($totalRent == 0) {
            return 0;
        }

        $totalPaid = $this->getTotalPaid();
        return (int) min(100, ($totalPaid / $totalRent) * 100);
    }

    /**
     * Check if this booking has a successful payment.
     * 
     * Returns true if at least one payment with status='success' exists.
     */
    public function hasSuccessfulPayment(): bool
    {
        return $this->payments()
            ->where('payment_status', 'success')
            ->exists();
    }

    /**
     * Check if this booking has any pending payment.
     * 
     * Pending payments are those awaiting verification from payment gateway.
     */
    public function hasPendingPayment(): bool
    {
        return $this->payments()
            ->where('payment_status', 'pending')
            ->exists();
    }

    /**
     * Check if this booking has a failed payment.
     * 
     * Returns true if there's at least one failed payment.
     */
    public function hasFailedPayment(): bool
    {
        return $this->payments()
            ->where('payment_status', 'failed')
            ->exists();
    }

    /**
     * Get the most recent successful payment for this booking.
     * 
     * Useful for displaying payment details to user.
     */
    public function lastSuccessfulPayment(): ?Payment
    {
        return $this->payments()
            ->where('payment_status', 'success')
            ->orderByDesc('paid_at')
            ->first();
    }

    /**
     * Get all pending payments for this booking.
     * 
     * Pending payments awaiting verification or user completion.
     */
    public function pendingPayments()
    {
        return $this->payments()
            ->where('payment_status', 'pending')
            ->orderByDesc('created_at');
    }

    /**
     * Count of successful payments for this booking.
     */
    public function getSuccessfulPaymentCount(): int
    {
        return $this->payments()
            ->where('payment_status', 'success')
            ->count();
    }

    /**
     * Get payment status summary for this booking.
     * 
     * Returns array with payment summary for easy viewing.
     */
    public function getPaymentSummary(): array
    {
        return [
            'total_rent' => (float) $this->total_rent,
            'total_paid' => $this->getTotalPaid(),
            'amount_pending' => $this->getAmountPending(),
            'payment_progress' => $this->getPaymentProgress(),
            'is_fully_paid' => $this->isFullyPaid(),
            'has_pending_payment' => $this->hasPendingPayment(),
            'has_failed_payment' => $this->hasFailedPayment(),
            'payment_count' => $this->getSuccessfulPaymentCount(),
        ];
    }

    // ==================== ACTION METHODS ====================

    /**
     * Confirm the booking.
     */
    public function confirm(): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Reject the booking.
     */
    public function reject(string $reason = null): void
    {
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Cancel the booking.
     */
    public function cancel(): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }

    /**
     * Mark booking as completed.
     */
    public function complete(): void
    {
        $this->update([
            'status' => 'completed',
        ]);
    }

    /**
     * Get human-readable status label.
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Pending Confirmation',
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
            'completed' => 'Completed',
            'rejected' => 'Rejected',
            default => $this->status,
        };
    }
}
