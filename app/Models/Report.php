<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = [
        'reporter_id',
        'reportable_id',
        'reportable_type',
        'report_type',
        'reason',
        'additional_info',
        'status',
        'admin_notes',
        'reviewed_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==================== RELATIONSHIPS ====================

    /**
     * Get the reporter (user who filed the report).
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Get the polymorphic reportable model (Property, Review, User, etc).
     */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the admin who reviewed this report.
     */
    public function reviewedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ==================== SCOPES ====================

    /**
     * Scope to filter reports by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter pending reports.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to filter reports under review.
     */
    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    /**
     * Scope to filter resolved reports.
     */
    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    /**
     * Scope to filter dismissed reports.
     */
    public function scopeDismissed($query)
    {
        return $query->where('status', 'dismissed');
    }

    /**
     * Scope to filter by report type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('report_type', $type);
    }

    /**
     * Scope to filter by reportable type (Property, Review, User).
     */
    public function scopeFor($query, $reportableType)
    {
        return $query->where('reportable_type', $reportableType);
    }

    /**
     * Scope to get recent reports first.
     */
    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at');
    }

    // ==================== METHODS ====================

    /**
     * Check if this report is still pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Mark report as under review.
     */
    public function markUnderReview(int $adminId): void
    {
        $this->update([
            'status' => 'under_review',
            'reviewed_by' => $adminId,
        ]);
    }

    /**
     * Resolve the report.
     */
    public function resolve(int $adminId, string $notes, string $action = 'resolved'): void
    {
        $this->update([
            'status' => $action,
            'reviewed_by' => $adminId,
            'admin_notes' => $notes,
            'resolved_at' => now(),
        ]);
    }

    /**
     * Dismiss the report (not a violation).
     */
    public function dismiss(int $adminId, string $notes): void
    {
        $this->resolve($adminId, $notes, 'dismissed');
    }

    /**
     * Get human-readable report type label.
     */
    public function getReportTypeLabel(): string
    {
        return match ($this->report_type) {
            'inappropriate_content' => 'Inappropriate Content',
            'spam' => 'Spam',
            'harassment' => 'Harassment',
            'fraud' => 'Fraud',
            'fake_listing' => 'Fake Listing',
            'scam' => 'Scam',
            'violence' => 'Violence',
            'discrimination' => 'Discrimination',
            'copyright' => 'Copyright Infringement',
            'other' => 'Other',
            default => $this->report_type,
        };
    }

    /**
     * Get human-readable status label.
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Pending Review',
            'under_review' => 'Under Review',
            'resolved' => 'Resolved',
            'dismissed' => 'Dismissed',
            default => $this->status,
        };
    }

    /**
     * Get the target item label (property title, user name, etc).
     */
    public function getTargetLabel(): string
    {
        if ($this->reportable_type === 'App\\Models\\Property' && $this->reportable) {
            return "Property: {$this->reportable->title}";
        } elseif ($this->reportable_type === 'App\\Models\\Review' && $this->reportable) {
            return "Review by {$this->reportable->reviewer->name}";
        } elseif ($this->reportable_type === 'App\\Models\\User' && $this->reportable) {
            return "User: {$this->reportable->name}";
        }

        return "{$this->reportable_type}";
    }
}
