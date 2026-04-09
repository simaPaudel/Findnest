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
        'room_features'
    ];

    protected $casts = [
        'availability' => 'boolean',
        'capacity' => 'integer',
        'current_occupancy' => 'integer',
        'price' => 'decimal:2',
    ];

    /**
     * Get the property that owns this room.
     */
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    /**
     * Get all bookings for this room.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_id');
    }

    /**
     * Get all images for this room.
     */
    public function images()
    {
        return $this->hasMany(RoomImage::class, 'room_id');
    }

    /**
     * Get the primary image for this room.
     */
    public function primaryImage()
    {
        return $this->hasOne(RoomImage::class, 'room_id')
            ->where('is_primary', true)
            ->orderBy('order');
    }

    /**
     * Get images ordered by display order.
     */
    public function orderedImages()
    {
        return $this->images()->ordered();
    }

    /**
     * Get the first image URL or a default placeholder.
     */
    public function getFirstImageUrl()
    {
        $image = $this->primaryImage ?? $this->images()->first();
        if ($image) {
            return $image->getUrl();
        }

        // Try to use property's first image as fallback
        return $this->property->getFirstImageUrl();
    }

    /**
     * Get the occupancy rate as a percentage.
     */
    public function getOccupancyRate()
    {
        if ($this->capacity === 0) {
            return 0;
        }

        return round(($this->current_occupancy / $this->capacity) * 100);
    }

    /**
     * Check if room has available capacity.
     */
    public function hasAvailableCapacity()
    {
        return $this->current_occupancy < $this->capacity;
    }

    /**
     * Scope to get only available rooms.
     */
    public function scopeAvailable($query)
    {
        return $query->where('availability', true)
            ->where('current_occupancy', '<', DB::raw('capacity'));
    }

    /**
     * Scope to filter rooms by price range.
     */
    public function scopeByPriceRange($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        return $query;
    }

    /**
     * Scope to filter rooms by capacity.
     */
    public function scopeByCapacity($query, $capacity)
    {
        return $query->where('capacity', '>=', $capacity);
    }

    /**
     * Validate that room creation is allowed for the parent property.
     * 
     * Throws exception if the parent property's rental_mode doesn't allow room creation.
     * Call this in a boot() method or form request before creating a room.
     * 
     * @throws \InvalidArgumentException if rental_mode doesn't allow rooms
     */
    public function validateRentalModePermission()
    {
        if (!$this->property) {
            throw new \InvalidArgumentException('Room must be associated with a property.');
        }

        if (!$this->property->canCreateRooms()) {
            throw new \InvalidArgumentException(
                "Cannot create room for property with rental_mode: {$this->property->rental_mode}. " .
                    "Only 'per_room' rental mode allows room creation. " .
                    "Update the property's rental_mode to 'per_room' first."
            );
        }
    }

    /**
     * Check if this room belongs to a property that allows room rental.
     * 
     * Useful for filtering rooms in queries or displaying availability.
     * Returns: true if parent property allows room-based rentals
     */
    public function isRoomRentalAllowed(): bool
    {
        return $this->property?->canRentRooms() ?? false;
    }

    /**
     * Get the property's rental mode for this room.
     * 
     * Useful for displaying what rental type this room belongs to.
     * Returns: The parent property's rental_mode enum value
     */
    public function getPropertyRentalMode()
    {
        return $this->property?->rental_mode;
    }

    /**
     * Check if property requires this room to exist.
     * 
     * Used to validate that we don't delete the last room if property requires rooms.
     * Returns: true if property requires at least one room
     */
    public function isRequiredByProperty(): bool
    {
        if (!$this->property) {
            return false;
        }

        // If property requires rooms and this is the only room, it's required
        if ($this->property->requiresRooms() && $this->property->rooms()->count() === 1) {
            return true;
        }

        return false;
    }
}
