<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class UpdatePropertyWithRentalModeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->isOwner()
            && $this->route('property')->owner_id === auth()->id();
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

            'rent_price' => 'nullable|numeric|min:0|max:999999.99',
            'property_type' => 'required|in:house,flat,apartment,room',
            'gender_preference' => 'nullable|in:any,male,female',
            'furnished' => 'nullable|boolean',
            'total_rooms' => 'nullable|integer|min:1|max:100',
            'rental_mode' => 'required|in:full_property,per_room',

            'amenity_ids' => 'nullable|array|max:20',
            'amenity_ids.*' => 'exists:amenities,id',
            'rules' => 'nullable|string|max:2000',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'images' => 'nullable|array|max:20',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',

            'rooms' => 'nullable|array',
            'rooms.*.id' => 'nullable|exists:rooms,id',
            'rooms.*.room_name' => 'required_with:rooms|string|max:100',
            'rooms.*.room_number' => 'nullable|string|max:50',
            'rooms.*.capacity' => 'required_with:rooms|integer|min:1|max:100',
            'rooms.*.price' => 'required_with:rooms|numeric|min:0|max:999999.99',
            'rooms.*.availability' => 'nullable|boolean',
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
            'rooms.*.capacity.required_with' => 'Room capacity is required when editing rooms',
            'rooms.*.price.required_with' => 'Room price is required when editing rooms',
            'rooms.*.images.*.image' => 'Each room image must be a valid image file',
            'rooms.*.images.*.mimes' => 'Room images must be JPG, PNG, or WebP format',
            'rooms.*.images.*.max' => 'Each room image must not exceed 5MB',
        ];
    }

    /**
     * Custom validation using after hook.
     */
    protected function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $rentalMode = $this->rental_mode;

            Log::info('Property update validation check', [
                'rental_mode' => $rentalMode,
                'rent_price' => $this->rent_price,
                'errors_before' => $validator->errors()->messages(),
            ]);

            if ($rentalMode === 'full_property' && (!$this->rent_price || $this->rent_price <= 0)) {
                $validator->errors()->add(
                    'rent_price',
                    'Rent price is required and must be greater than 0 for full property rentals'
                );
            }

            if ($rentalMode === 'per_room') {
                $property = $this->route('property');
                $existingRoomsCount = $property->rooms()->count();
                $submittedRoomsCount = count($this->input('rooms', []));

                if ($existingRoomsCount === 0 && $submittedRoomsCount === 0) {
                    $validator->errors()->add('rooms', 'At least one room must exist for room-based rental');
                }
            }

            Log::info('Property update validation complete', [
                'errors' => $validator->errors()->messages(),
            ]);
        });
    }
}
