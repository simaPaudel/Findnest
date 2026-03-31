@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    <div class="admin-dashboard">
        <section class="stats-grid">
            <article class="stat-card">
                <p class="stat-label">Total Users</p>
                <p class="stat-value">{{ $totalUsers }}</p>
            </article>
            <article class="stat-card">
                <p class="stat-label">Total Owners</p>
                <p class="stat-value">{{ $totalOwners }}</p>
            </article>
            <article class="stat-card">
                <p class="stat-label">Total Properties</p>
                <p class="stat-value">{{ $totalProperties }}</p>
            </article>
            <article class="stat-card stat-card-warn">
                <p class="stat-label">Pending Properties</p>
                <p class="stat-value">{{ $pendingProperties }}</p>
            </article>
            <article class="stat-card stat-card-good">
                <p class="stat-label">Approved Properties</p>
                <p class="stat-value">{{ $approvedProperties }}</p>
            </article>
            <article class="stat-card stat-card-danger">
                <p class="stat-label">Rejected Properties</p>
                <p class="stat-value">{{ $rejectedProperties }}</p>
            </article>
            <article class="stat-card">
                <p class="stat-label">Total Bookings</p>
                <p class="stat-value">{{ $totalBookings }}</p>
            </article>
            <article class="stat-card">
                <p class="stat-label">Total Reviews</p>
                <p class="stat-value">{{ $totalReviews }}</p>
            </article>
        </section>

        <div class="dashboard-grid">
            <section class="content-card">
                <div class="content-card-header">
                    <div>
                        <h2>Recent Properties</h2>
                        <p>Latest property submissions and updates.</p>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Property</th>
                                <th>Owner</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Verified</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentProperties as $property)
                                <tr>
                                    <td>
                                        <div class="primary-text">{{ $property->title }}</div>
                                        <div class="muted-text">{{ \Illuminate\Support\Str::limit($property->address, 40) }}</div>
                                    </td>
                                    <td>{{ $property->owner->name ?? 'N/A' }}</td>
                                    <td>{{ $property->city ?? 'N/A' }}</td>
                                    <td>
                                        <span class="status-pill status-{{ $property->status }}">
                                            {{ $property->status }}
                                        </span>
                                    </td>
                                    <td>{{ $property->is_verified ? 'Yes' : 'No' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-cell">No recent properties found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="content-card">
                <div class="content-card-header">
                    <div>
                        <h2>Recent Bookings</h2>
                        <p>Latest booking activity across the platform.</p>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Property</th>
                                <th>Status</th>
                                <th>Check In</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentBookings as $booking)
                                <tr>
                                    <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->property->title ?? 'N/A' }}</td>
                                    <td>
                                        <span class="status-pill status-neutral">
                                            {{ $booking->status ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>{{ $booking->check_in_date ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-cell">No recent bookings found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection
