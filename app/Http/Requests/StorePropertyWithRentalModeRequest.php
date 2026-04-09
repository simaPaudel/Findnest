<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyWithRentalModeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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

            // Full property rental fields - conditional on rental_mode
            'rent_price' => 'nullable|numeric|min:0|max:999999.99',
            'property_type' => 'required|in:house,flat,apartment,room',
            'gender_preference' => 'nullable|in:any,male,female',
            'furnished' => 'nullable|boolean',
            'total_rooms' => 'nullable|integer|min:1|max:100',

            // Rental mode determination
            'rental_mode' => 'required|in:full_property,per_room',

            // Common fields
            'amenity_ids' => 'nullable|array|max:20',
            'amenity_ids.*' => 'exists:amenities,id',
            'rules' => 'nullable|string|max:2000',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'images' => 'nullable|array|max:20',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',

            // Room data (if rental_mode includes 'rooms')
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
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'rental_mode.required' => 'Please select a rental mode (Full Property or Per Room)',
            'rental_mode.in' => 'Invalid rental mode selected. Choose Full Property or Per Room',
            'rooms.*.capacity.required_with' => 'Room capacity is required when adding rooms',
            'rooms.*.price.required_with' => 'Room price is required when adding rooms',
            'rooms.*.images.*.image' => 'Each room image must be a valid image file',
            'rooms.*.images.*.mimes' => 'Room images must be JPG, PNG, or WebP format',
            'rooms.*.images.*.max' => 'Each room image must not exceed 5MB',
            'rent_price.required' => 'Property rent price is required for full property rental',
        ];
    }

    /**
     * Custom validation using after hook.
     */
    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $rentalMode = $this->rental_mode;

            // If rental_mode is 'full_property', rent_price is required
            if ($rentalMode === 'full_property') {
                if (!$this->rent_price || $this->rent_price <= 0) {
                    $validator->errors()->add('rent_price', 'Rent price is required and must be greater than 0 for full property rentals');
                }
            }

            // If rental_mode is 'per_room', at least one room must be provided
            if ($rentalMode === 'per_room') {
                if (empty($this->rooms) || count($this->rooms) === 0) {
                    $validator->errors()->add('rooms', 'At least one room must be added for room-based rental');
                }
            }
        });
    }
}
