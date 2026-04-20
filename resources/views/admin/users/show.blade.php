@extends('admin.layout')

@section('title', $user->name . ' | Profile')
@section('hide_pagebar', 'true')

@section('content')
    <div class="admin-dashboard admin-user-detail-page">
        <section class="content-card admin-user-profile-card">
            <div class="admin-user-profile-main">
                <div class="admin-user-profile-avatar">
                    @if ($user->profilePhotoUrl())
                        <img
                            src="{{ $user->profilePhotoUrl() }}"
                            alt="{{ $user->name }}"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                        >
                        <span class="admin-user-profile-avatar-fallback" style="display:none;">{{ $user->avatarInitial() }}</span>
                    @else
                        <span class="admin-user-profile-avatar-fallback">{{ $user->avatarInitial() }}</span>
                    @endif
                </div>

                <div class="admin-user-profile-copy">
                    <p class="admin-user-profile-kicker">Profile overview</p>
                    <h2>{{ $user->name }}</h2>
                    <p>{{ $user->email }}</p>

                    <div class="admin-user-profile-badges">
                        <span class="status-pill status-neutral">{{ ucfirst($user->role) }}</span>
                        <span class="status-pill {{ $user->is_verified ? 'status-approved' : 'status-neutral' }}">
                            {{ $user->is_verified ? 'Verified' : 'Unverified' }}
                        </span>
                        <span class="status-pill {{ $user->is_blocked ? 'status-rejected' : 'status-approved' }}">
                            {{ $user->is_blocked ? 'Blocked' : 'Active' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="admin-user-profile-actions">
                @if (! $user->isAdmin())
                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                        @csrf
                        <button
                            type="submit"
                            class="admin-btn {{ $user->is_blocked ? 'admin-btn-success' : 'admin-btn-danger' }}"
                        >
                            {{ $user->is_blocked ? 'Reactivate user' : 'Block user' }}
                        </button>
                    </form>
                @else
                    <span class="admin-meta-note">Admin accounts are protected.</span>
                @endif

                <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">Back to users</a>
            </div>
        </section>

        <section class="admin-user-stats-grid">
            <article class="content-card admin-user-stat-card">
                <span class="admin-user-stat-label">Bookings</span>
                <strong>{{ $user->bookings_count }}</strong>
                <p>Total bookings linked to this account.</p>
            </article>

            <article class="content-card admin-user-stat-card">
                <span class="admin-user-stat-label">Payments</span>
                <strong>Rs {{ number_format((float) $successfulPaymentsAmount, 2) }}</strong>
                <p>{{ $successfulPaymentsCount }} successful payment{{ $successfulPaymentsCount === 1 ? '' : 's' }}.</p>
            </article>

            <article class="content-card admin-user-stat-card">
                <span class="admin-user-stat-label">Reviews</span>
                <strong>{{ $user->reviews_count }}</strong>
                <p>Reviews written by this user.</p>
            </article>

            <article class="content-card admin-user-stat-card">
                <span class="admin-user-stat-label">Properties</span>
                <strong>{{ $user->properties_count }}</strong>
                <p>Properties owned by this user.</p>
            </article>
        </section>

        <section class="admin-user-info-grid">
            <article class="content-card">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Basic info</h2>
                        <p>Account and contact details.</p>
                    </div>
                </div>

                <div class="admin-detail-list">
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Name</span>
                        <span class="admin-detail-value">{{ $user->name }}</span>
                    </div>

                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Email</span>
                        <span class="admin-detail-value">{{ $user->email }}</span>
                    </div>

                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Phone</span>
                        <span class="admin-detail-value">{{ $user->phone ?: 'N/A' }}</span>
                    </div>

                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Role</span>
                        <span class="admin-detail-value">{{ ucfirst($user->role) }}</span>
                    </div>

                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Gender</span>
                        <span class="admin-detail-value">{{ $user->gender ? ucfirst($user->gender) : 'N/A' }}</span>
                    </div>

                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Joined</span>
                        <span class="admin-detail-value">{{ optional($user->created_at)->format('M d, Y') ?? 'N/A' }}</span>
                    </div>

                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Status</span>
                        <span class="admin-detail-value">{{ $user->is_blocked ? 'Blocked' : 'Active' }}</span>
                    </div>

                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Bio</span>
                        <span class="admin-detail-value">{{ $user->bio ?: 'No bio added.' }}</span>
                    </div>
                </div>
            </article>

            <article class="content-card admin-user-actions-card">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Admin actions</h2>
                        <p>Manage account access and role from one place.</p>
                    </div>
                </div>

                <div class="admin-user-actions-body">
                    @if (! $user->isAdmin() && (int) auth()->id() !== (int) $user->id)
                        <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="admin-user-role-form">
                            @csrf
                            @method('PUT')

                            <label for="role" class="admin-detail-label">Manage role</label>
                            <div class="admin-user-role-row">
                                <select id="role" name="role" class="admin-input">
                                    <option value="user" @selected($user->role === 'user')>User</option>
                                    <option value="owner" @selected($user->role === 'owner')>Owner</option>
                                </select>
                                <button type="submit" class="admin-btn admin-btn-secondary">Save role</button>
                            </div>
                        </form>
                    @else
                        <div class="admin-empty-note">
                            Role changes are protected for this account.
                        </div>
                    @endif

                    <div class="admin-user-access-actions">
                        @if (! $user->isAdmin())
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="admin-btn {{ $user->is_blocked ? 'admin-btn-success' : 'admin-btn-danger' }}"
                                >
                                    {{ $user->is_blocked ? 'Reactivate user' : 'Block user' }}
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.users.index') }}" class="admin-btn admin-btn-secondary">View all users</a>
                    </div>
                </div>
            </article>
        </section>

        <section class="admin-user-records-grid">
            @if ($user->isOwner())
                <article class="content-card admin-user-properties-card">
                    <div class="content-card-header admin-panel-header">
                        <div>
                            <h2>Properties</h2>
                            <p>Latest three properties owned by this account.</p>
                        </div>

                        <a href="{{ route('admin.properties.index', ['user' => $user->id]) }}" class="admin-btn admin-btn-secondary admin-btn-sm">View all properties</a>
                    </div>

                    <div class="admin-user-record-list">
                        @if ($recentProperties->isNotEmpty())
                            @foreach ($recentProperties as $property)
                                <article class="admin-user-record-item">
                                    <div class="admin-user-record-media">
                                        <img
                                            src="{{ $property->getFirstImageUrl() ?? asset('images/property-placeholder.jpg') }}"
                                            alt="{{ $property->title }}"
                                            onerror="this.src='{{ asset('images/property-placeholder.jpg') }}';"
                                        >
                                    </div>

                                    <div class="admin-user-record-copy">
                                        <strong>{{ \Illuminate\Support\Str::limit($property->title, 44) }}</strong>
                                        <p>{{ $property->city ?: 'N/A' }} &middot; {{ $property->getPropertyTypeLabel() }}</p>
                                        <span class="status-pill {{ $property->is_verified ? 'status-approved' : 'status-neutral' }}">
                                            {{ $property->is_verified ? 'Verified' : ucfirst($property->status ?? 'Pending') }}
                                        </span>
                                    </div>

                                    <a href="{{ route('listings.show', $property) }}" class="admin-btn admin-btn-secondary admin-user-record-view">View</a>
                                </article>
                            @endforeach
                        @else
                            <div class="admin-user-record-empty">No properties found for this owner.</div>
                        @endif
                    </div>
                </article>
            @else
                <article class="content-card admin-user-bookings-card">
                    <div class="content-card-header admin-panel-header">
                        <div>
                            <h2>Booking history</h2>
                            <p>Latest three bookings tied to this account.</p>
                        </div>

                        <a href="{{ route('admin.bookings.index', ['user' => $user->id]) }}" class="admin-btn admin-btn-secondary admin-btn-sm">View all bookings</a>
                    </div>

                    <div class="table-wrap admin-user-table-wrap">
                        <table class="admin-table admin-user-bookings-table">
                            <thead>
                                <tr>
                                    <th>Booking</th>
                                    <th>Property</th>
                                    <th>Stay</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($recentBookings->isNotEmpty())
                                    @foreach ($recentBookings as $booking)
                                        <tr>
                                            <td>
                                                <div class="admin-booking-reference">
                                                    <strong>#{{ $booking->id }}</strong>
                                                    <p>{{ optional($booking->created_at)->format('M d, Y') ?? 'N/A' }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-booking-property">
                                                    <div class="admin-booking-property-thumb">
                                                        <img
                                                            src="{{ optional($booking->property)->getFirstImageUrl() ?? asset('images/property-placeholder.jpg') }}"
                                                            alt="{{ optional($booking->property)->title ?? 'Booking property' }}"
                                                            onerror="this.src='{{ asset('images/property-placeholder.jpg') }}';"
                                                        >
                                                    </div>
                                                    <div class="admin-booking-property-copy">
                                                        <strong>{{ \Illuminate\Support\Str::limit(optional($booking->property)->title ?? 'N/A', 42) }}</strong>
                                                        <p>{{ optional($booking->property)->city ?: 'N/A' }}</p>
                                                        <span>{{ $booking->getBookableTypeLabel() }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="admin-booking-stay">
                                                    <strong>{{ optional($booking->check_in_date)->format('M d, Y') ?? 'N/A' }}</strong>
                                                    <p>to {{ optional($booking->check_out_date)->format('M d, Y') ?? 'N/A' }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="status-pill {{ $booking->isConfirmed() ? 'status-approved' : ($booking->isCancelled() ? 'status-rejected' : 'status-pending') }}">
                                                    {{ $booking->getStatusLabel() }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-pill {{ $booking->hasSuccessfulPayment() ? 'status-approved' : ($booking->hasPendingPayment() ? 'status-pending' : 'status-neutral') }}">
                                                    {{ $booking->hasSuccessfulPayment() ? 'Paid' : ($booking->hasPendingPayment() ? 'Pending' : 'No payment') }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.bookings.show', $booking) }}" class="admin-btn admin-btn-secondary admin-btn-sm">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="empty-cell">No bookings found for this user.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </article>
            @endif

            <article class="content-card admin-user-reviews-card">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Reviews</h2>
                        <p>Latest reviews written by this user.</p>
                    </div>

                    <a href="{{ route('admin.reviews.index', ['user' => $user->id]) }}" class="admin-btn admin-btn-secondary admin-btn-sm">View all reviews</a>
                </div>

                <div class="admin-user-record-list">
                    @if ($recentReviews->isNotEmpty())
                        @foreach ($recentReviews as $review)
                            <article class="admin-user-record-item">
                                <div class="admin-user-record-media">
                                    <img
                                        src="{{ optional($review->property)->getFirstImageUrl() ?? asset('images/property-placeholder.jpg') }}"
                                        alt="{{ optional($review->property)->title ?? 'Review property' }}"
                                        onerror="this.src='{{ asset('images/property-placeholder.jpg') }}';"
                                    >
                                </div>

                                <div class="admin-user-record-copy">
                                    <strong>{{ \Illuminate\Support\Str::limit(optional($review->property)->title ?? 'N/A', 44) }}</strong>
                                    <p>{{ \Illuminate\Support\Str::limit($review->review_text, 78) }}</p>
                                    <span class="admin-user-record-rating">{{ $review->rating }}/5</span>
                                </div>

                                <a href="{{ $review->property ? route('listings.show', $review->property) : route('admin.reviews.index') }}" class="admin-btn admin-btn-secondary admin-user-record-view">View</a>
                            </article>
                        @endforeach
                    @else
                        <div class="admin-user-record-empty">No reviews found for this user.</div>
                    @endif
                </div>
            </article>

            <article class="content-card admin-user-reports-card">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Reports</h2>
                        <p>Recent reports filed by or about this user.</p>
                    </div>

                    <a href="{{ route('admin.reports.index', ['user' => $user->id]) }}" class="admin-btn admin-btn-secondary admin-btn-sm">View all reports</a>
                </div>

                <div class="admin-user-record-list">
                    @if ($recentReports->isNotEmpty())
                        @foreach ($recentReports as $report)
                            <article class="admin-user-record-item">
                                <div class="admin-user-record-copy">
                                    <span class="admin-user-record-rating">{{ $report->getReportTypeLabel() }}</span>
                                    <strong>{{ \Illuminate\Support\Str::limit($report->reason, 60) }}</strong>
                                    <p>{{ $report->reporter?->name ?? 'Unknown reporter' }} &middot; {{ $report->getTargetLabel() }}</p>
                                </div>

                                <span class="status-pill {{ $report->status === 'resolved' ? 'status-approved' : ($report->status === 'under_review' ? 'status-pending' : 'status-neutral') }}">
                                    {{ $report->getStatusLabel() }}
                                </span>

                                <a href="{{ route('admin.reports.show', $report) }}" class="admin-btn admin-btn-secondary admin-user-record-view">View</a>
                            </article>
                        @endforeach
                    @else
                        <div class="admin-user-record-empty">No related reports found for this user.</div>
                    @endif
                </div>
            </article>
        </section>
    </div>
@endsection
