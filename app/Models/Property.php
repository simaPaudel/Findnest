<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'title',
        'description',
        'address',
        'city',
        'location',
        'landmark',
        'rent_price',
        'room_type',
        'gender_preference',
        'furnished',
        'total_rooms',
        'amenities',
        'photos',
        'rules',
        'latitude',
        'longitude',
        'is_verified',
        'status'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'property_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class, 'property_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'property_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'property_id');
    }
}
