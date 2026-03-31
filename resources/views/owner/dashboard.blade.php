@extends('owner.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-value">{{ $totalListings }}</div>
                <div class="stat-label">Total Listings</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-value">{{ $activeListings }}</div>
                <div class="stat-label">Active Listings</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-value">{{ $pendingBookingRequests }}</div>
                <div class="stat-label">Pending Requests</div>
            </div>
        </div>

        <div class="stat-card">
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