@extends('admin.layout')

@section('title', 'Bookings')
@section('page_title', 'Bookings')
@section('hide_pagebar', 'true')

@section('content')
    @php
        $visibleTabs = $tabs;
        $requestedTab = request('tab');
        $displayTab = array_key_exists($requestedTab, $visibleTabs) ? $requestedTab : 'pending';
        $displayTabData = $visibleTabs[$displayTab];
        $heroCount = $displayTabData['count'];
    @endphp

    <div class="admin-bookings-page">
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

        @if (session('error'))
            <div class="booking-alert booking-alert-error">{{ session('error') }}</div>
        @endif

        <section class="content-card admin-bookings-hero">
            <div>
                <p class="page-kicker">Booking Management</p>
                <h2>Bookings</h2>
                <p>Compact booking rows for pending, confirmed, and cancelled records.</p>
            </div>

            <span class="admin-bookings-hero-note">
                {{ $heroCount }} booking{{ $heroCount === 1 ? '' : 's' }}
            </span>
        </section>

        <nav class="admin-bookings-tabs" aria-label="Booking categories">
            @foreach($visibleTabs as $tabKey => $tab)
                <a
                    href="{{ route('admin.bookings.index', array_merge(request()->except(['page', 'tab']), ['tab' => $tabKey])) }}"
                    class="admin-bookings-tab {{ $displayTab === $tabKey ? 'is-active' : '' }}"
                    aria-current="{{ $displayTab === $tabKey ? 'page' : 'false' }}"
                >
                    <span>{{ $tab['label'] }}</span>
                    <small>{{ $tab['count'] }}</small>
                </a>
            @endforeach
        </nav>

        <section class="content-card admin-bookings-section">
            <div class="content-card-header admin-bookings-section-header">
                <div>
                    <h2>{{ $displayTabData['label'] }}</h2>
                    <p>{{ $displayTabData['description'] }}</p>
                </div>
                <span class="admin-bookings-section-count">{{ $displayTabData['count'] }}</span>
            </div>

            <div class="admin-bookings-section-body">
                @if ($bookings->isNotEmpty())
                    <div class="admin-bookings-list">
                        @foreach ($bookings as $booking)
                            @php
                                $property = $booking->property;
                                $propertyTitle = data_get($booking, 'property.title')
                                    ?? data_get($booking, 'property.property_name')
                                    ?? 'Property';
                                $propertyCity = data_get($booking, 'property.city')
                                    ?? data_get($booking, 'property.location')
                                    ?? 'Location not specified';
                                $propertyImageUrl = optional($property)->getFirstImageUrl() ?? asset('images/property-placeholder.jpg');
                                $bookedUserName = $booking->user?->name ?? 'N/A';
                                $bookedUserEmail = $booking->user?->email ?? 'N/A';

                                $statusClass = match ($booking->status) {
                                    'confirmed', 'completed' => 'status-approved',
                                    'pending' => 'status-pending',
                                    'cancelled', 'rejected' => 'status-rejected',
                                    default => 'status-neutral',
                                };

                            @endphp

                            <article class="admin-booking-row">
                                <div class="admin-booking-row-main">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="admin-booking-row-media">
                                        <img
                                            src="{{ $propertyImageUrl }}"
                                            alt="{{ $propertyTitle }}"
                                            onerror="this.src='{{ asset('images/property-placeholder.jpg') }}'"
                                        >
                                    </a>

                                    <div class="admin-booking-row-copy">
                                        <div class="admin-booking-row-badges">
                                            <span class="status-pill {{ $statusClass }}">{{ $booking->getStatusLabel() }}</span>
                                        </div>

                                        <div class="admin-booking-copy-main">
                                            <a href="{{ route('admin.bookings.show', $booking) }}" class="admin-booking-title">
                                                {{ $propertyTitle }}
                                            </a>
                                            <p class="admin-booking-subtitle">{{ $propertyCity }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="admin-booking-row-center">
                                    <p class="admin-booking-userline">
                                        <strong>Booked by {{ $bookedUserName }}</strong>
                                        <span>{{ $bookedUserEmail }}</span>
                                    </p>

                                    <p class="admin-booking-row-meta-inline">
                                        <span>Booking #{{ $booking->id }}</span>
                                        <span>Move-in {{ optional($booking->check_in_date)->format('M d, Y') ?? 'N/A' }}</span>
                                        <span>{{ $booking->getDurationInDays() }} days</span>
                                    </p>
                                </div>

                                <div class="admin-booking-row-actions">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="admin-btn admin-btn-primary admin-btn-sm">
                                        View
                                    </a>

                                    @if ($activeTab === 'pending' && $booking->isPending())
                                        <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}" class="admin-booking-row-form" onsubmit="return confirm('Cancel this booking and move it to the Cancelled tab?')">
                                            @csrf
                                            <button type="submit" class="admin-btn admin-btn-danger-outline admin-btn-sm">
                                                Cancel Booking
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="admin-bookings-empty">{{ $displayTabData['empty'] }}</div>
                @endif
            </div>
        </section>

        @if ($bookings->hasPages())
            <section class="content-card admin-bookings-pagination">
                <div class="admin-bookings-pagination-info">
                    Showing {{ $bookings->firstItem() }}-{{ $bookings->lastItem() }} of {{ $bookings->total() }}
                </div>

                <div class="admin-bookings-pagination-links">
                    @if ($bookings->onFirstPage())
                        <span class="admin-bookings-page-link disabled">Previous</span>
                    @else
                        <a href="{{ $bookings->previousPageUrl() }}" class="admin-bookings-page-link">Previous</a>
                    @endif

                    @foreach (range(1, $bookings->lastPage()) as $page)
                        @if ($page === $bookings->currentPage())
                            <span class="admin-bookings-page-link active">{{ $page }}</span>
                        @else
                            <a href="{{ $bookings->url($page) }}" class="admin-bookings-page-link">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($bookings->hasMorePages())
                        <a href="{{ $bookings->nextPageUrl() }}" class="admin-bookings-page-link">Next</a>
                    @else
                        <span class="admin-bookings-page-link disabled">Next</span>
                    @endif
                </div>
            </section>
        @endif
    </div>

    <style>
        .admin-bookings-page {
            display: grid;
            gap: 16px;
            width: 100%;
            max-width: 1220px;
            margin: 0 auto;
        }

        .booking-alert {
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            background: #fff;
            padding: 16px 18px;
            font-size: 14px;
            line-height: 1.55;
        }

        .booking-alert p {
            margin: 0;
        }

        .booking-alert p + p {
            margin-top: 6px;
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

        .admin-bookings-hero,
        .admin-bookings-section,
        .admin-bookings-pagination {
            border: 1px solid #e5e7eb;
            border-radius: 22px;
            background: #fff;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .admin-bookings-hero {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 18px;
            padding: 16px 18px;
            flex-wrap: wrap;
        }

        .admin-bookings-hero h2 {
            margin: 0 0 6px;
            font-size: 28px;
            line-height: 1.15;
            letter-spacing: -0.03em;
        }

        .admin-bookings-hero p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }

        .admin-bookings-hero-note {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 0 14px;
            border-radius: 999px;
            background: #fff1f2;
            color: #e11d48;
            font-size: 13px;
            font-weight: 800;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .admin-bookings-tabs {
            display: flex;
            align-items: center;
            gap: 20px;
            overflow-x: auto;
            border-bottom: 1px solid #e5e7eb;
            scrollbar-width: none;
        }

        .admin-bookings-tabs::-webkit-scrollbar {
            display: none;
        }

        .admin-bookings-tab {
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

        .admin-bookings-tab span {
            font-size: 13px;
            font-weight: 800;
        }

        .admin-bookings-tab small {
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

        .admin-bookings-tab.is-active {
            color: #111827;
        }

        .admin-bookings-tab.is-active::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -1px;
            height: 2px;
            border-radius: 999px;
            background: #ff385c;
        }

        .admin-bookings-tab:hover {
            color: #111827;
        }

        .admin-bookings-tab.is-active small {
            background: #f8fafc;
            color: #475569;
        }

        .admin-bookings-section {
            overflow: hidden;
        }

        .admin-bookings-section-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            padding: 14px 16px;
            border-bottom: 1px solid #e5e7eb;
            background: #fcfcfd;
        }

        .admin-bookings-section-header h2 {
            margin: 0;
            color: #111827;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .admin-bookings-section-header p {
            margin: 6px 0 0;
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }

        .admin-bookings-section-count {
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

        .admin-bookings-section-body {
            padding: 12px 14px 14px;
        }

        .admin-bookings-list {
            display: grid;
            gap: 8px;
        }

        .admin-booking-row {
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(0, 0.92fr) auto;
            gap: 12px;
            align-items: center;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #fff;
        }

        .admin-booking-row-main {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .admin-booking-row-media {
            width: 70px;
            height: 64px;
            flex-shrink: 0;
            overflow: hidden;
            border-radius: 12px;
            background: #f1f5f9;
        }

        .admin-booking-row-media img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .admin-booking-row-copy {
            min-width: 0;
            display: grid;
            gap: 2px;
        }

        .admin-booking-row-badges {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
            margin-bottom: 0;
        }

        .admin-booking-row .status-pill {
            min-height: 24px;
            padding: 0 9px;
            border-radius: 999px;
            font-size: 10px;
            line-height: 22px;
        }

        .admin-booking-copy-main {
            min-width: 0;
            display: grid;
            gap: 1px;
        }

        .admin-booking-title {
            display: block;
            color: #0f172a;
            font-size: 15px;
            font-weight: 800;
            line-height: 1.25;
            text-decoration: none;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-booking-title:hover {
            color: #e11d48;
        }

        .admin-booking-subtitle {
            margin: 0;
            color: #64748b;
            font-size: 12px;
            line-height: 1.35;
        }

        .admin-booking-row-center {
            min-width: 0;
            display: grid;
            gap: 4px;
            align-content: center;
        }

        .admin-booking-userline {
            margin: 0;
            color: #64748b;
            font-size: 12px;
            line-height: 1.35;
        }

        .admin-booking-userline strong {
            color: #111827;
            font-weight: 800;
            font-size: 13px;
        }

        .admin-booking-userline span {
            display: block;
            margin-top: 3px;
            font-size: 12px;
            word-break: break-word;
        }

        .admin-booking-row-meta-inline {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin: 0;
            color: #64748b;
            font-size: 12px;
            line-height: 1.35;
        }

        .admin-booking-row-meta-inline span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .admin-booking-row-meta-inline span + span::before {
            content: "|";
            color: #cbd5e1;
            margin-right: 8px;
        }

        .admin-booking-row-actions {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: nowrap;
            align-self: center;
            justify-self: end;
        }

        .admin-booking-row-actions .admin-btn {
            min-width: 84px;
            height: 32px;
            min-height: 32px;
            padding: 0 14px;
            line-height: 1;
            white-space: nowrap;
            flex: 0 0 auto;
            justify-content: center;
        }

        .admin-booking-row-form {
            margin: 0;
        }

        .admin-btn-danger-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 32px;
            padding: 0 14px;
            border: 1px solid rgba(255, 56, 92, 0.28);
            border-radius: 999px;
            background: #fff;
            color: #e11d48;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            transition: background 0.18s ease, border-color 0.18s ease, color 0.18s ease, transform 0.18s ease;
        }

        .admin-booking-row-actions .admin-btn-danger-outline {
            min-width: 122px;
            width: auto;
        }

        .admin-booking-row-actions .admin-btn-primary {
            min-width: 84px;
        }

        .admin-btn-danger-outline:hover {
            transform: translateY(-1px);
            background: #fff1f2;
            border-color: rgba(255, 56, 92, 0.38);
            color: #e11d48;
        }

        .admin-bookings-empty {
            padding: 18px;
            color: #64748b;
            font-size: 14px;
            line-height: 1.55;
            text-align: center;
        }

        .admin-bookings-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            padding: 14px 18px 16px;
            border-top: 1px solid #e5e7eb;
        }

        .admin-bookings-pagination-info {
            color: #64748b;
            font-size: 13px;
            line-height: 1.45;
        }

        .admin-bookings-pagination-links {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .admin-bookings-page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
            color: #475569;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
        }

        .admin-bookings-page-link:hover {
            background: #f8fafc;
            border-color: #dbe4ee;
            color: #111827;
        }

        .admin-bookings-page-link.active {
            background: #ff385c;
            border-color: #ff385c;
            color: #fff;
        }

        .admin-bookings-page-link.disabled {
            opacity: 0.45;
            pointer-events: none;
        }

        @media (max-width: 700px) {
            .admin-booking-row {
                grid-template-columns: 1fr;
                align-items: stretch;
            }

            .admin-booking-row-actions {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
                justify-self: stretch;
            }

            .admin-booking-row-actions .admin-btn,
            .admin-btn-danger-outline {
                width: 100%;
                min-width: 0;
            }
        }

        @media (max-width: 700px) {
            .admin-bookings-hero,
            .admin-bookings-section-header {
                padding: 16px;
            }

            .admin-bookings-section-body {
                padding: 12px;
            }

            .admin-booking-row-main {
                align-items: flex-start;
            }

            .admin-booking-row-media {
                width: 60px;
                height: 56px;
            }

            .admin-bookings-pagination {
                padding: 12px 14px 14px;
            }

            .admin-bookings-pagination-info,
            .admin-bookings-pagination-links {
                width: 100%;
            }

            .admin-bookings-pagination-links {
                justify-content: flex-start;
            }
        }
    </style>
@endsection
