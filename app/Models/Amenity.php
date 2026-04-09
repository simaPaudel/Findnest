<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
    ];

    /**
     * Boot method - auto-generate slug from name
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->slug) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    /**
     * Get all properties that have this amenity.
     */
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_amenities');
    }

    /**
     * Scope to filter amenities by search term.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', "%$term%")
            ->orWhere('slug', 'like', "%$term%");
    }
}
