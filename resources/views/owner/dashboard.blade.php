@extends('owner.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="owner-dashboard-page">
    <section class="owner-dashboard-hero">
        <div class="owner-dashboard-copy-block">
            <p class="owner-dashboard-kicker">Overview</p>
            <h2 class="owner-dashboard-title">Dashboard</h2>
            <p class="owner-dashboard-copy">Manage listings, bookings, and activity.</p>

            <div class="owner-dashboard-summary" aria-label="Owner dashboard summary">
                <span>{{ $activeListings }} active {{ $activeListings === 1 ? 'listing' : 'listings' }}</span>
                <span>{{ $pendingBookingRequests }} pending {{ $pendingBookingRequests === 1 ? 'request' : 'requests' }}</span>
                <span>{{ number_format($avgRating, 1) }} average rating</span>
            </div>
        </div>

        <a href="{{ route('owner.listings.create') }}" class="owner-dashboard-cta">Add Property</a>
    </section>

    <section class="owner-stat-grid" aria-label="Dashboard metrics">
        <article class="owner-stat-card owner-stat-card--neutral">
            <p class="owner-stat-label">Total Properties</p>
            <div class="owner-stat-value">{{ $totalListings }}</div>
            <p class="owner-stat-note">All properties in your portfolio</p>
            <span class="owner-stat-chip">Portfolio</span>
        </article>

        <article class="owner-stat-card owner-stat-card--active">
            <p class="owner-stat-label">Active Properties</p>
            <div class="owner-stat-value">{{ $activeListings }}</div>
            <p class="owner-stat-note">Currently visible and bookable</p>
            <span class="owner-stat-chip">Live</span>
        </article>

        <article class="owner-stat-card owner-stat-card--pending">
            <p class="owner-stat-label">Pending Requests</p>
            <div class="owner-stat-value">{{ $pendingBookingRequests }}</div>
            <p class="owner-stat-note">Needs review or action</p>
            <span class="owner-stat-chip">Action</span>
        </article>

        <article class="owner-stat-card owner-stat-card--rating">
            <p class="owner-stat-label">Average Rating</p>
            <div class="owner-stat-value">{{ number_format($avgRating, 1) }}</div>
            <p class="owner-stat-note">{{ $reviewsCount }} {{ $reviewsCount === 1 ? 'review' : 'reviews' }}</p>
            <span class="owner-stat-chip">Trust</span>
        </article>
    </section>

    <section class="owner-section-card owner-booking-section">
        <div class="owner-section-header">
            <div>
                <h3 class="owner-section-title">Management Actions</h3>
                <p class="owner-section-copy">Quick access to the most common owner tasks.</p>
            </div>
        </div>

        <div class="owner-action-grid" aria-label="Quick actions">
            <a href="{{ route('owner.listings.index') }}" class="owner-action-card">
                <span class="owner-action-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 10.5V20h16v-9.5M4 10.5L12 4l8 6.5M9 20v-6h6v6"></path>
                    </svg>
                </span>
                <span>
                    <span class="owner-action-title">Properties</span>
                    <span class="owner-action-copy">View and manage your current property listings.</span>
                </span>
            </a>

            <a href="{{ route('owner.listings.create') }}" class="owner-action-card">
                <span class="owner-action-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </span>
                <span>
                    <span class="owner-action-title">Add Property</span>
                    <span class="owner-action-copy">Create a new property listing from scratch.</span>
                </span>
            </a>

            <a href="{{ route('owner.bookings.index') }}" class="owner-action-card">
                <span class="owner-action-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </span>
                <span>
                    <span class="owner-action-title">Booking Requests</span>
                    <span class="owner-action-copy">Review booking activity and respond quickly.</span>
                </span>
            </a>
        </div>
    </section>

    <section class="owner-section-card">
        <div class="owner-section-header">
            <div>
                <h3 class="owner-section-title">Recent Booking Requests</h3>
                <p class="owner-section-copy">Latest requests that need your attention.</p>
            </div>

            <a href="{{ route('owner.bookings.index') }}" class="btn-secondary-sm">View All</a>
        </div>

        @if($recentBookings->count() > 0)
            <div class="owner-activity-head" aria-hidden="true">
                <span>User</span>
                <span>Property</span>
                <span>Check-in</span>
                <span>Status</span>
                <span>Actions</span>
            </div>

            <div class="owner-activity-list">
                @foreach($recentBookings as $booking)
                    @php
                        $bookingStatusLabel = match ($booking->status) {
                            'pending' => 'Awaiting response',
                            'confirmed' => 'Confirmed',
                            'cancelled' => 'Cancelled',
                            'rejected' => 'Rejected',
                            default => ucfirst($booking->status),
                        };
                    @endphp

                    <div class="owner-activity-row">
                        <div class="owner-user">
                            <div class="owner-avatar">{{ substr($booking->user->name, 0, 1) }}</div>
                            <div class="owner-user-meta">
                                <div class="owner-user-name">{{ $booking->user->name }}</div>
                                <div class="owner-user-email">{{ $booking->user->email }}</div>
                            </div>
                        </div>

                        <div class="owner-property-cell">
                            <div class="owner-property-title" title="{{ $booking->property->title }}">{{ $booking->property->title }}</div>
                            <div class="owner-property-subtitle">{{ $booking->property->city ?? 'Property' }}</div>
                        </div>

                        <div class="owner-mobile-field" data-label="Check-in">
                            <div class="owner-meta-value">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</div>
                        </div>

                        <div class="owner-mobile-field" data-label="Status">
                            <div class="owner-status-badge-wrap">
                                <span class="badge badge-{{ $booking->status }}">
                                    {{ $bookingStatusLabel }}
                                </span>
                            </div>
                        </div>

                        <div class="owner-activity-actions">
                            @if($booking->status === 'pending')
                                <form method="POST" action="{{ route('owner.bookings.accept', $booking->id) }}">
                                    @csrf
                                    <button type="submit" class="btn-success-outline">Accept</button>
                                </form>
                                <form method="POST" action="{{ route('owner.bookings.reject', $booking->id) }}">
                                    @csrf
                                    <button type="submit" class="btn-danger-outline">Reject</button>
                                </form>
                            @else
                                <span class="table-empty-action">
                                    @if($booking->confirmed_at)
                                        Confirmed on {{ \Carbon\Carbon::parse($booking->confirmed_at)->format('M d, Y') }}
                                    @elseif($booking->cancelled_at)
                                        Cancelled on {{ \Carbon\Carbon::parse($booking->cancelled_at)->format('M d, Y') }}
                                    @else
                                        No further actions
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="owner-empty-state">
                <h3>No Booking Requests Yet</h3>
                <p>When guests book your properties, they will appear here.</p>
            </div>
        @endif
    </section>
</div>
@endsection
