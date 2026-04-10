@extends('owner.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<style>
    .owner-dashboard-page {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .owner-dashboard-hero {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
    }

    .owner-dashboard-kicker {
        font-size: 0.74rem;
        font-weight: 700;
        color: #be123c;
        letter-spacing: 0.14em;
        text-transform: uppercase;
    }

    .owner-dashboard-title {
        margin-top: 6px;
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.04em;
        color: var(--fn-charcoal);
    }

    .owner-dashboard-copy {
        margin-top: 6px;
        max-width: 54rem;
        color: var(--fn-gray-dark);
        font-size: 0.95rem;
        line-height: 1.65;
    }

    .owner-dashboard-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.85rem 1.15rem;
        border-radius: 14px;
        background: #ff385c;
        color: #fff;
        font-size: 0.88rem;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: background 0.18s ease, transform 0.18s ease;
    }

    .owner-dashboard-cta:hover {
        background: #e11d48;
        transform: translateY(-1px);
    }

    .owner-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .owner-stat-card {
        position: relative;
        padding: 18px 18px 20px;
        border: 1px solid #e5e7eb;
        border-top-width: 2px;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    }

    .owner-stat-card::after {
        content: '';
        position: absolute;
        top: 16px;
        right: 16px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 56, 92, 0.12);
    }

    .owner-stat-card.total {
        background: linear-gradient(180deg, #fff7f9 0%, #ffffff 76%);
        border-top-color: #ff9fb4;
    }

    .owner-stat-card.active {
        background: linear-gradient(180deg, #fff8fa 0%, #ffffff 76%);
        border-top-color: #fda4af;
    }

    .owner-stat-card.pending {
        background: linear-gradient(180deg, #fffaf8 0%, #ffffff 76%);
        border-top-color: #fdba74;
    }

    .owner-stat-card.rating {
        background: linear-gradient(180deg, #fff7f9 0%, #ffffff 76%);
        border-top-color: #f9a8d4;
    }

    .owner-stat-label {
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #be123c;
    }

    .owner-stat-value {
        margin-top: 18px;
        font-size: 2.4rem;
        font-weight: 800;
        letter-spacing: -0.05em;
        line-height: 1;
        color: var(--fn-charcoal);
    }

    .owner-stat-note {
        margin-top: 10px;
        font-size: 0.82rem;
        color: var(--fn-gray-dark);
    }

    .owner-action-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .owner-action-card {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 18px;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        background: #fff;
        color: inherit;
        text-decoration: none;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
        transition: transform 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
    }

    .owner-action-card:hover {
        transform: translateY(-2px);
        border-color: rgba(255, 56, 92, 0.28);
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.05);
    }

    .owner-action-icon {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        background: #fff5f7;
        border: 1px solid rgba(255, 56, 92, 0.12);
        color: #ff385c;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .owner-action-icon svg {
        width: 20px;
        height: 20px;
    }

    .owner-action-title {
        font-size: 0.98rem;
        font-weight: 700;
        color: var(--fn-charcoal);
    }

    .owner-action-copy {
        margin-top: 4px;
        font-size: 0.86rem;
        line-height: 1.55;
        color: var(--fn-gray-dark);
    }

    .owner-section-card {
        overflow: hidden;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    }

    .owner-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 22px;
        border-bottom: 1px solid #eef2f7;
    }

    .owner-section-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--fn-charcoal);
    }

    .owner-section-copy {
        margin-top: 4px;
        font-size: 0.86rem;
        color: var(--fn-gray-dark);
    }

    .owner-activity-list {
        display: flex;
        flex-direction: column;
    }

    .owner-activity-row {
        display: grid;
        grid-template-columns: minmax(0, 1.45fr) minmax(0, 1.45fr) 150px 120px auto;
        gap: 16px;
        align-items: center;
        padding: 16px 22px;
        border-bottom: 1px solid #f3f4f6;
    }

    .owner-activity-row:last-child {
        border-bottom: none;
    }

    .owner-user {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .owner-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 56, 92, 0.12);
        color: #ff385c;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }

    .owner-user-meta {
        min-width: 0;
    }

    .owner-user-name {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--fn-charcoal);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .owner-user-email {
        margin-top: 2px;
        font-size: 0.8rem;
        color: var(--fn-gray-dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .owner-property-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--fn-charcoal);
        line-height: 1.5;
    }

    .owner-property-subtitle {
        margin-top: 2px;
        font-size: 0.8rem;
        color: var(--fn-gray-dark);
    }

    .owner-meta-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #94a3b8;
    }

    .owner-meta-value {
        margin-top: 5px;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--fn-charcoal);
    }

    .owner-activity-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
    }

    .owner-empty-state {
        padding: 42px 22px;
        text-align: center;
        color: var(--fn-gray-dark);
    }

    .owner-empty-state h3 {
        margin-bottom: 6px;
        font-size: 1rem;
        font-weight: 700;
        color: var(--fn-charcoal);
    }

    @media (max-width: 1200px) {
        .owner-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .owner-action-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .owner-activity-row {
            grid-template-columns: minmax(0, 1.45fr) minmax(0, 1.2fr) 140px 110px auto;
        }
    }

    @media (max-width: 900px) {
        .owner-dashboard-hero {
            flex-direction: column;
            align-items: flex-start;
        }

        .owner-activity-row {
            grid-template-columns: 1fr;
            gap: 10px;
            align-items: flex-start;
        }

        .owner-activity-actions {
            justify-content: flex-start;
        }
    }

    @media (max-width: 768px) {
        .owner-stat-grid,
        .owner-action-grid {
            grid-template-columns: 1fr;
        }

        .owner-dashboard-title {
            font-size: 1.8rem;
        }

        .owner-section-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="owner-dashboard-page">
    <section class="owner-dashboard-hero">
        <div>
            <p class="owner-dashboard-kicker">Overview</p>
            <h2 class="owner-dashboard-title">Dashboard</h2>
            <p class="owner-dashboard-copy">A quick overview of your properties, booking requests, and recent activity.</p>
        </div>

        <a href="{{ route('owner.listings.create') }}" class="owner-dashboard-cta">Add Property</a>
    </section>

    <section class="owner-stat-grid" aria-label="Dashboard metrics">
        <div class="owner-stat-card total">
            <p class="owner-stat-label">Total Properties</p>
            <div class="owner-stat-value">{{ $totalListings }}</div>
            <p class="owner-stat-note">All properties in your portfolio</p>
        </div>

        <div class="owner-stat-card active">
            <p class="owner-stat-label">Active Properties</p>
            <div class="owner-stat-value">{{ $activeListings }}</div>
            <p class="owner-stat-note">Currently visible and bookable</p>
        </div>

        <div class="owner-stat-card pending">
            <p class="owner-stat-label">Pending Requests</p>
            <div class="owner-stat-value">{{ $pendingBookingRequests }}</div>
            <p class="owner-stat-note">Needs review or action</p>
        </div>

        <div class="owner-stat-card rating">
            <p class="owner-stat-label">Average Rating</p>
            <div class="owner-stat-value">{{ number_format($avgRating, 1) }}</div>
            <p class="owner-stat-note">{{ $reviewsCount }} {{ $reviewsCount === 1 ? 'review' : 'reviews' }}</p>
        </div>
    </section>

    <section class="owner-action-grid" aria-label="Quick actions">
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
            <div class="owner-activity-list">
                @foreach($recentBookings as $booking)
                    <div class="owner-activity-row">
                        <div class="owner-user">
                            <div class="owner-avatar">{{ substr($booking->user->name, 0, 1) }}</div>
                            <div class="owner-user-meta">
                                <div class="owner-user-name">{{ $booking->user->name }}</div>
                                <div class="owner-user-email">{{ $booking->user->email }}</div>
                            </div>
                        </div>

                        <div>
                            <div class="owner-property-title" title="{{ $booking->property->title }}">{{ $booking->property->title }}</div>
                            <div class="owner-property-subtitle">{{ $booking->property->city ?? 'Property' }}</div>
                        </div>

                        <div>
                            <div class="owner-meta-label">Check-In</div>
                            <div class="owner-meta-value">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</div>
                        </div>

                        <div>
                            <div class="owner-meta-label">Status</div>
                            <div style="margin-top: 6px;">
                                <span class="badge badge-{{ $booking->status }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="owner-activity-actions">
                            @if($booking->status === 'pending')
                                <form method="POST" action="{{ route('owner.bookings.accept', $booking->id) }}" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn-success-outline">Accept</button>
                                </form>
                                <form method="POST" action="{{ route('owner.bookings.reject', $booking->id) }}" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn-danger-outline">Reject</button>
                                </form>
                            @else
                                <span class="table-empty-action">No actions</span>
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
