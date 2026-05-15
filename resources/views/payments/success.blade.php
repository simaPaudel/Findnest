@extends('user.layout')

@section('title', 'Booking Confirmed')
@section('page-title', 'Booking Confirmed')

@php
    $property = $booking->property;
    $room = $booking->room;
    $propertyTitle = $property?->title ?? 'Property';
    $propertyLocation = $property?->address ?: ($property?->location ?: ($property?->city ?: 'Location not specified'));
    $propertyImageUrl = $property?->getFirstImageUrl() ?? asset('images/property-placeholder.jpg');
    $bookedItemLabel = $booking->isRoomSpecific() && $room ? $room->room_name : 'Entire property';
    $bookingReference = 'FN-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT);
    $bookingDate = $booking->confirmed_at?->format('M d, Y') ?? $payment->paid_at?->format('M d, Y') ?? $booking->created_at->format('M d, Y');
    $stayDates = $booking->check_in_date->format('M d, Y') . ' to ' . $booking->check_out_date->format('M d, Y');
    $paymentStatusLabel = $payment->getStatusLabel();
    $paymentDate = $payment->paid_at?->format('M d, Y') ?? $bookingDate;
    $ownerId = (int) ($property?->owner_id ?? 0);
    $canMessageOwner = $ownerId > 0 && $ownerId !== (int) auth()->id();
@endphp

@push('styles')
    <style>
        .booking-success-page {
            display: grid;
            gap: 1.5rem;
        }

        .booking-success-hero,
        .booking-success-card {
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            background: #ffffff;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
        }

        .booking-success-hero {
            padding: clamp(1.25rem, 3vw, 2rem);
            border-color: #bbf7d0;
        }

        .booking-success-hero-top {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .booking-success-icon {
            display: inline-flex;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 9999px;
            border: 1px solid #bbf7d0;
            background: #ecfdf5;
            color: #059669;
        }

        .booking-success-kicker {
            margin-bottom: 0.35rem;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #be123c;
        }

        .booking-success-title {
            margin: 0;
            font-size: clamp(1.8rem, 4vw, 2.75rem);
            line-height: 1.08;
            letter-spacing: -0.04em;
            color: #0f172a;
        }

        .booking-success-copy {
            margin-top: 0.85rem;
            max-width: 62ch;
            color: #475569;
            line-height: 1.75;
        }

        .booking-success-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-top: 1.1rem;
        }

        .booking-success-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            border-radius: 9999px;
            border: 1px solid transparent;
            padding: 0.5rem 0.85rem;
            font-size: 0.78rem;
            font-weight: 700;
            line-height: 1;
        }

        .booking-success-badge--success {
            border-color: #bbf7d0;
            background: #ecfdf5;
            color: #047857;
        }

        .booking-success-badge--brand {
            border-color: #fecdd3;
            background: #fff1f2;
            color: #be123c;
        }

        .booking-success-grid {
            display: grid;
            gap: 1.25rem;
        }

        @media (min-width: 1024px) {
            .booking-success-grid {
                grid-template-columns: minmax(0, 1.35fr) minmax(0, 0.85fr);
                align-items: start;
            }
        }

        .booking-success-card {
            padding: 1.25rem;
        }

        .booking-success-media {
            display: grid;
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .booking-success-media {
                grid-template-columns: 132px minmax(0, 1fr);
                align-items: start;
            }
        }

        .booking-success-image {
            width: 100%;
            min-height: 160px;
            border-radius: 18px;
            object-fit: cover;
            background: #f8fafc;
        }

        .booking-success-heading {
            font-size: 1.35rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.03em;
            color: #0f172a;
        }

        .booking-success-subtitle {
            margin-top: 0.35rem;
            color: #64748b;
            line-height: 1.65;
        }

        .booking-success-list {
            display: grid;
            gap: 0.75rem;
            margin-top: 1.1rem;
        }

        @media (min-width: 640px) {
            .booking-success-list {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .booking-success-item {
            min-width: 0;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #f8fafc;
            padding: 0.95rem 1rem;
        }

        .booking-success-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .booking-success-value {
            margin-top: 0.45rem;
            color: #0f172a;
            font-weight: 700;
            line-height: 1.4;
        }

        .booking-success-value--large {
            font-size: 1.35rem;
        }

        .booking-success-payment-note {
            margin-top: 1rem;
            color: #475569;
            line-height: 1.7;
        }

        .booking-success-dl {
            margin-top: 1rem;
            display: grid;
            gap: 0.9rem;
        }

        .booking-success-dl-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            font-size: 0.95rem;
        }

        .booking-success-dl-row dt {
            color: #64748b;
        }

        .booking-success-dl-row dd {
            margin: 0;
            text-align: right;
            font-weight: 700;
            color: #0f172a;
        }

        .booking-success-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .booking-success-actions > * {
            flex: 1 1 180px;
            min-width: 180px;
        }

        .booking-success-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            padding: 0.95rem 1.1rem;
            border: 1px solid transparent;
            font-weight: 700;
            text-decoration: none;
            transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease, box-shadow 0.15s ease;
        }

        .booking-success-button--primary {
            background: #ff385c;
            color: #ffffff;
            box-shadow: 0 10px 18px rgba(255, 56, 92, 0.14);
        }

        .booking-success-button--primary:hover {
            background: #e11d48;
        }

        .booking-success-button--secondary {
            border-color: #bbf7d0;
            background: #ffffff;
            color: #166534;
        }

        .booking-success-button--secondary:hover {
            background: #f0fdf4;
        }

        .booking-success-button--ghost {
            border-color: #e2e8f0;
            background: #ffffff;
            color: #475569;
        }

        .booking-success-button--ghost:hover {
            background: #f8fafc;
        }

        .booking-success-button:disabled {
            cursor: wait;
            opacity: 0.75;
        }

        .booking-success-footer-note {
            margin-top: 1rem;
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.7;
        }
    </style>
@endpush

@section('content')
    <div class="booking-success-page">
        <section class="booking-success-hero">
            <div class="booking-success-hero-top">
                <div class="booking-success-icon" aria-hidden="true">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6L9 17l-5-5"></path>
                    </svg>
                </div>

                <div class="min-w-0">
                    <p class="booking-success-kicker">Payment Verified</p>
                    <h1 class="booking-success-title">Booking Confirmed Successfully</h1>
                    <p class="booking-success-copy">
                        Khalti has verified your payment and FindNest has confirmed your booking.
                        Your reservation details are now saved in your account and ready for review.
                    </p>

                    <div class="booking-success-badges" aria-label="Booking confirmation status">
                        <span class="booking-success-badge booking-success-badge--success">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Payment Verified
                        </span>
                        <span class="booking-success-badge booking-success-badge--brand">
                            <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                            Booking Confirmed
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <div class="booking-success-grid">
            <section class="booking-success-card">
                <div class="booking-success-media">
                    <img
                        src="{{ $propertyImageUrl }}"
                        alt="{{ $propertyTitle }}"
                        class="booking-success-image"
                        onerror="this.src='{{ asset('images/property-placeholder.jpg') }}'">

                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="booking-success-badge booking-success-badge--brand">
                                {{ $booking->isRoomSpecific() ? 'Room booking' : 'Property booking' }}
                            </span>
                            <span class="booking-success-badge booking-success-badge--success">
                                {{ $booking->getStatusLabel() }}
                            </span>
                        </div>

                        <h2 class="mt-3 booking-success-heading">{{ $propertyTitle }}</h2>
                        <p class="booking-success-subtitle">{{ $propertyLocation }}</p>

                        <div class="booking-success-list">
                            <div class="booking-success-item">
                                <div class="booking-success-label">Booking ID</div>
                                <div class="booking-success-value">{{ $bookingReference }}</div>
                            </div>

                            <div class="booking-success-item">
                                <div class="booking-success-label">Property / Room</div>
                                <div class="booking-success-value">{{ $bookedItemLabel }}</div>
                            </div>

                            <div class="booking-success-item">
                                <div class="booking-success-label">Booking Date</div>
                                <div class="booking-success-value">{{ $bookingDate }}</div>
                            </div>

                            <div class="booking-success-item">
                                <div class="booking-success-label">Stay Dates</div>
                                <div class="booking-success-value">{{ $stayDates }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="booking-success-card">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="booking-success-label">Payment Summary</p>
                        <h2 class="mt-2 text-xl font-semibold tracking-tight text-slate-900">Transaction complete</h2>
                    </div>

                    <span class="booking-success-badge booking-success-badge--success">
                        {{ $paymentStatusLabel }}
                    </span>
                </div>

                <div class="mt-5 rounded-2xl border border-emerald-100 bg-emerald-50/80 p-4">
                    <div class="booking-success-label">Amount Paid</div>
                    <div class="booking-success-value booking-success-value--large">@npr($payment->amount)</div>
                    <p class="mt-2 text-sm leading-6 text-emerald-800">
                        Khalti verified this payment successfully and the booking is now confirmed.
                    </p>
                </div>

                <dl class="booking-success-dl">
                    <div class="booking-success-dl-row">
                        <dt>Payment Method</dt>
                        <dd>Khalti</dd>
                    </div>

                    <div class="booking-success-dl-row">
                        <dt>Payment Date</dt>
                        <dd>{{ $paymentDate }}</dd>
                    </div>

                    <div class="booking-success-dl-row">
                        <dt>Booking Status</dt>
                        <dd class="text-emerald-700">{{ $booking->getStatusLabel() }}</dd>
                    </div>
                </dl>

                <p class="booking-success-payment-note">
                    You can view the booking details, contact the owner, or return to the home page from the actions below.
                </p>
            </section>
        </div>

        <section class="booking-success-card">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold tracking-tight text-slate-900">Next Actions</h2>
                    <p class="mt-1 text-sm leading-6 text-slate-500">
                        Continue from your confirmed booking or go back to browsing listings.
                    </p>
                </div>
            </div>

            <div class="booking-success-actions mt-5">
                <a href="{{ route('user.bookings.show', $booking) }}" class="booking-success-button booking-success-button--primary">
                    View Booking
                </a>

                @if($canMessageOwner)
                    <button
                        type="button"
                        class="booking-success-button booking-success-button--secondary"
                        data-message-owner
                        data-contact-url="{{ route('user.conversations.property.create-or-open', ['propertyId' => $property->id]) }}"
                        data-redirect-url="{{ route('user.messages.index') }}"
                        data-default-label="Message Owner"
                        data-loading-label="Opening Messages...">
                        Message Owner
                    </button>
                @endif

                <a href="{{ route('home') }}" class="booking-success-button booking-success-button--ghost">
                    Back to Home
                </a>
            </div>

            <p class="booking-success-footer-note">
                A confirmation record has been saved to your account. If you need help, use the booking details page to review the property and payment record.
            </p>
        </section>
    </div>
@endsection

@push('scripts')
    @if($canMessageOwner)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const button = document.querySelector('[data-message-owner]');
                if (!button) return;

                button.addEventListener('click', async function () {
                    const contactUrl = this.dataset.contactUrl;
                    const redirectUrl = this.dataset.redirectUrl;
                    const defaultLabel = this.dataset.defaultLabel || this.textContent.trim();
                    const loadingLabel = this.dataset.loadingLabel || 'Opening...';
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                    this.disabled = true;
                    this.textContent = loadingLabel;

                    try {
                        const response = await fetch(contactUrl, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify({})
                        });

                        const data = await response.json().catch(function () {
                            return {};
                        });

                        if (!response.ok) {
                            throw new Error(data.message || 'Unable to open the owner conversation.');
                        }

                        if (!data.conversation_id) {
                            throw new Error('Conversation could not be created.');
                        }

                        window.location.href = `${redirectUrl}?conversation=${data.conversation_id}`;
                    } catch (error) {
                        alert(error.message || 'Unable to message the owner right now.');
                        this.disabled = false;
                        this.textContent = defaultLabel;
                    }
                });
            });
        </script>
    @endif
@endpush
