<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InitiatePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $booking = $this->route('booking');
        return $booking && $booking->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $booking = $this->route('booking');
        $minAmount = $booking?->advance_payment ?? 0;

        return [
            'booking_id' => 'required|exists:bookings,id',
            'amount' => [
                'required',
                'numeric',
                'min:' . $minAmount,
                'max:' . ($booking?->total_rent ?? 999999),
            ],
            'payment_method' => 'required|in:khalti,card_online',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        $booking = $this->route('booking');

        return [
            'amount.min' => "Payment amount must be at least ₨{$booking?->advance_payment}",
            'amount.max' => "Payment amount cannot exceed ₨{$booking?->total_rent}",
            'payment_method.in' => 'Invalid payment method selected',
        ];
    }

    /**
     * Get validated data, merging user context.
     */
    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        if (!is_array($data)) {
            return $data;
        }

        // Ensure booking_id comes from route, not request
        $data['booking_id'] = $this->route('booking')?->id;

        return $data;
    }
}
