@extends('user.layout')

@section('title', 'Booking Details')
@section('page-title', 'Booking Details')

@section('content')
@php
    $propertyImageUrl = $booking->room?->getFirstImageUrl() ?? $booking->property?->getFirstImageUrl() ?? asset('images/property-placeholder.jpg');
    $bookedItemLabel = $booking->isRoomSpecific() && $booking->room
        ? $booking->room->room_name
        : 'Entire property';
    $totalPaid = (float) $booking->getTotalPaid();
    $advanceTarget = round((float) $booking->total_rent * 0.20, 2);
    $remainingBalance = max((float) $booking->total_rent - $totalPaid, 0);
    $lastPayment = $booking->lastSuccessfulPayment();

    if ($booking->isCancelled()) {
        $statusLabel = 'Booking Cancelled';
        $statusClasses = 'bg-red-50 text-red-700 border-red-200';
        $statusNote = 'This booking has been cancelled.';
    } elseif ($booking->isRejected()) {
        $statusLabel = 'Booking Rejected';
        $statusClasses = 'bg-amber-50 text-amber-700 border-amber-200';
        $statusNote = 'The owner did not accept this booking request.';
    } elseif ($booking->isCompleted()) {
        $statusLabel = 'Booking Completed';
        $statusClasses = 'bg-slate-100 text-slate-700 border-slate-200';
        $statusNote = 'This booking has been completed.';
    } elseif ($booking->isFullyPaid()) {
        $statusLabel = 'Fully Paid';
        $statusClasses = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        $statusNote = 'All dues for this booking have been cleared.';
    } elseif ($booking->hasSuccessfulPayment()) {
        $statusLabel = $booking->isRoomSpecific() ? 'Room Booked' : 'Property Booked';
        $statusClasses = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        $statusNote = 'Advance payment received successfully. The remaining balance is shown below.';
    } else {
        $statusLabel = 'Awaiting Advance Payment';
        $statusClasses = 'bg-amber-50 text-amber-700 border-amber-200';
        $statusNote = 'Pay the 20% advance to place this monthly rental booking.';
    }

    $reviewStatusLabel = null;
    $reviewStatusClasses = null;

    if (!empty($existingReview)) {
        if ($existingReview->is_approved) {
            $reviewStatusLabel = 'Approved';
            $reviewStatusClasses = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        } else {
            $reviewStatusLabel = 'Pending Admin Review';
            $reviewStatusClasses = 'bg-amber-50 text-amber-700 border-amber-200';
        }
    }
@endphp

<div class="space-y-6">
    <a href="{{ route('user.bookings.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 transition hover:text-slate-900">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Bookings
    </a>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-[320px_1fr]">
            <div class="h-72 bg-slate-100 lg:h-full">
                <img
                    src="{{ $propertyImageUrl }}"
                    alt="{{ $booking->property->title }}"
                    class="h-full w-full object-cover"
                    onerror="this.src='{{ asset('images/property-placeholder.jpg') }}'">
            </div>

            <div class="p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-3">
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                {{ $booking->property->getRentalModeLabel() }}
                            </span>
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                {{ $booking->property->getPropertyTypeLabel() }}
                            </span>
                        </div>

                        <div>
                            <h2 class="text-2xl font-semibold text-slate-900">{{ $booking->property->title }}</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                {{ $booking->property->address ?: ($booking->property->location ?: $booking->property->city) }}
                                @if($booking->property->city)
                                    , {{ $booking->property->city }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="rounded-xl border px-4 py-3 text-sm {{ $statusClasses }}">
                        <p class="font-semibold">{{ $statusLabel }}</p>
                        <p class="mt-1 leading-6">{{ $statusNote }}</p>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Booked Unit</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ $bookedItemLabel }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Move-in Date</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ $booking->check_in_date->format('M d, Y') }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Booking Term</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">1 month</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Reference</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900">#{{ str_pad($booking->id, 8, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1.55fr_0.95fr]">
        <div class="space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <h3 class="text-lg font-semibold text-slate-900">Booking Overview</h3>
                    <span class="text-sm text-slate-500">Created {{ $booking->created_at->format('M d, Y') }}</span>
                </div>

                <dl class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="rounded-xl bg-slate-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Rental Plan</dt>
                        <dd class="mt-2 text-sm font-semibold text-slate-900">{{ $booking->property->getRentalModeLabel() }}</dd>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Current Booking Status</dt>
                        <dd class="mt-2 text-sm font-semibold text-slate-900">{{ $statusLabel }}</dd>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Monthly Rent</dt>
                        <dd class="mt-2 text-sm font-semibold text-slate-900">@npr($booking->total_rent)</dd>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Booking For</dt>
                        <dd class="mt-2 text-sm font-semibold text-slate-900">{{ $bookedItemLabel }}</dd>
                    </div>
                </dl>

                @if($booking->special_requests)
                    <div class="mt-5 rounded-xl border border-slate-200 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Special Requests</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600 whitespace-pre-line">{{ $booking->special_requests }}</p>
                    </div>
                @endif

                @if($booking->isRejected() && $booking->rejection_reason)
                    <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4">
                        <p class="text-sm font-semibold text-amber-800">Rejection Reason</p>
                        <p class="mt-2 text-sm leading-6 text-amber-700">{{ $booking->rejection_reason }}</p>
                    </div>
                @endif
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Payment Details</h3>

                <div class="mt-5 space-y-4">
                    <div class="flex items-center justify-between gap-4 border-b border-slate-200 pb-4">
                        <div>
                            <p class="text-sm font-medium text-slate-900">First month rent</p>
                            <p class="mt-1 text-sm text-slate-500">Monthly rental amount used for this booking</p>
                        </div>
                        <p class="text-sm font-semibold text-slate-900">@npr($booking->total_rent)</p>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-900">Advance payment target</p>
                            <p class="mt-1 text-sm text-slate-500">20% of the monthly rent</p>
                        </div>
                        <p class="text-sm font-semibold text-slate-900">@npr($advanceTarget)</p>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-900">Paid so far</p>
                            <p class="mt-1 text-sm text-slate-500">Successful payments received on this booking</p>
                        </div>
                        <p class="text-sm font-semibold text-emerald-600">@npr($totalPaid)</p>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-900">Remaining balance</p>
                            <p class="mt-1 text-sm text-slate-500">Balance still outstanding after advance payment</p>
                        </div>
                        <p class="text-sm font-semibold text-slate-900">@npr($remainingBalance)</p>
                    </div>
                </div>

                <div class="mt-5 rounded-xl border p-4 {{ $booking->hasSuccessfulPayment() ? 'border-emerald-200 bg-emerald-50' : 'border-amber-200 bg-amber-50' }}">
                    <p class="text-sm font-semibold {{ $booking->hasSuccessfulPayment() ? 'text-emerald-700' : 'text-amber-700' }}">
                        {{ $booking->hasSuccessfulPayment() ? 'Advance payment done' : 'Advance payment required' }}
                    </p>
                    <p class="mt-2 text-sm leading-6 {{ $booking->hasSuccessfulPayment() ? 'text-emerald-700' : 'text-amber-700' }}">
                        @if($booking->hasSuccessfulPayment())
                            {{ $booking->isRoomSpecific() ? 'Your room has been booked.' : 'Your property booking has been placed.' }}
                            Remaining balance: @npr($remainingBalance).
                        @else
                            Pay the advance amount to reserve this monthly rental.
                        @endif
                    </p>
                </div>
            </section>

            @if($canReviewBooking || $existingReview)
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Your Review</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-500">
                                Share your experience with this property. Reviews appear on the listing only after admin approval.
                            </p>
                        </div>
                        @if($reviewStatusLabel)
                            <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $reviewStatusClasses }}">
                                {{ $reviewStatusLabel }}
                            </span>
                        @endif
                    </div>

                    @if($existingReview)
                        <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-center gap-1 text-amber-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-4 w-4 {{ $i <= $existingReview->rating ? 'text-amber-400' : 'text-slate-200' }}" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                                <span class="ml-2 text-sm font-medium text-slate-700">{{ $existingReview->rating }}/5</span>
                            </div>
                            <p class="mt-3 text-sm leading-7 text-slate-600">{{ $existingReview->review_text }}</p>
                        </div>
                    @endif

                    <form action="{{ route('user.bookings.review', $booking) }}" method="POST" class="mt-5 space-y-5">
                        @csrf

                        <div>
                            <label class="text-sm font-medium text-slate-900">Rating</label>
                            <div class="mt-3 inline-flex flex-row-reverse items-center justify-end gap-1 review-star-picker">
                                @for($i = 5; $i >= 1; $i--)
                                    <label class="cursor-pointer">
                                        <input
                                            type="radio"
                                            name="rating"
                                            value="{{ $i }}"
                                            class="sr-only"
                                            {{ (int) old('rating', $existingReview?->rating) === $i ? 'checked' : '' }}>
                                        <svg class="h-8 w-8 text-slate-200 transition" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </label>
                                @endfor
                            </div>
                            <p class="mt-2 text-xs text-slate-500">Select 1 to 5 stars for your stay experience.</p>
                            @error('rating')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="review_text" class="text-sm font-medium text-slate-900">Review</label>
                            <textarea
                                id="review_text"
                                name="review_text"
                                rows="5"
                                class="mt-3 w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-rose-400 focus:ring-4 focus:ring-rose-100"
                                placeholder="Write a short and honest review about the property, cleanliness, location, and overall experience.">{{ old('review_text', $existingReview?->review_text) }}</textarea>
                            @error('review_text')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-xs leading-6 text-slate-500">
                                Your review will stay hidden until an admin approves it.
                            </p>
                            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                                {{ $existingReview ? 'Update Review' : 'Submit Review' }}
                            </button>
                        </div>
                    </form>
                </section>
            @endif
        </div>

        <div class="space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Actions</h3>

                <div class="mt-5 space-y-3">
                    @if($booking->hasSuccessfulPayment())
                        <a href="{{ route('user.bookings.download-invoice', $booking) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                            Download Invoice PDF
                        </a>
                    @endif

                    @if(!$booking->hasSuccessfulPayment() && $booking->getAmountPending() > 0 && !$booking->isCancelled() && !$booking->isRejected() && !$booking->isCompleted())
                        <a href="{{ route('user.bookings.bill', $booking) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-rose-700">
                            Pay Advance
                        </a>
                    @endif

                    @if($booking->isPending())
                        <a href="{{ route('user.bookings.edit', $booking) }}" class="inline-flex w-full items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Edit Booking
                        </a>
                    @endif

                    @if($booking->isActive() || $booking->isConfirmed())
                        <form action="{{ route('user.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-full rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700 transition hover:bg-red-100">
                                Cancel Booking
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('user.bookings.bill', $booking) }}" class="inline-flex w-full items-center justify-center rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        View Invoice
                    </a>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Payment Record</h3>

                <dl class="mt-5 space-y-3 text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-slate-500">Payment progress</dt>
                        <dd class="font-semibold text-slate-900">{{ $booking->getPaymentProgress() }}%</dd>
                    </div>
                    @if($lastPayment)
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-slate-500">Advance received on</dt>
                            <dd class="font-semibold text-slate-900">{{ $lastPayment->paid_at?->format('M d, Y') ?? $lastPayment->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-slate-500">Method</dt>
                            <dd class="font-semibold text-slate-900">{{ $lastPayment->getMethodLabel() }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-slate-500">Transaction</dt>
                            <dd class="font-semibold text-slate-900">{{ $lastPayment->transaction_id ?: 'Recorded' }}</dd>
                        </div>
                    @else
                        <div class="rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-500">
                            No successful payment has been recorded yet for this booking.
                        </div>
                    @endif
                </dl>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-900">Timeline</h3>

                <div class="mt-5 space-y-4 text-sm">
                    <div>
                        <p class="font-medium text-slate-900">Booking created</p>
                        <p class="mt-1 text-slate-500">{{ $booking->created_at->format('M d, Y') }}</p>
                    </div>

                    @if($lastPayment)
                        <div>
                            <p class="font-medium text-slate-900">Advance payment done</p>
                            <p class="mt-1 text-slate-500">{{ $lastPayment->paid_at?->format('M d, Y') ?? $lastPayment->created_at->format('M d, Y') }}</p>
                        </div>
                    @endif

                    @if($booking->confirmed_at)
                        <div>
                            <p class="font-medium text-slate-900">Booking confirmed</p>
                            <p class="mt-1 text-slate-500">{{ $booking->confirmed_at->format('M d, Y') }}</p>
                        </div>
                    @endif

                    @if($booking->cancelled_at)
                        <div>
                            <p class="font-medium text-slate-900">Booking cancelled</p>
                            <p class="mt-1 text-slate-500">{{ $booking->cancelled_at->format('M d, Y') }}</p>
                        </div>
                    @endif

                    @if($booking->rejected_at)
                        <div>
                            <p class="font-medium text-slate-900">Booking rejected</p>
                            <p class="mt-1 text-slate-500">{{ $booking->rejected_at->format('M d, Y') }}</p>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
