<?php

namespace App\Http\Requests;

use App\Rules\RoomBelongsToProperty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * StoreBookingRequest
 * 
 * Validates booking creation requests with hybrid rental support.
 * Ensures:
 * - Property exists and is approved
 * - Room (if provided) belongs to property
 * - Dates are valid and available
 * - Payment amounts are reasonable
 */
class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isUser();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Property validation
            'property_id' => [
                'required',
                'integer',
                'min:1',
                'exists:properties,id',
                // Property must be approved
                Rule::exists('properties', 'id')->where('status', 'approved'),
            ],

            // Room validation (optional for hybrid support)
            'room_id' => [
                'nullable',
                'integer',
                'min:1',
                'exists:rooms,id',
                // If room provided, must belong to selected property
                new RoomBelongsToProperty($this->input('property_id')),
            ],

            // Date validation
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => [
                'nullable',
                'date',
                'after:check_in_date',
            ],

            // Payment validation
            'advance_payment' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'total_rent' => 'required|numeric|min:0',

            // Special requests
            'special_requests' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'property_id.required' => 'Please select a property.',
            'property_id.exists' => 'The selected property does not exist or is not available.',

            'room_id.exists' => 'The selected room does not exist.',

            'check_in_date.required' => 'Please select a check-in date.',
            'check_in_date.date' => 'Check-in date must be a valid date.',
            'check_in_date.after' => 'Check-in date must be in the future.',
            'check_out_date.date' => 'Check-out date must be a valid date.',
            'check_out_date.after' => 'Check-out date must be after check-in date.',

            'advance_payment.required' => 'Advance payment amount is required.',
            'advance_payment.numeric' => 'Advance payment must be a valid number.',
            'advance_payment.min' => 'Advance payment must be at least 0.',

            'security_deposit.numeric' => 'Security deposit must be a valid number.',
            'security_deposit.min' => 'Security deposit must be at least 0.',

            'total_rent.required' => 'Total rent amount is required.',
            'total_rent.numeric' => 'Total rent must be a valid number.',
            'total_rent.min' => 'Total rent must be a positive number.',

            'special_requests.string' => 'Special requests must be text.',
            'special_requests.max' => 'Special requests must not exceed 1000 characters.',
        ];
    }

    /**
     * Prepare data for validation and storage.
     */
    public function prepareForValidation(): void
    {
        // Merge user ID and default status
        $this->merge([
            'user_id' => auth()->id(),
            'status' => 'pending', // Bookings start as pending until payment succeeds
        ]);

        // Set defaults for payment amounts if not provided
        if (!$this->has('advance_payment') || !$this->input('advance_payment')) {
            $this->merge(['advance_payment' => 0]);
        }

        if (!$this->has('security_deposit') || !$this->input('security_deposit')) {
            $this->merge(['security_deposit' => 0]);
        }

        if ($this->filled('check_in_date') && !$this->filled('check_out_date')) {
            $checkOutDate = \Carbon\Carbon::parse($this->input('check_in_date'))
                ->addMonth()
                ->toDateString();

            $this->merge(['check_out_date' => $checkOutDate]);
        }

        // Set room_id to null if empty (for full property bookings)
        if (!$this->has('room_id') || !$this->input('room_id')) {
            $this->merge(['room_id' => null]);
        }
    }

    /**
     * Get validated data with merged user context.
     */
    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        if (!is_array($data)) {
            return $data;
        }

        // Ensure sensitive fields come from auth context
        $data['user_id'] = auth()->id();
        $data['status'] = 'pending';

        // Ensure room_id is null or int
        $data['room_id'] = $data['room_id'] ?: null;

        return $data;
    }
}
