<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $casts = [
        'is_verified' => 'boolean',
        'is_approved' => 'boolean',
    ];

    protected $fillable = [
        'user_id',
        'property_id',
        'booking_id',
        'review_type',
        'rating',
        'review_text',
        'is_verified',
        'is_approved',
        'helpful_votes',
        'trust_points_awarded'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias for user() - the reviewer of this review.
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Get all reports for this review (polymorphic).
     */
    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
