<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrustPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'giver_id',
        'receiver_id',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function giver()
    {
        return $this->belongsTo(User::class, 'giver_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
