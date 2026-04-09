<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $booking = $this->route('booking');

        // User can only update their own bookings, or owner/admin can update
        return auth()->check() && (
            auth()->id() === $booking->user_id ||
            auth()->user()->isAdmin()
        );
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'advance_payment' => 'nullable|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'total_rent' => 'required|numeric|min:0',
            'special_requests' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages.
     */
    public function messages(): array
    {
        return [
            'total_rent.required' => 'Total rent amount is required.',
            'total_rent.min' => 'Total rent must be a positive number.',
            'special_requests.max' => 'Special requests must not exceed 1000 characters.',
        ];
    }
}
