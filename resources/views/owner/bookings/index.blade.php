@extends('owner.layout')

@section('title', 'Booking Requests')
@section('page-title', 'Booking Requests')

@section('content')
<div class="owner-bookings-page">
<div class="content-card bookings-card">
    <div class="card-header bookings-card-header">
        <div>
            <h2 class="card-title">Recent Booking Requests</h2>
            <p class="card-subtitle">Review upcoming move-ins and respond to pending booking requests.</p>
        </div>
    </div>

    <div class="bookings-content">
        @if($bookings->count() > 0)
            <div class="table-responsive bookings-table-wrap">
                <table class="data-table bookings-table">
                    <colgroup>
                        <col class="bookings-col-id">
                        <col class="bookings-col-user">
                        <col class="bookings-col-property">
                        <col class="bookings-col-date">
                        <col class="bookings-col-date">
                        <col class="bookings-col-duration">
                        <col class="bookings-col-rent">
                        <col class="bookings-col-status">
                        <col class="bookings-col-actions">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>User</th>
                            <th>Property</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Duration</th>
                            <th>Total Rent</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td>
                                    <span class="booking-id">#{{ $booking->id }}</span>
                                </td>
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
                                    <div class="property-cell booking-property-cell">
                                        <span class="property-name-bold booking-property-title">{{ $booking->property->title }}</span>
                                        @if(!empty($booking->property->city) || !empty($booking->property->location))
                                            <span class="property-subtext">
                                                {{ $booking->property->city ?? $booking->property->location }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="booking-date-cell">
                                        <span class="booking-date-main">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</span>
                                        <span class="booking-date-sub">Move-in</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="booking-date-cell">
                                        <span class="booking-date-main">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</span>
                                        <span class="booking-date-sub">Move-out</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="booking-duration">{{ $booking->duration_months }} month(s)</span>
                                </td>
                                <td>
                                    <div class="booking-rent">@npr($booking->total_rent)</div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $booking->status }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($booking->status === 'pending')
                                        <div class="action-buttons booking-action-buttons">
                                            <form method="POST" action="{{ route('owner.bookings.accept', $booking->id) }}" class="booking-action-form">
                                                @csrf
                                                <button type="submit" class="btn-success-outline">Accept</button>
                                            </form>
                                            <form method="POST" action="{{ route('owner.bookings.reject', $booking->id) }}" class="booking-action-form">
                                                @csrf
                                                <button type="submit" class="btn-danger-outline">Reject</button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="booking-meta-note">
                                            @if($booking->confirmed_at)
                                                Confirmed on {{ \Carbon\Carbon::parse($booking->confirmed_at)->format('M d, Y') }}
                                            @elseif($booking->cancelled_at)
                                                Cancelled on {{ \Carbon\Carbon::parse($booking->cancelled_at)->format('M d, Y') }}
                                            @else
                                                No further actions
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bookings-mobile-list">
                @foreach($bookings as $booking)
                    <article class="booking-mobile-card">
                        <div class="booking-mobile-top">
                            <div>
                                <div class="booking-id">#{{ $booking->id }}</div>
                                <div class="booking-mobile-property">{{ $booking->property->title }}</div>
                                @if(!empty($booking->property->city) || !empty($booking->property->location))
                                    <div class="property-subtext">{{ $booking->property->city ?? $booking->property->location }}</div>
                                @endif
                            </div>
                            <span class="badge badge-{{ $booking->status }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>

                        <div class="user-info booking-mobile-user">
                            <div class="user-avatar">{{ substr($booking->user->name, 0, 1) }}</div>
                            <div class="user-meta">
                                <div class="user-name">{{ $booking->user->name }}</div>
                                <div class="user-email">{{ $booking->user->email }}</div>
                            </div>
                        </div>

                        <div class="booking-mobile-grid">
                            <div>
                                <span class="booking-mobile-label">Check-In</span>
                                <span class="booking-mobile-value">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="booking-mobile-label">Check-Out</span>
                                <span class="booking-mobile-value">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="booking-mobile-label">Duration</span>
                                <span class="booking-mobile-value">{{ $booking->duration_months }} month(s)</span>
                            </div>
                            <div>
                                <span class="booking-mobile-label">Total Rent</span>
                                <span class="booking-mobile-value booking-rent">@npr($booking->total_rent)</span>
                            </div>
                        </div>

                        @if($booking->status === 'pending')
                            <div class="action-buttons booking-action-buttons booking-mobile-actions">
                                <form method="POST" action="{{ route('owner.bookings.accept', $booking->id) }}" class="booking-action-form">
                                    @csrf
                                    <button type="submit" class="btn-success-outline">Accept</button>
                                </form>
                                <form method="POST" action="{{ route('owner.bookings.reject', $booking->id) }}" class="booking-action-form">
                                    @csrf
                                    <button type="submit" class="btn-danger-outline">Reject</button>
                                </form>
                            </div>
                        @else
                            <div class="booking-meta-note">
                                @if($booking->confirmed_at)
                                    Confirmed on {{ \Carbon\Carbon::parse($booking->confirmed_at)->format('M d, Y') }}
                                @elseif($booking->cancelled_at)
                                    Cancelled on {{ \Carbon\Carbon::parse($booking->cancelled_at)->format('M d, Y') }}
                                @else
                                    No further actions
                                @endif
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>

            <div class="pagination-wrapper">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="empty-state">
                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3>No Booking Requests</h3>
                <p>You don't have any booking requests yet.</p>
            </div>
        @endif
    </div>
</div>
</div>
@endsection
