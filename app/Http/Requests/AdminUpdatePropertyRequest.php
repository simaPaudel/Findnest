<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdatePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
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
            'rental_mode' => 'required|in:full_property,per_room',
            'gender_preference' => 'nullable|in:any,male,female',
            'furnished' => 'nullable|boolean',
            'total_rooms' => 'nullable|integer|min:1|max:100',
            'amenity_ids' => 'nullable|array|max:20',
            'amenity_ids.*' => 'exists:amenities,id',
            'rules' => 'nullable|string|max:2000',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Property title is required.',
            'address.required' => 'Property address is required.',
            'city.required' => 'City is required.',
            'property_type.required' => 'Property type is required.',
            'property_type.in' => 'Invalid property type selected.',
            'rental_mode.required' => 'Rental mode is required.',
            'rental_mode.in' => 'Invalid rental mode selected.',
            'rent_price.numeric' => 'Rent price must be a valid number.',
            'rent_price.min' => 'Rent price cannot be negative.',
            'amenity_ids.*.exists' => 'Invalid amenity selected.',
        ];
    }

    /**
     * Custom validation using after hook.
     */
    protected function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->rental_mode === 'full_property' && (!$this->rent_price || $this->rent_price <= 0)) {
                $validator->errors()->add(
                    'rent_price',
                    'Rent price is required and must be greater than 0 for full property rentals.'
                );
            }
        });
    }
}
