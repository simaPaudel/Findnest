<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    const ROLE_USER = 'user';
    const ROLE_OWNER = 'owner';
    const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'gender',
        'bio',
        'profile_photo',
        'trust_points',
        'is_verified',
        'email_verified_at',
        'verification_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'trust_points' => 'integer',
        'is_verified' => 'boolean',
    ];

    protected $attributes = [
        'role' => self::ROLE_USER,
        'trust_points' => 0,
        'is_verified' => false,
    ];

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    // Relationships
    public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function roommatePreference()
    {
        return $this->hasOne(RoommatePreference::class);
    }

    public function savedListings()
    {
        return $this->hasMany(SavedListing::class, 'user_id');
    }

    public function ownerApplications()
    {
        return $this->hasMany(OwnerApplication::class);
    }

    public function latestOwnerApplication()
    {
        return $this->hasOne(OwnerApplication::class)->latestOfMany();
    }

    public function notifications()
    {
        return $this->hasMany(AppNotification::class);
    }

    /**
     * Get all payments made by this user (through bookings).
     */
    public function payments()
    {
        return $this->hasManyThrough(
            Payment::class,
            Booking::class,
            'user_id',      // Foreign key on bookings table
            'booking_id',   // Foreign key on payments table
            'id',           // Local key on users table
            'id'            // Local key on bookings table
        );
    }

    /**
     * Get all reports filed by this user (as reporter).
     */
    public function reportsFiled()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    /**
     * Get all reports about this user (polymorphic).
     */
    public function reportsAboutMe()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * Get reports reviewed by this user (admin processing reports).
     */
    public function reviewedReports()
    {
        return $this->hasMany(Report::class, 'reviewed_by');
    }
}
