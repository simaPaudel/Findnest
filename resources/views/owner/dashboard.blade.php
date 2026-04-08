@extends('owner.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard-header">
    <div>
        <h1 class="dashboard-title">Dashboard</h1>
        <p class="dashboard-subtitle">A quick overview of your listings, requests, and recent activity.</p>
    </div>
</div>

<div class="dashboard-container">
    <div class="stats-grid">
        <div class="stat-card stat-card-total">
            <p class="stat-label">Total Listings</p>
            <div class="stat-value">{{ $totalListings }}</div>
        </div>

        <div class="stat-card stat-card-active">
            <p class="stat-label">Active Listings</p>
            <div class="stat-value">{{ $activeListings }}</div>
        </div>

        <div class="stat-card stat-card-pending">
            <p class="stat-label">Pending Requests</p>
            <div class="stat-value">{{ $pendingBookingRequests }}</div>
        </div>

        <div class="stat-card stat-card-rating">
            <p class="stat-label">Average Rating</p>
            <div class="stat-value">{{ number_format($avgRating, 1) }}</div>
            <p class="stat-note">{{ $reviewsCount }} {{ $reviewsCount === 1 ? 'review' : 'reviews' }}</p>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header">
            <div>
                <h2 class="card-title">Recent Booking Requests</h2>
                <p class="card-subtitle">Latest requests that need your attention.</p>
            </div>
            <a href="{{ route('owner.bookings.index') }}" class="btn-secondary-sm">View All</a>
        </div>

        <div class="table-responsive">
            @if($recentBookings->count() > 0)
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Property</th>
                            <th>Check-In</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBookings as $booking)
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">{{ substr($booking->user->name, 0, 1) }}</div>
                                        <div class="user-meta">
                                            <div class="user-name">{{ $booking->user->name }}</div>
                                            <div class="user-email">{{ $booking->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="property-cell">
                                        <span class="property-name-bold" title="{{ $booking->property->title }}">{{ $booking->property->title }}</span>
                                        <span class="property-subtext">{{ $booking->property->city ?? 'Property listing' }}</span>
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                                <td>{{ $booking->duration_months }} month(s)</td>
                                <td>
                                    <span class="badge badge-{{ $booking->status }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($booking->status === 'pending')
                                        <div class="action-buttons">
                                            <form method="POST" action="{{ route('owner.bookings.accept', $booking->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn-success-outline">Accept</button>
                                            </form>
                                            <form method="POST" action="{{ route('owner.bookings.reject', $booking->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn-danger-outline">Reject</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="table-empty-action">No actions</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <h3>No Booking Requests Yet</h3>
                    <p>When guests book your properties, they'll appear here.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
