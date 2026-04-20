@extends('admin.layout')

@section('title', 'Booking Details')
@section('page_title', 'Booking Details')

@section('content')
    @php
        $propertyImageUrl = optional($booking->property)->getFirstImageUrl() ?? asset('images/property-placeholder.jpg');
        $bookerAvatarUrl = $booking->user && method_exists($booking->user, 'profilePhotoUrl') ? $booking->user->profilePhotoUrl() : null;
        $bookerAvatarInitial = $booking->user && method_exists($booking->user, 'avatarInitial')
            ? $booking->user->avatarInitial()
            : strtoupper(substr($booking->user?->name ?? 'U', 0, 1));

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
    @endphp

    <div class="admin-bookings-page">
        <section class="content-card admin-booking-detail-hero">
            <div class="admin-booking-detail-hero-copy">
                <p class="page-kicker">Booking Review</p>
                <h2>{{ $booking->property->title ?? 'Booking details' }}</h2>
                <p>
                    Review booking status, payment status, and any related disputes without leaving the admin panel.
                </p>
            </div>

            <div class="admin-booking-detail-hero-actions">
                <a href="{{ route('admin.bookings.index') }}" class="admin-btn admin-btn-secondary">View all bookings</a>

                @if($booking->status === 'confirmed' && $booking->hasSuccessfulPayment())
                    <form method="POST" action="{{ route('admin.bookings.release', $booking) }}" class="admin-booking-release-form">
                        @csrf
                        <button type="submit" class="admin-btn admin-btn-success">Release booking</button>
                    </form>
                @endif
            </div>
        </section>

        <section class="admin-bookings-summary-grid">
            <article class="content-card admin-booking-summary-card" id="booking-status">
                <span class="admin-summary-label">Booking status</span>
                <div class="admin-summary-value {{ $statusClass }}">{{ $booking->getStatusLabel() }}</div>
                <div class="admin-summary-note">
                    {{ $booking->isConfirmed() ? 'This booking is active and ready for release once payment is verified.' : 'Status comes directly from the booking record.' }}
                </div>
            </article>

            <article class="content-card admin-booking-summary-card" id="payment-status">
                <span class="admin-summary-label">Payment status</span>
                <div class="admin-summary-value {{ $paymentClass }}">{{ $paymentState === 'paid' ? 'Paid' : ucfirst($paymentState) }}</div>
                <div class="admin-summary-note">
                    Paid Rs {{ number_format((float) $booking->getTotalPaid(), 2) }} of Rs {{ number_format((float) ($booking->total_rent ?? 0), 2) }}.
                </div>
            </article>

            <article class="content-card admin-booking-summary-card" id="disputes">
                <span class="admin-summary-label">Disputes</span>
                <div class="admin-summary-value">{{ $relatedDisputesCount }}</div>
                <div class="admin-summary-note">
                    {{ $relatedDisputesCount > 0 ? 'Related reports are available below for basic dispute handling.' : 'No related reports found for this booking.' }}
                </div>
            </article>
        </section>

        <section class="admin-booking-detail-layout">
            <article class="content-card admin-booking-detail-card">
                <div class="admin-booking-detail-media">
                    <img src="{{ $propertyImageUrl }}" alt="{{ $booking->property->title ?? 'Booking property' }}">
                </div>

                <div class="admin-booking-detail-body">
                    <div class="admin-booking-section-title">
                        <div>
                            <h3>Booking overview</h3>
                            <p>Core booking data pulled from the database.</p>
                        </div>
                    </div>

                    <div class="admin-booking-user">
                        <div class="admin-booking-avatar">
                            @if($bookerAvatarUrl)
                                <img
                                    src="{{ $bookerAvatarUrl }}"
                                    alt="{{ $booking->user->name ?? 'User' }}"
                                    onerror="this.style.display='none'; this.nextElementSibling.removeAttribute('hidden');"
                                >
                                <span class="admin-booking-avatar-fallback" hidden>{{ $bookerAvatarInitial }}</span>
                            @else
                                <span class="admin-booking-avatar-fallback">{{ $bookerAvatarInitial }}</span>
                            @endif
                        </div>

                        <div class="admin-booking-user-copy">
                            <strong>{{ $booking->user->name ?? 'N/A' }}</strong>
                            <span>{{ $booking->user->email ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="admin-booking-info-grid">
                        <div class="admin-booking-info-item">
                            <span>Property</span>
                            <strong>{{ $booking->property->title ?? 'N/A' }}</strong>
                            <p>{{ $booking->property->city ?? 'N/A' }}</p>
                        </div>

                        <div class="admin-booking-info-item">
                            <span>Rental type</span>
                            <strong>{{ $booking->getBookableTypeLabel() }}</strong>
                            <p>
                                @if($booking->room)
                                    Room: {{ $booking->room->room_name ?? 'N/A' }}
                                @else
                                    Full property booking
                                @endif
                            </p>
                        </div>

                        <div class="admin-booking-info-item">
                            <span>Stay dates</span>
                            <strong>{{ optional($booking->check_in_date)->format('M d, Y') ?? 'N/A' }}</strong>
                            <p>Until {{ optional($booking->check_out_date)->format('M d, Y') ?? 'N/A' }}</p>
                        </div>

                        <div class="admin-booking-info-item">
                            <span>Booking value</span>
                            <strong>Rs {{ number_format((float) ($booking->total_rent ?? 0), 2) }}</strong>
                            <p>Advance paid Rs {{ number_format((float) ($booking->advance_payment ?? 0), 2) }}</p>
                        </div>
                    </div>

                    @if($booking->special_requests)
                        <div class="admin-booking-info-item admin-booking-request-note">
                            <span>Special request</span>
                            <p>{{ $booking->special_requests }}</p>
                        </div>
                    @endif
                </div>
            </article>

            <article class="content-card admin-booking-detail-card">
                <div class="admin-booking-detail-body">
                    <div class="admin-booking-section-title">
                        <div>
                            <h3>Payment status</h3>
                            <p>Latest payment records connected to this booking.</p>
                        </div>

                        <a href="{{ route('admin.bookings.index') }}" class="admin-inline-link">View all bookings</a>
                    </div>

                    <div class="admin-booking-payment-stack">
                        <div class="admin-booking-info-item">
                            <span>Latest payment</span>
                            <strong>{{ $latestPayment?->getStatusLabel() ?? 'No payment yet' }}</strong>
                            <p>
                                {{ $latestPayment?->payment_method ? ucfirst($latestPayment->payment_method) : 'No payment method yet' }}
                            </p>
                        </div>

                        <div class="admin-booking-info-item">
                            <span>Total paid</span>
                            <strong>Rs {{ number_format((float) $booking->getTotalPaid(), 2) }}</strong>
                            <p>{{ $booking->isFullyPaid() ? 'Booking is fully paid.' : 'Amount remaining: Rs ' . number_format((float) $booking->getAmountPending(), 2) }}</p>
                        </div>

                        <div class="admin-booking-info-item">
                            <span>Payment progress</span>
                            <strong>{{ $booking->getPaymentProgress() }}%</strong>
                            <p>Based on successful payments only.</p>
                        </div>
                    </div>

                    <div class="admin-booking-payment-history">
                        <h4>Payment history</h4>

                        @forelse($booking->payments->sortByDesc('created_at') as $payment)
                            @php
                                $historyClass = match ($payment->payment_status) {
                                    'success' => 'status-approved',
                                    'pending' => 'status-pending',
                                    'failed' => 'status-rejected',
                                    default => 'status-neutral',
                                };
                            @endphp

                            <div class="admin-payment-history-item">
                                <div>
                                    <strong>{{ $payment->getStatusLabel() }}</strong>
                                    <p>{{ $payment->getMethodLabel() }} &middot; {{ $payment->getTypeLabel() }}</p>
                                </div>
                                <span class="status-pill {{ $historyClass }}">
                                    Rs {{ number_format((float) $payment->amount, 2) }}
                                </span>
                            </div>
                        @empty
                            <div class="admin-empty-note">No payment records found.</div>
                        @endforelse
                    </div>
                </div>
            </article>
        </section>

        <section class="content-card admin-booking-disputes-card">
            <div class="admin-booking-section-title">
                <div>
                    <h3>Handle disputes</h3>
                    <p>Basic dispute handling uses related reports for the user and property.</p>
                </div>

                <a href="{{ route('admin.reports.index') }}" class="admin-btn admin-btn-secondary">Open reports</a>
            </div>

            <div class="admin-booking-disputes-list">
                @forelse($relatedReports as $report)
                    @php
                        $reportStatusClass = match ($report->status) {
                            'resolved' => 'status-approved',
                            'under_review' => 'status-pending',
                            'dismissed' => 'status-neutral',
                            default => 'status-pending',
                        };
                    @endphp

                    <article class="admin-dispute-item">
                        <div class="admin-dispute-item-top">
                            <div>
                                <span class="admin-dispute-type">{{ $report->getReportTypeLabel() }}</span>
                                <h4>{{ \Illuminate\Support\Str::limit($report->reason, 80) }}</h4>
                                <p>
                                    {{ $report->reporter?->name ?? 'Unknown reporter' }}
                                    &middot; {{ $report->getTargetLabel() }}
                                </p>
                            </div>

                            <span class="status-pill {{ $reportStatusClass }}">{{ $report->getStatusLabel() }}</span>
                        </div>

                        <div class="admin-dispute-item-actions">
                            <a href="{{ route('admin.reports.show', $report) }}" class="admin-btn admin-btn-secondary">Open report</a>
                        </div>
                    </article>
                @empty
                    <div class="admin-empty-note">No related disputes found for this booking.</div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
