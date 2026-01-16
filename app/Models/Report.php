<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'property_id',
        'owner_id',
        'review_id',
        'user_id',
        'report_type',
        'reason',
        'additional_info',
        'status',
        'admin_notes',
        'resolved_at'
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id');
    }
}
