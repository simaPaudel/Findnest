<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoommatePreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'budget_range',
        'preferred_location',
        'cleanliness_level',
        'sleep_schedule',
        'study_habits',
        'gender_preference',
        'smoking_preference',
        'alcohol_preference',
        'max_roommates',
        'age_range_min',
        'age_range_max',
        'interests',
        'additional_preferences'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
