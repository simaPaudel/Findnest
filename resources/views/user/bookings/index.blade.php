@extends('user.layout')

@section('title', 'My Bookings')
@section('page-title', 'My Bookings')

@section('content')
@php
    $tabs = [
        'pending' => [
            'label' => 'Pending',
            'count' => $pendingBookings->count(),
            'description' => 'Bookings waiting for payment or confirmation.',
            'empty' => 'No pending bookings right now.',
        ],
        'paid' => [
            'label' => 'Paid / Confirmed',
            'count' => $confirmedPaidBookings->count(),
            'description' => 'Bookings with successful payment or confirmed stay status.',
            'empty' => 'No paid or confirmed bookings yet.',
        ],
        'cancelled' => [
            'label' => 'Cancelled',
            'count' => $cancelledBookings->count(),
            'description' => 'Bookings that were cancelled and kept for your records.',
            'empty' => 'No cancelled bookings.',
        ],
    ];
@endphp

<div class="booking-page">
    <div class="booking-page-header">
        <div>
            <p class="booking-kicker">Booking Management</p>
            <p>Review pending payments, confirmed stays, invoices, and cancelled records from one clean dashboard.</p>
        </div>

        <div class="booking-header-actions">
            <span>{{ $bookings->count() }} {{ $bookings->count() === 1 ? 'booking' : 'bookings' }}</span>
            <a href="{{ route('listings.index') }}" class="booking-btn booking-btn-primary">Browse Properties</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="booking-alert booking-alert-error">
            <strong>Error</strong>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if (session('success'))
        <div class="booking-alert booking-alert-success">{{ session('success') }}</div>
    @endif

    <nav class="booking-tabs" aria-label="Booking categories">
        @foreach($tabs as $tabKey => $tab)
            <a href="{{ route('user.bookings.index', ['tab' => $tabKey]) }}"
                class="booking-tab {{ $activeTab === $tabKey ? 'is-active' : '' }}"
                aria-current="{{ $activeTab === $tabKey ? 'page' : 'false' }}">
                <span>{{ $tab['label'] }}</span>
                <small>{{ $tab['count'] }}</small>
            </a>
        @endforeach
    </nav>

    <section class="booking-section">
        <div class="booking-section-header">
            <div>
                <h3>{{ $tabs[$activeTab]['label'] }}</h3>
                <p>{{ $tabs[$activeTab]['description'] }}</p>
            </div>
            <span>{{ $activeBookings->count() }}</span>
        </div>

        @if($activeBookings->isNotEmpty())
            <div class="booking-list">
                @foreach($activeBookings as $booking)
                    @php
                        $property = $booking->property;
                        $propertyTitle = $property->title ?? $property->property_name ?? 'Property';
                        $propertyCity = $property->city ?? ($property->location ?? 'Location not specified');
                        $propertyImage = $property && method_exists($property, 'getFirstImageUrl')
                            ? $property->getFirstImageUrl()
                            : asset('images/property-placeholder.jpg');

                        if ($booking->isConfirmed() || $booking->hasSuccessfulPayment()) {
                            $statusClasses = 'booking-status-success';
                            $statusLabel = $booking->isFullyPaid() ? 'Fully Paid' : ($booking->isConfirmed() ? 'Confirmed' : 'Paid');
                        } elseif ($booking->isPending()) {
                            $statusClasses = 'booking-status-pending';
                            $statusLabel = 'Pending';
                        } elseif ($booking->isCancelled()) {
                            $statusClasses = 'booking-status-danger';
                            $statusLabel = 'Cancelled';
                        } else {
                            $statusClasses = 'booking-status-neutral';
                            $statusLabel = ucfirst($booking->status ?? 'Status');
                        }

                        $paidAmount = (float) $booking->getTotalPaid();
                        $amountPending = (float) $booking->getAmountPending();
                        $hasPaymentRecord = $booking->hasSuccessfulPayment() && $paidAmount > 0;
                        $canPay = $activeTab === 'pending'
                            && $booking->isPending()
                            && ! $booking->hasSuccessfulPayment()
                            && $amountPending > 0;
                        $canCancel = $activeTab === 'pending'
                            && $booking->isPending()
                            && ! $booking->hasSuccessfulPayment()
                            && ! $booking->isCancelled();
                        $canShowInvoice = $activeTab === 'paid' && $hasPaymentRecord;
                    @endphp

                    <article class="booking-card">
                        <div class="booking-card-main">
                            <a href="{{ route('listings.show', $booking->property->id) }}" class="booking-card-image">
                                <img src="{{ $propertyImage }}"
                                    alt="{{ $propertyTitle }}"
                                    onerror="this.src='{{ asset('images/property-placeholder.jpg') }}'">
                            </a>

                            <div class="booking-card-copy">
                                <div class="booking-badge-row">
                                    <span class="booking-status {{ $statusClasses }}">{{ $statusLabel }}</span>
                                    <span class="booking-type">
                                        {{ $booking->isRoomSpecific() && $booking->room ? 'Room: ' . $booking->room->room_name : 'Full Property' }}
                                    </span>
                                </div>

                                <a href="{{ route('listings.show', $booking->property->id) }}" class="booking-title">{{ $propertyTitle }}</a>
                                <p class="booking-location">{{ $propertyCity }}</p>
                            </div>
                        </div>

                        <div class="booking-meta-grid">
                            <div class="booking-meta-card">
                                <span>Move-in</span>
                                <strong>{{ $booking->check_in_date->format('M d, Y') }}</strong>
                                <small>{{ $booking->getDurationInDays() }} days</small>
                            </div>
                            <div class="booking-meta-card">
                                <span>Total Rent</span>
                                <strong>@npr($booking->total_rent)</strong>
                                <small>{{ $booking->getPaymentProgress() }}% paid</small>
                            </div>
                            <div class="booking-meta-card">
                                <span>{{ $activeTab === 'paid' ? 'Paid' : 'Balance' }}</span>
                                <strong>@npr($activeTab === 'paid' ? $paidAmount : $amountPending)</strong>
                                <small>
                                    @if($activeTab === 'paid')
                                        {{ $hasPaymentRecord ? 'Payment recorded' : 'No payment recorded' }}
                                    @else
                                        Amount due
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="booking-card-actions">
                            @if($activeTab === 'pending')
                                <a href="{{ route('user.bookings.show', $booking) }}" class="booking-btn booking-btn-secondary">View Details</a>

                                @if($canPay)
                                    <a href="{{ route('user.bookings.bill', $booking) }}" class="booking-btn booking-btn-primary">Pay</a>
                                @endif

                                @if($canCancel)
                                    <form action="{{ route('user.bookings.cancel', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="booking-btn booking-btn-danger">Cancel</button>
                                    </form>
                                @endif
                            @elseif($activeTab === 'paid')
                                @if($canShowInvoice)
                                    <a href="{{ route('user.bookings.bill', $booking) }}" class="booking-btn booking-btn-secondary">View Invoice</a>
                                    <a href="{{ route('user.bookings.download-invoice', $booking) }}" class="booking-btn booking-btn-dark">Download Invoice</a>
                                @else
                                    <a href="{{ route('user.bookings.show', $booking) }}" class="booking-btn booking-btn-secondary">View Details</a>
                                @endif
                            @else
                                <a href="{{ route('user.bookings.show', $booking) }}" class="booking-btn booking-btn-secondary">View Details</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="booking-empty-small">{{ $tabs[$activeTab]['empty'] }}</div>
        @endif
    </section>
</div>

<style>
    .booking-page {
        display: grid;
        gap: 20px;
    }

    .booking-page-header,
    .booking-section {
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }

    .booking-page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 18px;
        padding: 22px;
        flex-wrap: wrap;
    }

    .booking-kicker {
        margin: 0 0 6px;
        color: #ff385c;
        font-size: 13px;
        font-weight: 700;
    }

    .booking-page-header p,
    .booking-section-header p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
    }

    .booking-header-actions,
    .booking-card-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
    }

    .booking-header-actions span {
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
    }

    .booking-tabs {
        display: flex;
        align-items: center;
        gap: 24px;
        overflow-x: auto;
        border-bottom: 1px solid #e5e7eb;
        scrollbar-width: none;
    }

    .booking-tabs::-webkit-scrollbar {
        display: none;
    }

    .booking-tab {
        position: relative;
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 0 0 12px;
        color: #475569;
        text-decoration: none;
        white-space: nowrap;
        transition: color 0.18s ease;
    }

    .booking-tab span {
        font-size: 13px;
        font-weight: 800;
    }

    .booking-tab small {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 21px;
        height: 21px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
    }

    .booking-tab.is-active {
        color: #111827;
    }

    .booking-tab.is-active::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: -1px;
        height: 2px;
        border-radius: 999px;
        background: #ff385c;
    }

    .booking-tab:hover {
        color: #111827;
    }

    .booking-tab.is-active small {
        background: #f8fafc;
        color: #475569;
    }

    .booking-section {
        overflow: hidden;
    }

    .booking-section-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        padding: 18px 20px;
        border-bottom: 1px solid #e5e7eb;
        background: #fcfcfd;
    }

    .booking-section-header h3 {
        margin: 0;
        color: #111827;
        font-size: 18px;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .booking-section-header > span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        border-radius: 999px;
        background: #fff1f2;
        color: #e11d48;
        font-size: 13px;
        font-weight: 800;
    }

    .booking-list {
        display: grid;
        gap: 14px;
        padding: 16px;
    }

    .booking-card {
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) minmax(280px, 0.9fr) minmax(180px, auto);
        gap: 16px;
        align-items: center;
        padding: 14px;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        background: #fff;
    }

    .booking-card-main {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }

    .booking-card-image {
        width: 84px;
        height: 84px;
        flex-shrink: 0;
        overflow: hidden;
        border-radius: 16px;
        background: #f1f5f9;
    }

    .booking-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .booking-card-copy {
        min-width: 0;
    }

    .booking-badge-row {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }

    .booking-status,
    .booking-type {
        display: inline-flex;
        align-items: center;
        min-height: 25px;
        padding: 0 9px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        line-height: 1;
    }

    .booking-status-success {
        border: 1px solid #bbf7d0;
        background: #ecfdf5;
        color: #047857;
    }

    .booking-status-pending {
        border: 1px solid #fde68a;
        background: #fffbeb;
        color: #b45309;
    }

    .booking-status-danger {
        border: 1px solid #fecdd3;
        background: #fff1f2;
        color: #be123c;
    }

    .booking-status-neutral,
    .booking-type {
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
    }

    .booking-title {
        display: block;
        overflow: hidden;
        color: #0f172a;
        font-size: 15px;
        font-weight: 800;
        text-decoration: none;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .booking-title:hover {
        color: #e11d48;
    }

    .booking-location {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.5;
    }

    .booking-meta-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }

    .booking-meta-card {
        min-width: 0;
        padding: 12px;
        border-radius: 14px;
        background: #f8fafc;
    }

    .booking-meta-card span {
        display: block;
        color: #94a3b8;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .booking-meta-card strong {
        display: block;
        margin-top: 6px;
        color: #111827;
        font-size: 14px;
        line-height: 1.35;
    }

    .booking-meta-card small {
        display: block;
        margin-top: 4px;
        color: #64748b;
        font-size: 12px;
    }

    .booking-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 0 13px;
        border: 1px solid transparent;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.18s ease, border-color 0.18s ease, color 0.18s ease, transform 0.18s ease;
    }

    .booking-btn:hover {
        transform: translateY(-1px);
    }

    .booking-btn-primary {
        background: #ff385c;
        color: #fff;
    }

    .booking-btn-primary:hover {
        background: #e11d48;
    }

    .booking-btn-secondary {
        border-color: #e5e7eb;
        background: #fff;
        color: #334155;
    }

    .booking-btn-secondary:hover {
        background: #f8fafc;
    }

    .booking-btn-dark {
        background: #111827;
        color: #fff;
    }

    .booking-btn-danger {
        border-color: #fecdd3;
        background: #fff1f2;
        color: #be123c;
    }

    .booking-alert,
    .booking-empty-small {
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        background: #fff;
        padding: 16px;
        color: #64748b;
        font-size: 14px;
    }

    .booking-alert-error {
        border-color: #fecaca;
        background: #fef2f2;
        color: #b91c1c;
    }

    .booking-alert-success {
        border-color: #bbf7d0;
        background: #ecfdf5;
        color: #047857;
    }

    @media (max-width: 1200px) {
        .booking-card {
            grid-template-columns: 1fr;
            align-items: stretch;
        }

        .booking-card-actions {
            justify-content: flex-start;
        }
    }

    @media (max-width: 700px) {
        .booking-page-header,
        .booking-section-header {
            padding: 18px;
        }

        .booking-header-actions,
        .booking-card-actions {
            width: 100%;
            justify-content: stretch;
        }

        .booking-btn,
        .booking-card-actions form {
            width: 100%;
        }

        .booking-card-main {
            align-items: flex-start;
        }

        .booking-card-image {
            width: 72px;
            height: 72px;
        }

        .booking-meta-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
