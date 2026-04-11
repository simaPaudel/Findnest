<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class RoommatePreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
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

    public static function userKeyColumn(): string
    {
        return Schema::hasColumn((new static())->getTable(), 'user_id') ? 'user_id' : 'student_id';
    }

    public static function queryForUserId(int $userId): Builder
    {
        return static::query()->where(function (Builder $query) use ($userId): void {
            $query->where('user_id', $userId);

            if (Schema::hasColumn((new static())->getTable(), 'student_id')) {
                $query->orWhere('student_id', $userId);
            }
        });
    }

    public static function resolveUserId(self $preference): ?int
    {
        $userId = $preference->getAttribute('user_id');
        if ($userId !== null) {
            return (int) $userId;
        }

        $studentId = $preference->getAttribute('student_id');
        if ($studentId !== null) {
            return (int) $studentId;
        }

        return null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, static::userKeyColumn());
    }
}
