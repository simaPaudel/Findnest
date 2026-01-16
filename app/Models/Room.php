<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'room_name',
        'room_number',
        'capacity',
        'current_occupancy',
        'price',
        'availability',
        'room_photos',
        'room_features'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_id');
    }
}
