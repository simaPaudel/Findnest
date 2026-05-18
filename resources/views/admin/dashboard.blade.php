@extends('admin.layout')

@section('title', 'Dashboard')
@section('page_kicker', 'Overview')
@section('page_title', 'Admin Dashboard')
@section('page_meta')
    <span class="admin-page-date">
        {{ now()->format('l, F j, Y') }}
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </span>
@endsection

@section('content')
    @php
        $activeBookings = collect($bookingChart['segments'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('count');
    @endphp

    <div class="admin-dashboard admin-dashboard-home">
        <section class="stats-grid admin-dashboard-stats">
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
            <article class="stat-card">
                <p class="stat-label">Active Bookings</p>
                <p class="stat-value">{{ $activeBookings }}</p>
            </article>
        </section>

        <section class="admin-dashboard-top-grid">
            <article class="content-card admin-dashboard-panel admin-chart-panel">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Platform Overview</h2>
                        <p>Live signup, listing, and booking activity from the first record onward.</p>
                    </div>

                    <div class="admin-chart-legend">
                        @foreach ($activityChart['series'] as $series)
                            <span class="admin-legend-item">
                                <span class="admin-legend-dot" style="background: {{ $series['color'] }}"></span>
                                {{ $series['label'] }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div class="admin-chart-shell">
                    <svg viewBox="0 0 {{ $activityChart['width'] }} {{ $activityChart['height'] }}" class="admin-chart-svg" role="img" aria-label="Platform activity chart">
                        @foreach ($activityChart['ticks'] as $index => $tick)
                            @php
                                $tickCount = max(count($activityChart['ticks']) - 1, 1);
                                $y = $activityChart['padding'] + (($activityChart['height'] - ($activityChart['padding'] * 2)) * ($index / $tickCount));
                            @endphp
                            <line x1="{{ $activityChart['padding'] }}" y1="{{ $y }}" x2="{{ $activityChart['width'] - $activityChart['padding'] }}" y2="{{ $y }}" class="admin-chart-grid-line"></line>
                            <text x="10" y="{{ $y + 4 }}" class="admin-chart-axis-label">{{ number_format($tick) }}</text>
                        @endforeach

                        @foreach ($activityChart['labels'] as $index => $label)
                            @php
                                $labelCount = max(count($activityChart['labels']) - 1, 1);
                                $x = count($activityChart['labels']) === 1
                                    ? ($activityChart['width'] / 2)
                                    : $activityChart['padding'] + (($activityChart['width'] - ($activityChart['padding'] * 2)) * ($index / $labelCount));
                            @endphp
                            <text x="{{ $x }}" y="{{ $activityChart['height'] - 6 }}" text-anchor="middle" class="admin-chart-month-label">{{ $label }}</text>
                        @endforeach

                        @foreach ($activityChart['series'] as $series)
                            <path d="{{ $series['area'] }}" fill="{{ $series['fill'] }}" opacity="0.95"></path>
                            <path d="{{ $series['line'] }}" fill="none" stroke="{{ $series['color'] }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                            @foreach ($series['points'] as $point)
                                <circle cx="{{ $point['x'] }}" cy="{{ $point['y'] }}" r="3.5" fill="{{ $series['color'] }}" stroke="#ffffff" stroke-width="2"></circle>
                            @endforeach
                        @endforeach
                    </svg>
                </div>
            </article>

            <article class="content-card admin-dashboard-panel admin-donut-panel">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Booking Status</h2>
                        <p>Current booking distribution.</p>
                    </div>

                    <span class="admin-card-chip">{{ number_format($bookingChart['total']) }} total</span>
                </div>

                <div class="admin-donut-shell">
                    <div class="admin-donut-chart" style="background: {{ $bookingChart['background'] }};">
                        <div class="admin-donut-center">
                            <strong>{{ number_format($bookingChart['total']) }}</strong>
                            <span>Bookings</span>
                        </div>
                    </div>

                    <div class="admin-donut-legend">
                        @forelse ($bookingChart['segments'] as $segment)
                            <div class="admin-donut-legend-item">
                                <span class="admin-legend-dot" style="background: {{ $segment['color'] }}"></span>
                                <div>
                                    <strong>{{ $segment['label'] }}</strong>
                                    <span>{{ $segment['percentage'] }}% &middot; {{ $segment['count'] }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="admin-empty-note">No bookings yet.</div>
                        @endforelse
                    </div>
                </div>
            </article>

        </section>

        <section class="admin-dashboard-bottom">
            <article class="content-card admin-dashboard-table-card">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Recent Properties</h2>
                        <p>Latest property submissions and updates.</p>
                    </div>

                </div>

                <div class="table-wrap">
                    <table class="admin-table admin-dashboard-table">
                        <thead>
                            <tr>
                                <th>Property</th>
                                <th>Owner</th>
                                <th>City</th>
                                <th>Status</th>
                                <th>Verified</th>
                                <th>Added</th>
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
                                            {{ ucfirst($property->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-pill {{ $property->is_verified ? 'status-approved' : 'status-neutral' }}">
                                            {{ $property->is_verified ? 'Verified' : 'Unverified' }}
                                        </span>
                                    </td>
                                    <td>{{ optional($property->created_at)->format('M d, Y') ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty-cell">No recent properties found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="admin-card-footer">
                    <a href="{{ route('admin.properties.index') }}" class="admin-footer-link">View all properties</a>
                </div>
            </article>

            <article class="content-card admin-dashboard-table-card">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Recent Bookings</h2>
                        <p>Latest booking activity across the platform.</p>
                    </div>

                </div>

                <div class="table-wrap">
                    <table class="admin-table admin-dashboard-table">
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
                                        <span class="status-pill status-{{ $booking->status }}">
                                            {{ ucfirst($booking->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td>{{ optional($booking->check_in_date)->format('M d, Y') ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-cell">No recent bookings found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="admin-card-footer">
                    <a href="{{ route('admin.bookings.index') }}" class="admin-footer-link">View all bookings</a>
                </div>
            </article>
        </section>
    </div>
@endsection
