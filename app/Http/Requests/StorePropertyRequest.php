<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only authenticated users can create properties
        return auth()->check() && auth()->user()->isOwner();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'rent_price' => 'required|numeric|min:0|max:999999.99',
            'property_type' => 'required|in:house,flat,apartment,room',
            'property_type' => 'required|in:house,flat,room,apartment,hostel,other',
            'rental_mode' => 'required|in:full_property,per_room',
            'gender_preference' => 'nullable|in:any,male,female',
            'furnished' => 'nullable|boolean',
            'total_rooms' => 'nullable|integer|min:1|max:100',
            'amenity_ids' => 'nullable|array',
            'amenity_ids.*' => 'exists:amenities,id',
            'rules' => 'nullable|string|max:2000',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'images' => 'nullable|array|max:20',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'rooms' => 'nullable|array',
            'rooms.*.room_name' => 'required_with:rooms|string|max:100',
            'rooms.*.room_number' => 'nullable|string|max:50',
            'rooms.*.capacity' => 'required_with:rooms|integer|min:1|max:100',
            'rooms.*.price' => 'required_with:rooms|numeric|min:0|max:999999.99',
            'rooms.*.room_features' => 'nullable|string|max:1000',
            'rooms.*.images' => 'nullable|array|max:10',
            'rooms.*.images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Property title is required',
            'address.required' => 'Property address is required',
            'city.required' => 'City is required',
            'rent_price.required' => 'Rent price is required',
            'property_type.required' => 'Property type is required',
            'property_type.required' => 'Property type is required',
            'property_type.in' => 'Invalid property type selected. Valid types: house, flat, room, apartment, hostel, or other',
            'rental_mode.required' => 'Rental mode is required',
            'rental_mode.in' => 'Invalid rental mode selected. Valid modes: full_property or per_room',
            'images.*.image' => 'Each file must be a valid image',
            'images.*.mimes' => 'Images must be JPG, PNG, or WebP format',
            'images.*.max' => 'Each image must not exceed 5MB',
            'amenity_ids.*.exists' => 'Invalid amenity selected',
            'rooms.*.capacity.required_with' => 'Room capacity is required when adding rooms',
            'rooms.*.price.required_with' => 'Room price is required when adding rooms',
            'rooms.*.images.*.image' => 'Each room image must be a valid image file',
            'rooms.*.images.*.mimes' => 'Room images must be JPG, PNG, or WebP format',
            'rooms.*.images.*.max' => 'Each room image must not exceed 5MB',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'furnished' => $this->boolean('furnished'),
        ]);
    }

    /**
     * Custom validation using after hook for complex business rules.
     */
    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // If rental_mode is 'full_property', rent_price is required
            if ($this->rental_mode === 'full_property') {
                if (!$this->rent_price || $this->rent_price <= 0) {
                    $validator->errors()->add(
                        'rent_price',
                        'Rent price is required and must be greater than 0 for full property rentals'
                    );
                }
            }

            // If rental_mode is 'per_room', at least one room must be provided
            if ($this->rental_mode === 'per_room') {
                if (empty($this->rooms) || count($this->rooms) === 0) {
                    $validator->errors()->add(
                        'rooms',
                        'At least one room must be added for room-based rental modes'
                    );
                }
            }

            // Validate property type and rental mode combination
            $this->validatePropertyTypeRentalModeCombination($validator);
        });
    }

    /**
     * Validate that property_type and rental_mode combination is appropriate.
     */
    private function validatePropertyTypeRentalModeCombination($validator)
    {
        $propertyType = $this->property_type;
        $rentalMode = $this->rental_mode;

        // Single rooms should not use 'per_room' mode (they are the room)
        if ($propertyType === 'room' && $rentalMode === 'per_room') {
            $validator->errors()->add(
                'rental_mode',
                'Single rooms should use "full_property" mode, not "per_room" mode'
            );
        }

        // Hostels are typically room-based
        if ($propertyType === 'hostel' && $rentalMode === 'full_property') {
            $validator->errors()->add(
                'rental_mode',
                'Hostels are typically rented by rooms. Consider using "per_room" mode'
            );
        }
    }
}
