<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

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
        'payout_method',
        'payout_account_name',
        'payout_account_number',
        'payout_qr',
        'payout_notes',
        'trust_points',
        'is_verified',
        'is_blocked',
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
        'is_blocked' => 'boolean',
    ];

    protected $attributes = [
        'role' => self::ROLE_USER,
        'trust_points' => 0,
        'is_verified' => false,
        'is_blocked' => false,
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

    public function isBlocked(): bool
    {
        return (bool) $this->is_blocked;
    }

    public function profilePhotoUrl(): ?string
    {
        if (! $this->profile_photo) {
            return null;
        }

        if (Str::startsWith($this->profile_photo, ['http://', 'https://', '//'])) {
            return $this->profile_photo;
        }

        $path = ltrim($this->profile_photo, '/');

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        if (file_exists(public_path('storage/' . $path))) {
            return asset('storage/' . $path);
        }

        if (Str::startsWith($path, 'storage/')) {
            return asset($path);
        }

        if (file_exists(storage_path('app/public/' . $path))) {
            return asset('storage/' . $path);
        }

        if (Str::startsWith($path, 'profiles/')) {
            return asset('storage/' . $path);
        }

        return asset($path);
    }

    public function avatarInitial(): string
    {
        return strtoupper(Str::substr(trim((string) ($this->name ?: 'U')), 0, 1));
    }

    public function payoutQrUrl(): ?string
    {
        if (! $this->payout_qr) {
            return null;
        }

        if (Str::startsWith($this->payout_qr, ['http://', 'https://', '//'])) {
            return $this->payout_qr;
        }

        $path = ltrim($this->payout_qr, '/');

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        if (file_exists(public_path('storage/' . $path))) {
            return asset('storage/' . $path);
        }

        if (Str::startsWith($path, 'storage/')) {
            return asset($path);
        }

        if (file_exists(storage_path('app/public/' . $path))) {
            return asset('storage/' . $path);
        }

        if (Str::startsWith($path, 'payout-qr/')) {
            return asset('storage/' . $path);
        }

        return asset($path);
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

    public function trustPointsGiven()
    {
        return $this->hasMany(TrustPoint::class, 'giver_id');
    }

    public function trustPointsReceived()
    {
        return $this->hasMany(TrustPoint::class, 'receiver_id');
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
