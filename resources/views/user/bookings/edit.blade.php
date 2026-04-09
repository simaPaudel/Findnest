@extends('user.layout')

@section('title', 'Edit Booking')
@section('page-title', 'Edit Booking')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    {{-- Back Link --}}
    <a href="{{ route('user.bookings.show', $booking) }}" class="fn-link text-sm inline-flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        {{ __('Back to Booking Details') }}
    </a>

    {{-- Header --}}
    <div>
        <h1 class="text-3xl font-bold fn-text-charcoal">{{ __('Edit Booking') }}</h1>
        <p class="fn-text-gray mt-2">{{ $booking->property->property_name }}</p>
    </div>

    {{-- Error Display --}}
    @if ($errors->any())
    <div class="fn-alert-error">
        <p class="font-semibold mb-2">{{ __('Please fix the following errors:') }}</p>
        @foreach ($errors->all() as $error)
        <p class="text-sm">{{ $error }}</p>
        @endforeach
    </div>
    @endif

    {{-- Edit Form Card --}}
    <div class="fn-glass-card">
        <form action="{{ route('user.bookings.update', $booking) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Booking Info (Read-only Summary) --}}
            <div class="pb-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold mb-4">{{ __('Booking Summary') }}</h2>
                <div class="bg-gray-50 p-4 rounded space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="fn-text-gray">{{ __('Check-in:') }}</span>
                        <span class="font-semibold">{{ $booking->check_in_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="fn-text-gray">{{ __('Check-out:') }}</span>
                        <span class="font-semibold">{{ $booking->check_out_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="fn-text-gray">{{ __('Duration:') }}</span>
                        <span class="font-semibold">{{ $booking->getDurationInDays() }} days</span>
                    </div>
                </div>
            </div>

            {{-- Editable Payment Information --}}
            <div class="pb-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold mb-4">{{ __('Payment Information') }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Advance Payment --}}
                    <div>
                        <label for="advance_payment" class="block text-sm font-medium fn-text-charcoal mb-2">
                            {{ __('Advance Payment') }}
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 fn-text-gray">₨</span>
                            <input type="number"
                                id="advance_payment"
                                name="advance_payment"
                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('advance_payment') border-red-500 @enderror"
                                value="{{ old('advance_payment', $booking->advance_payment) }}"
                                step="0.01"
                                min="0"
                                required>
                        </div>
                        @error('advance_payment')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs fn-text-gray mt-1">{{ __('Current: ') }}@npr($booking->advance_payment)</p>
                    </div>

                    {{-- Security Deposit --}}
                    <div>
                        <label for="security_deposit" class="block text-sm font-medium fn-text-charcoal mb-2">
                            {{ __('Security Deposit') }}<span class="fn-text-gray"> ({{ __('Optional') }})</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 fn-text-gray">₨</span>
                            <input type="number"
                                id="security_deposit"
                                name="security_deposit"
                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('security_deposit') border-red-500 @enderror"
                                value="{{ old('security_deposit', $booking->security_deposit) }}"
                                step="0.01"
                                min="0">
                        </div>
                        @error('security_deposit')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs fn-text-gray mt-1">{{ __('Current: ') }}@npr($booking->security_deposit)</p>
                    </div>
                </div>

                {{-- Total Rent Display --}}
                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold fn-text-charcoal">{{ __('Total Rent:') }}</span>
                        <span class="text-lg font-bold fn-text-red">@npr($booking->total_rent)</span>
                    </div>
                    <p class="text-xs fn-text-gray mt-2">
                        {{ __('Total rent is calculated based on your booking dates and cannot be changed.') }}
                    </p>
                </div>
            </div>

            {{-- Special Requests --}}
            <div class="pb-6 border-b border-gray-200">
                <label for="special_requests" class="block text-sm font-medium fn-text-charcoal mb-2">
                    {{ __('Special Requests') }}<span class="fn-text-gray"> ({{ __('Optional') }})</span>
                </label>
                <textarea id="special_requests"
                    name="special_requests"
                    rows="5"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('special_requests') border-red-500 @enderror"
                    placeholder="{{ __('Any additional requests or requirements...') }}">{{ old('special_requests', $booking->special_requests) }}</textarea>
                @error('special_requests')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs fn-text-gray mt-1">{{ __('Maximum 1000 characters') }}</p>
            </div>

            {{-- Summary Card --}}
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h3 class="font-semibold mb-3">{{ __('Updated Summary') }}</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="fn-text-gray">{{ __('Total Rent:') }}</span>
                        <span class="font-semibold">@npr($booking->total_rent)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="fn-text-gray">{{ __('Advance Payment:') }}</span>
                        <span class="font-semibold" id="summaryAdvance">@npr($booking->advance_payment)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="fn-text-gray">{{ __('Security Deposit:') }}</span>
                        <span class="font-semibold" id="summaryDeposit">@npr($booking->security_deposit)</span>
                    </div>
                    <div class="border-t pt-2 flex justify-between font-semibold">
                        <span>{{ __('Total Due:') }}</span>
                        <span id="summaryTotal" class="fn-text-red">@npr($booking->advance_payment + $booking->security_deposit)</span>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('user.bookings.show', $booking) }}" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 font-semibold transition">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition ml-auto">
                    {{ __('Save Changes') }}
                </button>
            </div>
        </form>
    </div>

    {{-- Info Box --}}
    <div class="fn-glass-card bg-blue-50 border border-blue-200">
        <h3 class="font-semibold mb-2 text-blue-900">{{ __('Note:') }}</h3>
        <p class="fn-text-gray text-sm">
            {{ __('You can only edit payment amounts and special requests for pending bookings. Once confirmed, you cannot change booking details. To modify dates or room selection, please cancel and create a new booking.') }}
        </p>
    </div>
</div>

<script>
    const advancePaymentInput = document.getElementById('advance_payment');
    const securityDepositInput = document.getElementById('security_deposit');
    const totalRent = parseFloat('{{ $booking->total_rent }}');

    function updateSummary() {
        const advance = parseFloat(advancePaymentInput.value) || 0;
        const deposit = parseFloat(securityDepositInput.value) || 0;
        const total = advance + deposit;

        document.getElementById('summaryAdvance').textContent = '₨' + advance.toLocaleString();
        document.getElementById('summaryDeposit').textContent = '₨' + deposit.toLocaleString();
        document.getElementById('summaryTotal').textContent = '₨' + total.toLocaleString();
    }

    advancePaymentInput?.addEventListener('change', updateSummary);
    advancePaymentInput?.addEventListener('keyup', updateSummary);
    securityDepositInput?.addEventListener('change', updateSummary);
    securityDepositInput?.addEventListener('keyup', updateSummary);
</script>
@endsection