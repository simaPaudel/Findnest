@extends('owner.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-red">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalListings }}</div>
                <div class="stat-label">Total Listings</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-green">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $activeListings }}</div>
                <div class="stat-label">Active Listings</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-yellow">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $pendingBookingRequests }}</div>
                <div class="stat-label">Pending Requests</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($avgRating, 1) }}</div>
                <div class="stat-label">Avg Rating ({{ $reviewsCount }} reviews)</div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">Recent Booking Requests</h2>
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
                                        <div>
                                            <div class="user-name">{{ $booking->user->name }}</div>
                                            <div class="user-email">{{ $booking->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $booking->property->title }}</td>
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
                                                <button type="submit" class="btn-success-sm">Accept</button>
                                            </form>
                                            <form method="POST" action="{{ route('owner.bookings.reject', $booking->id) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn-danger-sm">Reject</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3>No Booking Requests Yet</h3>
                    <p>When guests book your properties, they'll appear here.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
