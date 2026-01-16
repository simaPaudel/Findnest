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
}
