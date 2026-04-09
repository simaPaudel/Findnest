<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User must own the property
        $property = $this->route('property');
        return auth()->check() && 
               auth()->user()->isOwner() && 
               $property->owner_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'room_name' => 'required|string|max:100',
            'room_number' => 'nullable|string|max:50',
            'capacity' => 'required|integer|min:1|max:100',
            'price' => 'required|numeric|min:0|max:999999.99',
            'availability' => 'nullable|boolean',
            'room_features' => 'nullable|string|max:1000',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'room_name.required' => 'Room name is required',
            'capacity.required' => 'Room capacity is required',
            'capacity.min' => 'Room capacity must be at least 1',
            'price.required' => 'Room price is required',
            'price.numeric' => 'Room price must be a valid number',
            'images.*.image' => 'Each file must be a valid image',
            'images.*.max' => 'Each image must not exceed 5MB',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if (!$this->has('availability')) {
            $this->merge([
                'availability' => true,
            ]);
        }
    }

    /**
     * Custom validation using after hook.
     */
    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Verify property supports room-based rental
            $property = $this->route('property');
            try {
                $property->validateRoomCreation();
            } catch (\InvalidArgumentException $e) {
                $validator->errors()->add('property', $e->getMessage());
            }
        });
    }
}

