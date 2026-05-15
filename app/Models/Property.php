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
        'property_type',
        'rental_mode',
        'gender_preference',
        'furnished',
        'total_rooms',
        'rules',
        'latitude',
        'longitude',
        'is_verified',
        'status'
    ];

    protected $casts = [
        'furnished' => 'boolean',
        'is_verified' => 'boolean',
        'rent_price' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
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

    public function approvedPropertyReviews()
    {
        return $this->hasMany(Review::class, 'property_id')
            ->where('review_type', 'property')
            ->where('is_approved', true);
    }

    public function scopeWithApprovedReviewStats($query)
    {
        return $query
            ->withCount(['approvedPropertyReviews as property_reviews_count'])
            ->withAvg(['approvedPropertyReviews as property_average_rating'], 'rating');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function savedBy()
    {
        return $this->hasMany(SavedListing::class, 'property_id');
    }

    public function payments()
    {
        return $this->hasManyThrough(
            Payment::class,
            Booking::class,
            'property_id',
            'booking_id',
            'id',
            'id'
        );
    }

    public function getTotalPaymentsReceived()
    {
        return $this->payments()
            ->where('payment_status', 'success')
            ->sum('amount') ?? 0;
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenities');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class, 'property_id');
    }

    public function primaryImage()
    {
        return $this->hasOne(PropertyImage::class, 'property_id')
            ->where('is_primary', true)
            ->orderBy('order');
    }

    public function orderedImages()
    {
        return $this->images()->ordered();
    }

    public function scopeWithAmenities($query, array $amenityIds)
    {
        return $query->whereHas('amenities', function ($q) use ($amenityIds) {
            $q->whereIn('amenities.id', $amenityIds);
        });
    }

    public function getFirstImageUrl(bool $fallback = true)
    {
        $image = $this->relationLoaded('images')
            ? $this->images
                ->sortBy(fn($image) => [
                    $image->is_primary ? 0 : 1,
                    $image->order ?? PHP_INT_MAX,
                    $image->id,
                ])
                ->first()
            : $this->images()
                ->orderByDesc('is_primary')
                ->orderBy('order')
                ->orderBy('id')
                ->first();

        if ($image) {
            return $image->getUrl();
        }

        $roomImage = $this->relationLoaded('rooms')
            ? $this->rooms
                ->flatMap(fn ($room) => $room->relationLoaded('images') ? $room->images : collect())
                ->sortBy(fn($image) => [
                    $image->is_primary ? 0 : 1,
                    $image->order ?? PHP_INT_MAX,
                    $image->id,
                ])
                ->first()
            : $this->roomImages()
                ->orderByDesc('is_primary')
                ->orderBy('order')
                ->orderBy('id')
                ->first();

        if ($roomImage) {
            return $roomImage->getUrl();
        }

        return $fallback ? asset('images/property-placeholder.jpg') : null;
    }
    public function getMinimumRoomPrice(): ?float
    {
        if (!$this->canRentPerRoom()) {
            return null;
        }

        $price = $this->relationLoaded('rooms')
            ? $this->rooms->min('price')
            : $this->rooms()->min('price');

        return $price !== null ? (float) $price : null;
    }

    public function getMaximumRoomPrice(): ?float
    {
        if (!$this->canRentPerRoom()) {
            return null;
        }

        $price = $this->relationLoaded('rooms')
            ? $this->rooms->max('price')
            : $this->rooms()->max('price');

        return $price !== null ? (float) $price : null;
    }

    public function getOwnerPriceLabel(): string
    {
        if ($this->canRentPerRoom()) {
            $minPrice = $this->getMinimumRoomPrice();
            $maxPrice = $this->getMaximumRoomPrice();

            if ($minPrice === null || $maxPrice === null) {
                return 'Room price pending';
            }

            if ($minPrice === $maxPrice) {
                return 'Rs ' . number_format($minPrice) . ' / room';
            }

            return 'Rs ' . number_format($minPrice) . ' - Rs ' . number_format($maxPrice) . ' / room';
        }

        return 'Rs ' . number_format((float) $this->rent_price) . ' / month';
    }

    public function getOwnerListingSummary(): string
    {
        if ($this->canRentPerRoom()) {
            $roomCount = $this->relationLoaded('rooms')
                ? $this->rooms->count()
                : $this->rooms()->count();

            return trim($this->getPropertyTypeLabel() . ' • ' . $roomCount . ' room' . ($roomCount === 1 ? '' : 's'));
        }

        return $this->getPropertyTypeLabel();
    }

    public function canRentFullProperty()
    {
        return $this->rental_mode === 'full_property';
    }

    public function canRentPerRoom()
    {
        return $this->rental_mode === 'per_room';
    }

    public function canRentRooms()
    {
        return $this->canRentPerRoom();
    }

    public function roomImages()
    {
        return $this->hasManyThrough(
            RoomImage::class,
            Room::class,
            'property_id',
            'room_id',
            'id',
            'id'
        );
    }

    public function scopeByRentalMode($query, $mode)
    {
        if ($mode === 'full_property') {
            return $query->where('rental_mode', 'full_property');
        }

        if ($mode === 'per_room') {
            return $query->where('rental_mode', 'per_room');
        }

        return $query->where('rental_mode', $mode);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true)
            ->where('status', 'approved');
    }

    public function canCreateRooms(): bool
    {
        return $this->rental_mode === 'per_room';
    }

    public function requiresRooms(): bool
    {
        return $this->rental_mode === 'per_room';
    }

    public function validateRoomCreation()
    {
        if (!$this->canCreateRooms()) {
            throw new \InvalidArgumentException(
                "Cannot create rooms for property with rental_mode: {$this->rental_mode}. " .
                    "Only 'per_room' rental mode allows room creation."
            );
        }
    }

    public function getPropertyTypeLabel(): string
    {
        $labels = [
            'house' => 'House',
            'flat' => 'Flat/Apartment',
            'room' => 'Single Room',
            'apartment' => 'Multi-room Apartment',
            'other' => 'Other'
        ];
        return $labels[$this->property_type] ?? $this->property_type;
    }

    public function getRentalModeLabel(): string
    {
        $labels = [
            'full_property' => 'Full Property',
            'per_room' => 'Individual Rooms'
        ];
        return $labels[$this->rental_mode] ?? $this->rental_mode;
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('property_type', $type);
    }

    public function scopeOfTypes($query, array $types)
    {
        return $query->whereIn('property_type', $types);
    }

    public function isSuitableForFullPropertyRental(): bool
    {
        return in_array($this->property_type, ['house', 'flat', 'apartment']);
    }

    public function isSuitableForRoomRental(): bool
    {
        return in_array($this->property_type, ['house', 'apartment']);
    }

    public function getRecommendedRentalMode(): string
    {
        if ($this->property_type === 'room') {
            return 'full_property';
        }

        if (in_array($this->property_type, ['house', 'apartment'])) {
            return 'per_room';
        }

        return 'full_property';
    }
}
