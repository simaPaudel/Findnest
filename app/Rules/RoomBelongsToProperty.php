<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Room;

/**
 * RoomBelongsToProperty
 * 
 * Validates that the selected room belongs to the selected property.
 * Used in booking validation to prevent invalid room/property combinations.
 */
class RoomBelongsToProperty implements Rule
{
    protected $propertyId;

    public function __construct($propertyId)
    {
        $this->propertyId = $propertyId;
    }

    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        // If no room selected, this rule passes (room is optional)
        if (is_null($value)) {
            return true;
        }

        // Check if room exists and belongs to property
        return Room::where('id', $value)
            ->where('property_id', $this->propertyId)
            ->exists();
    }

    /**
     * Get the validation error message.
     */
    public function message()
    {
        return 'The selected room does not belong to the selected property.';
    }
}
