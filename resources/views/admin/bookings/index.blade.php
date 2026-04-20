@extends('admin.layout')

@section('title', 'Bookings')
@section('page_title', 'Bookings')
@section('hide_pagebar', 'true')

@section('content')
    <div class="admin-bookings-page">
        <section class="content-card admin-bookings-hero">
            <div>
                <p class="page-kicker">Booking Center</p>
                <h2>Bookings</h2>
                <p>Track booking status, payment status, and disputes from one table.</p>
            </div>

            <span class="admin-hero-note">
                {{ $bookings->total() }} total booking{{ $bookings->total() === 1 ? '' : 's' }}
            </span>
        </section>

        <section class="content-card admin-bookings-table-card">
            <div class="content-card-header admin-bookings-table-header">
                <div>
                    <h2>All bookings</h2>
                    <p>View booking details, payment state, and admin actions in one place.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table class="admin-table admin-bookings-table">
                    <thead>
                        <tr>
                            <th>Booking</th>
                            <th>User</th>
                            <th>Property</th>
                            <th>Stay</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            @php
                                $propertyImageUrl = optional($booking->property)->getFirstImageUrl() ?? asset('images/property-placeholder.jpg');
                                $userAvatarUrl = $booking->user && method_exists($booking->user, 'profilePhotoUrl') ? $booking->user->profilePhotoUrl() : null;
                                $userAvatarInitial = $booking->user && method_exists($booking->user, 'avatarInitial')
                                    ? $booking->user->avatarInitial()
                                    : strtoupper(substr($booking->user?->name ?? 'U', 0, 1));

                                $paymentState = $booking->hasSuccessfulPayment()
                                    ? 'paid'
                                    : ($booking->payments->sortByDesc('created_at')->first()?->payment_status ?? 'unpaid');

                                $statusClass = match ($booking->status) {
                                    'confirmed', 'completed' => 'status-approved',
                                    'pending' => 'status-pending',
                                    'cancelled', 'rejected' => 'status-rejected',
                                    default => 'status-neutral',
                                };

                                $paymentClass = match ($paymentState) {
                                    'paid', 'success' => 'status-approved',
                                    'pending' => 'status-pending',
                                    'failed' => 'status-rejected',
                                    default => 'status-neutral',
                                };

                                $latestSuccessfulPayment = $booking->payments
                                    ->where('payment_status', 'success')
                                    ->sortByDesc('paid_at')
                                    ->first();

                                $payoutState = $latestSuccessfulPayment?->payout_status ?? 'not_applicable';
                                $payoutClass = match ($payoutState) {
                                    'completed' => 'status-approved',
                                    'pending' => 'status-pending',
                                    default => 'status-neutral',
                                };
                            @endphp

                            <tr>
                                <td>
                                    <div class="admin-booking-reference">
                                        <strong>#{{ $booking->id }}</strong>
                                        <p>{{ optional($booking->created_at)->format('M d, Y') ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td>
                                    <div class="admin-booking-table-user">
                                        <div class="admin-booking-avatar">
                                            @if($userAvatarUrl)
                                                <img
                                                    src="{{ $userAvatarUrl }}"
                                                    alt="{{ $booking->user->name ?? 'User' }}"
                                                    onerror="this.style.display='none'; this.nextElementSibling.removeAttribute('hidden');"
                                                >
                                                <span class="admin-booking-avatar-fallback" hidden>{{ $userAvatarInitial }}</span>
                                            @else
                                                <span class="admin-booking-avatar-fallback">{{ $userAvatarInitial }}</span>
                                            @endif
                                        </div>

                                        <div class="admin-booking-user-copy">
                                            <strong>{{ $booking->user->name ?? 'N/A' }}</strong>
                                            <span>{{ $booking->user->email ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="admin-booking-property">
                                        <div class="admin-booking-property-thumb">
                                            <img src="{{ $propertyImageUrl }}" alt="{{ $booking->property->title ?? 'Booking property' }}">
                                        </div>

                                        <div class="admin-booking-property-copy">
                                            <strong>{{ $booking->property->title ?? 'N/A' }}</strong>
                                            <p>{{ $booking->property->city ?? 'N/A' }}</p>
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
                                    <span class="status-pill {{ $statusClass }}">{{ $booking->getStatusLabel() }}</span>
                                </td>
                                <td>
                                    <div class="admin-booking-payment-cell">
                                        <span class="status-pill {{ $paymentClass }}">{{ $paymentState === 'paid' ? 'Paid' : ucfirst($paymentState) }}</span>
                                        <p>
                                            Payout:
                                            <span class="status-pill {{ $payoutClass }}">
                                                {{ $latestSuccessfulPayment ? $latestSuccessfulPayment->getPayoutStatusLabel() : 'N/A' }}
                                            </span>
                                        </p>
                                    </div>
                                </td>
                                <td>
                                    <div class="admin-booking-table-actions">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="admin-btn admin-btn-secondary admin-btn-sm admin-booking-view-btn">
                                            View
                                        </a>
                                        <details class="admin-booking-actions-menu">
                                            <summary class="admin-booking-actions-toggle" aria-label="More booking actions">
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                            </summary>

                                            <div class="admin-booking-actions-panel">
                                                <a href="{{ route('admin.bookings.show', $booking) }}#payment-status" class="admin-booking-action-link">
                                                    View payment
                                                </a>
                                                <a href="{{ route('admin.bookings.show', $booking) }}#booking-status" class="admin-booking-action-link">
                                                    Update status
                                                </a>
                                                <a href="{{ route('admin.bookings.show', $booking) }}#disputes" class="admin-booking-action-link">
                                                    Handle dispute
                                                </a>

                                                @if($booking->status === 'confirmed' && $booking->hasSuccessfulPayment())
                                                    <form method="POST" action="{{ route('admin.bookings.release', $booking) }}" class="admin-booking-release-form">
                                                        @csrf
                                                        <button type="submit" class="admin-booking-action-link admin-booking-menu-button">Release booking</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </details>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-cell">No bookings found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        @if ($bookings->hasPages())
            <section class="content-card admin-bookings-pagination">
                <div class="admin-bookings-pagination-wrap">
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
                </div>
            </section>
        @endif
    </div>
@endsection
