<?php

namespace App\Services;

use App\Models\Property;
use App\Models\Room;
use Illuminate\Support\Collection;

class RoomAvailabilityService
{
    /**
     * Attach computed availability values to a room model for safe Blade access.
     */
    public function decorate(Room $room): Room
    {
        $room->setAttribute('active_confirmed_bookings_count', $this->getActiveConfirmedBookingCount($room));
        $room->setAttribute('availability_status', $this->getAvailabilityStatus($room));
        $room->setAttribute('is_bookable', $this->isBookable($room));

        return $room;
    }

    /**
     * Attach computed availability values to each room in a collection.
     */
    public function decorateCollection(iterable $rooms): Collection
    {
        return collect($rooms)->map(function ($room) {
            return $room instanceof Room ? $this->decorate($room) : $room;
        });
    }

    /**
     * Attach computed availability values to a property model for safe Blade access.
     */
    public function decorateProperty(Property $property): Property
    {
        if ($property->relationLoaded('rooms')) {
            $property->setRelation('rooms', $this->decorateCollection($property->rooms));

            $bookableRooms = $property->rooms->filter(fn (Room $room) => $this->isBookable($room));
            $property->setAttribute('available_rooms_count', $bookableRooms->count());
            $property->setAttribute('min_room_price', $bookableRooms->min('price'));
            $property->setAttribute('max_room_price', $bookableRooms->max('price'));
        }

        $property->setAttribute('bookable_rooms_count', $this->getBookableRoomsCount($property));
        $property->setAttribute('property_availability_status', $this->getPropertyAvailabilityStatus($property));
        $property->setAttribute('property_availability_label', $this->getPropertyAvailabilityLabel($property));
        $property->setAttribute('is_property_bookable', $this->isPropertyBookable($property));

        return $property;
    }

    /**
     * Attach computed availability values to each property in a collection.
     */
    public function decoratePropertyCollection(iterable $properties): Collection
    {
        return collect($properties)->map(function ($property) {
            return $property instanceof Property ? $this->decorateProperty($property) : $property;
        });
    }

    /**
     * Get a normalized availability status for display logic.
     */
    public function getAvailabilityStatus(Room $room): string
    {
        if (!(bool) $room->availability) {
            return 'unavailable';
        }

        if ($this->getActiveConfirmedBookingCount($room) > 0) {
            return 'full';
        }

        return 'available';
    }

    /**
     * Determine whether the room can currently be booked.
     */
    public function isBookable(Room $room): bool
    {
        return $this->getAvailabilityStatus($room) === 'available';
    }

    /**
     * Get the number of currently bookable rooms for a property.
     */
    public function getBookableRoomsCount(Property $property): int
    {
        if ($property->rental_mode !== 'per_room') {
            return 0;
        }

        $rooms = $property->relationLoaded('rooms')
            ? $property->rooms
            : $property->rooms()
                ->withCount([
                    'bookings as active_confirmed_bookings_count' => function ($query) {
                        $query->where('status', 'confirmed');
                    }
                ])->get();

        return $rooms->filter(fn (Room $room) => $this->isBookable($room))->count();
    }

    /**
     * Get a normalized availability status for a property.
     */
    public function getPropertyAvailabilityStatus(Property $property): string
    {
        if ($property->status !== 'approved') {
            return 'unavailable';
        }

        if ($property->rental_mode !== 'per_room') {
            return $this->hasActiveFullPropertyBooking($property)
                ? 'unavailable'
                : 'available';
        }

        $bookableRoomsCount = $this->getBookableRoomsCount($property);

        if ($bookableRoomsCount <= 0) {
            return 'unavailable';
        }

        return 'available';
    }

    /**
     * Get a display label for property-level availability.
     */
    public function getPropertyAvailabilityLabel(Property $property): string
    {
        if ($property->rental_mode !== 'per_room') {
            return $this->getPropertyAvailabilityStatus($property) === 'available'
                ? 'Available for booking'
                : 'Property already booked';
        }

        $bookableRoomsCount = $this->getBookableRoomsCount($property);

        if ($bookableRoomsCount <= 0) {
            return 'All rooms booked';
        }

        return $bookableRoomsCount === 1
            ? '1 room available'
            : $bookableRoomsCount . ' rooms available';
    }

    /**
     * Determine whether a property is currently bookable.
     */
    public function isPropertyBookable(Property $property): bool
    {
        return $this->getPropertyAvailabilityStatus($property) === 'available';
    }

    /**
     * Check whether the property currently has an active confirmed full-property booking.
     */
    protected function hasActiveFullPropertyBooking(Property $property): bool
    {
        if ($property->getAttribute('active_full_property_bookings_count') !== null) {
            return (int) $property->getAttribute('active_full_property_bookings_count') > 0;
        }

        return $property->bookings()
            ->whereNull('room_id')
            ->where('status', 'confirmed')
            ->exists();
    }

    /**
     * Get the number of confirmed bookings currently reserving this room.
     */
    public function getActiveConfirmedBookingCount(Room $room): int
    {
        if ($room->getAttribute('active_confirmed_bookings_count') !== null) {
            return max((int) $room->getAttribute('active_confirmed_bookings_count'), 0);
        }

        return $room->bookings()
            ->where('status', 'confirmed')
            ->count();
    }

}
