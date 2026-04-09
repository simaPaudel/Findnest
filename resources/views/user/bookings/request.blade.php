@extends('user.layout')

@section('title', 'Request Booking')
@section('page-title', 'Request Booking')

@section('content')
@php
    $selectedRoomId = old('room_id', $selectedRoom?->id);
    $baseMonthlyRent = $property->canRentRooms()
        ? (float) ($selectedRoom?->price ?? ($rooms->min('price') ?? 0))
        : (float) $property->rent_price;

    $roomPricing = $rooms->mapWithKeys(function ($room) {
        return [$room->id => (float) $room->price];
    });

    $propertyImageUrl = $property->getFirstImageUrl();
@endphp

<div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 xl:grid-cols-[1.45fr_0.9fr] gap-8 items-start">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-8 py-8 border-b border-slate-200 bg-gradient-to-br from-white via-slate-50 to-rose-50">
                <div class="flex flex-col md:flex-row gap-6 md:items-start md:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">FindNest Booking</p>
                        <h2 class="mt-2 text-4xl font-bold text-slate-950 leading-tight">Book {{ $property->title }}</h2>
                        <p class="mt-3 text-base text-slate-600">
                            @if($property->canRentRooms())
                                Select your room, confirm your dates, and proceed with the 20% advance payment.
                            @else
                                Confirm your stay details and continue with the 20% advance payment.
                            @endif
                        </p>
                    </div>
                    <div class="w-full md:w-48 h-32 rounded-2xl overflow-hidden bg-slate-100 shadow-sm shrink-0">
                        <img src="{{ $propertyImageUrl }}" alt="{{ $property->title }}" class="w-full h-full object-cover"
                            onerror="this.src='https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=400&h=260&fit=crop'">
                    </div>
                </div>
            </div>

            <div class="p-8">
                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4">
                        <h3 class="font-semibold text-red-800 mb-2">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('user.bookings.create') }}" method="POST" id="bookingForm" class="space-y-8">
                    @csrf
                    <input type="hidden" name="property_id" value="{{ $property->id }}">
                    <input type="hidden" name="check_out_date" id="check_out_date">
                    <input type="hidden" name="total_rent" id="total_rent_input" value="0">
                    <input type="hidden" name="advance_payment" id="advance_payment_input" value="0">
                    <input type="hidden" name="security_deposit" value="0">

                    <section class="space-y-4">
                        <div class="flex items-end justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-bold text-slate-950">Property Information</h3>
                                <p class="text-sm text-slate-500 mt-1">Overview of the property you are booking.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Property</p>
                                <p class="mt-2 font-semibold text-slate-900">{{ $property->title }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Location</p>
                                <p class="mt-2 font-semibold text-slate-900">{{ $property->city ?: ($property->location ?: 'Not specified') }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Rental Mode</p>
                                <p class="mt-2 font-semibold text-slate-900">{{ $property->rental_mode === 'per_room' ? 'Individual Room' : 'Full Property' }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-5">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Base Monthly Rent</p>
                                <p class="mt-2 font-semibold text-slate-900">Rs {{ number_format($baseMonthlyRent) }}</p>
                            </div>
                        </div>
                    </section>

                    @if($property->canRentRooms())
                        <section class="pt-8 border-t border-slate-200">
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-slate-950">Choose Your Room</h3>
                                <p class="text-sm text-slate-500 mt-1">Pick the room you want to reserve from this property.</p>
                            </div>

                            @if($rooms->isNotEmpty())
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    @foreach($rooms as $room)
                                        @php
                                            $roomImage = $room->images->firstWhere('is_primary', true) ?? $room->images->sortBy('order')->first();
                                            $roomImageUrl = $roomImage ? $roomImage->getUrl() : $propertyImageUrl;
                                        @endphp
                                        <label class="block cursor-pointer">
                                            <input
                                                type="radio"
                                                name="room_id"
                                                value="{{ $room->id }}"
                                                class="sr-only room-option"
                                                data-price="{{ (float) $room->price }}"
                                                {{ (string) $selectedRoomId === (string) $room->id ? 'checked' : '' }}>
                                            <div class="room-card rounded-3xl border border-slate-200 overflow-hidden transition hover:border-rose-300 hover:shadow-md bg-white">
                                                <div class="h-44 bg-slate-100">
                                                    <img src="{{ $roomImageUrl }}" alt="{{ $room->room_name }}" class="w-full h-full object-cover">
                                                </div>
                                                <div class="p-5 space-y-3">
                                                    <div class="flex items-start justify-between gap-3">
                                                        <div>
                                                            <h4 class="text-lg font-bold text-slate-900">{{ $room->room_name }}</h4>
                                                            <p class="text-sm text-slate-500">{{ $room->room_features ?: 'Room option inside this property' }}</p>
                                                        </div>
                                                        <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">Available</span>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-3 rounded-2xl bg-slate-50 p-4 text-sm">
                                                        <div>
                                                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Price</p>
                                                            <p class="mt-1 font-bold text-slate-900">Rs {{ number_format((float) $room->price) }}/month</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Capacity</p>
                                                            <p class="mt-1 font-semibold text-slate-700">{{ $room->capacity }} {{ $room->capacity === 1 ? 'person' : 'people' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-sm text-slate-500">
                                    No rooms are currently available for booking in this property.
                                </div>
                            @endif
                        </section>
                    @endif

                    <section class="pt-8 border-t border-slate-200">
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-slate-950">Stay Details</h3>
                            <p class="text-sm text-slate-500 mt-1">Choose your move-in date. The initial booking term is fixed for 1 month.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="check_in_date" class="block text-sm font-medium text-slate-700 mb-1">Move-in Date <span class="text-red-500">*</span></label>
                                <input type="date" id="check_in_date" name="check_in_date" min="{{ now()->toDateString() }}" required value="{{ old('check_in_date') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-rose-500">
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-5 border border-slate-200">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Initial Booking Term</p>
                                <p class="mt-2 text-lg font-bold text-slate-900">1 Month</p>
                                <p class="mt-1 text-sm text-slate-500">Checkout date is not required for this rental request.</p>
                            </div>
                        </div>

                        <div class="mt-4 rounded-2xl bg-blue-50 px-4 py-3 text-sm text-blue-700 font-medium" id="durationDisplay">
                            Initial booking term: 1 month.
                        </div>
                    </section>

                    <section class="pt-8 border-t border-slate-200">
                        <label for="special_requests" class="block text-sm font-medium text-slate-700 mb-2">Special Requests <span class="text-slate-400">(Optional)</span></label>
                        <textarea id="special_requests" name="special_requests" rows="4" class="w-full rounded-2xl border border-slate-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-rose-500" placeholder="Any special requests or requirements?">{{ old('special_requests') }}</textarea>
                    </section>

                    <div class="pt-6 border-t border-slate-200 space-y-4">
                        <label class="flex items-start gap-3">
                            <input type="checkbox" id="agreeTerms" class="mt-1" required>
                            <span class="text-sm text-slate-600">I agree to the rental terms and confirm that the information provided is accurate.</span>
                        </label>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('listings.show', $property) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-6 py-3 font-semibold text-slate-700 hover:bg-slate-50">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-rose-500 px-6 py-3 font-semibold text-white hover:bg-rose-600 {{ $property->canRentRooms() && $rooms->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $property->canRentRooms() && $rooms->isEmpty() ? 'disabled' : '' }}>
                                Continue to Bill
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <aside class="space-y-6 xl:sticky xl:top-24">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <p class="text-sm font-semibold uppercase tracking-wide text-slate-400">Payment Summary</p>
                    <h3 class="mt-2 text-2xl font-bold text-slate-950">Pay 20% Now</h3>
                    <p class="mt-2 text-sm text-slate-500">Only the 20% advance payment is charged now. The remaining balance stays due later.</p>
                </div>

                <div class="p-6 space-y-4 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Monthly Rent</span>
                        <span class="font-semibold text-slate-900" id="monthlyRent">Rs {{ number_format($baseMonthlyRent) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Duration</span>
                        <span class="font-semibold text-slate-900" id="durationDisplay2">1 month</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-200 pt-4">
                        <span class="text-slate-500">Total Rent</span>
                        <span class="font-semibold text-slate-900" id="totalRent">Rs 0</span>
                    </div>
                    <div class="rounded-2xl bg-rose-50 border border-rose-200 px-4 py-4">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="font-semibold text-slate-900">Advance Due Now</p>
                                <p class="mt-1 text-xs text-slate-500">Exact 20% payable amount</p>
                            </div>
                            <span class="text-2xl font-bold text-rose-600" id="dueNow">Rs 0</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-200 pt-4">
                        <span class="text-slate-500">Remaining 80%</span>
                        <span class="font-semibold text-slate-900" id="remainingBalance">Rs 0</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                <p class="text-sm font-semibold text-slate-900">Professional billing flow</p>
                <p class="mt-2 text-sm text-slate-500">After this step, the bill screen will show the exact payable advance amount again before payment.</p>
            </div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkInDate = document.getElementById('check_in_date');
        const checkOutDate = document.getElementById('check_out_date');
        const totalRentInput = document.getElementById('total_rent_input');
        const advancePaymentInput = document.getElementById('advance_payment_input');
        const roomOptions = document.querySelectorAll('.room-option');
        const roomCards = document.querySelectorAll('.room-card');
        const roomPricing = @json($roomPricing);
        const propertyMonthlyRent = {{ json_encode((float) $property->rent_price) }};

        function getCurrentMonthlyRent() {
            const selectedRoom = document.querySelector('.room-option:checked');
            if (selectedRoom) {
                return parseFloat(selectedRoom.dataset.price || roomPricing[selectedRoom.value] || 0);
            }
            return parseFloat(propertyMonthlyRent || 0);
        }

        function formatMoney(amount) {
            return `Rs ${Math.round(amount).toLocaleString()}`;
        }

        function updateRoomSelectionStyles() {
            roomOptions.forEach((option, index) => {
                if (!roomCards[index]) {
                    return;
                }
                roomCards[index].classList.toggle('border-rose-500', option.checked);
                roomCards[index].classList.toggle('ring-2', option.checked);
                roomCards[index].classList.toggle('ring-rose-200', option.checked);
            });
        }

        function updateDurationAndRent() {
            const monthlyRent = getCurrentMonthlyRent();
            const checkIn = checkInDate.value ? new Date(checkInDate.value) : null;

            document.getElementById('monthlyRent').textContent = formatMoney(monthlyRent);

            if (checkIn) {
                const computedCheckout = new Date(checkIn);
                computedCheckout.setMonth(computedCheckout.getMonth() + 1);

                const totalRent = Math.ceil(monthlyRent);
                const dueNow = Math.ceil(totalRent * 0.2);
                const remainingBalance = totalRent - dueNow;

                document.getElementById('durationDisplay').textContent = 'Initial booking term: 1 month.';
                document.getElementById('durationDisplay2').textContent = '1 month';
                document.getElementById('totalRent').textContent = formatMoney(totalRent);
                document.getElementById('dueNow').textContent = formatMoney(dueNow);
                document.getElementById('remainingBalance').textContent = formatMoney(remainingBalance);

                checkOutDate.value = computedCheckout.toISOString().split('T')[0];
                totalRentInput.value = totalRent;
                advancePaymentInput.value = dueNow;
            } else {
                document.getElementById('durationDisplay').textContent = 'Initial booking term: 1 month.';
                document.getElementById('durationDisplay2').textContent = '1 month';
                document.getElementById('totalRent').textContent = 'Rs 0';
                document.getElementById('dueNow').textContent = 'Rs 0';
                document.getElementById('remainingBalance').textContent = 'Rs 0';
                checkOutDate.value = '';
                totalRentInput.value = 0;
                advancePaymentInput.value = 0;
            }
        }

        roomOptions.forEach((option) => {
            option.addEventListener('change', function() {
                updateRoomSelectionStyles();
                updateDurationAndRent();
            });
        });

        checkInDate.addEventListener('change', updateDurationAndRent);

        updateRoomSelectionStyles();
        updateDurationAndRent();
    });
</script>
@endpush
