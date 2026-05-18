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

        $latestSuccessfulPayment = $booking->payments
            ->where('payment_status', 'success')
            ->sortByDesc('paid_at')
            ->first();

        $latestPayoutState = $latestSuccessfulPayment
            ? ($latestSuccessfulPayment->payout_status ?? 'pending')
            : null;
        $latestPayoutClass = match ($latestPayoutState) {
            'completed' => 'status-approved',
            'pending' => 'status-pending',
            default => 'status-neutral',
        };

        $bookingOwner = $booking->property?->owner;
        $bookingOwnerPayoutQrUrl = ($bookingOwner && method_exists($bookingOwner, 'payoutQrUrl'))
            ? $bookingOwner->payoutQrUrl()
            : null;
    @endphp

    <style>
        .admin-booking-details-page {
            display: grid;
            gap: 16px;
        }

        .admin-booking-detail-hero {
            align-items: flex-start;
        }

        .admin-booking-detail-hero-actions {
            align-items: flex-start;
        }

        .admin-booking-detail-layout {
            grid-template-columns: minmax(0, 1.06fr) minmax(0, 0.94fr);
        }

        .admin-booking-detail-body {
            gap: 14px;
            padding: 16px;
        }

        .admin-booking-overview-top {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .admin-booking-overview-thumb {
            width: 132px;
            height: 96px;
            border: 1px solid var(--fn-line);
            border-radius: 16px;
            overflow: hidden;
            background: #f8fafc;
            flex-shrink: 0;
        }

        .admin-booking-overview-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .admin-booking-overview-copy {
            min-width: 0;
            display: grid;
            gap: 6px;
            flex: 1;
        }

        .admin-booking-overview-copy h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .admin-booking-overview-copy p {
            margin: 0;
            color: var(--fn-muted);
            font-size: 13px;
            line-height: 1.5;
        }

        .admin-booking-user-row {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            padding: 12px 14px;
            border: 1px solid var(--fn-line);
            border-radius: 14px;
            background: #fff;
        }

        .admin-booking-user-copy {
            min-width: 0;
        }

        .admin-booking-user-copy strong {
            display: block;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        .admin-booking-user-copy span {
            display: block;
            margin-top: 3px;
            color: var(--fn-muted);
            font-size: 13px;
            line-height: 1.45;
            word-break: break-word;
        }

        .admin-booking-meta-grid {
            gap: 10px;
        }

        .admin-booking-mini-card {
            padding: 12px 14px;
            border: 1px solid var(--fn-line);
            border-radius: 14px;
            background: #fff;
            display: grid;
            gap: 6px;
        }

        .admin-booking-mini-card span {
            color: var(--fn-muted);
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .admin-booking-mini-card strong {
            font-size: 15px;
            font-weight: 800;
            line-height: 1.35;
        }

        .admin-booking-mini-card p {
            margin: 0;
            color: var(--fn-muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .admin-booking-summary-card .admin-summary-value {
            display: block;
            padding: 0 !important;
            margin: 4px 0 0;
            background: transparent !important;
            border: 0 !important;
            box-shadow: none !important;
            letter-spacing: -0.02em;
        }

        .admin-booking-summary-card .admin-summary-value.status-approved {
            color: #059669 !important;
        }

        .admin-booking-summary-card .admin-summary-value.status-pending {
            color: #d97706 !important;
        }

        .admin-booking-summary-card .admin-summary-value.status-rejected,
        .admin-booking-summary-card .admin-summary-value.status-neutral {
            color: #475569 !important;
        }

        .admin-booking-side-stack {
            display: grid;
            gap: 14px;
        }

        .admin-booking-payment-stack {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .admin-booking-payment-history h4 {
            margin: 0;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: -0.01em;
        }

        .admin-payment-history-item {
            align-items: center;
        }

        .admin-booking-owner-payout {
            margin-top: 0;
            padding-top: 0;
            border-top: 0;
        }

        .admin-booking-owner-payout-extra {
            grid-template-columns: 1fr;
        }

        .admin-booking-owner-payout-qr {
            max-width: 220px;
        }

        .admin-booking-owner-payout-qr img {
            width: 100%;
            max-width: 180px;
            height: auto;
            object-fit: contain;
            align-self: start;
        }

        .admin-booking-disputes-card {
            padding: 16px;
        }

        @media (max-width: 980px) {
            .admin-booking-detail-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .admin-booking-overview-top {
                flex-direction: column;
            }

            .admin-booking-overview-thumb {
                width: 100%;
                height: 180px;
            }

            .admin-booking-payment-stack {
                grid-template-columns: 1fr;
            }

            .admin-booking-detail-hero-actions {
                width: 100%;
            }

            .admin-booking-detail-hero-actions .admin-btn,
            .admin-booking-detail-hero-actions .admin-booking-release-form {
                width: 100%;
            }

            .admin-booking-detail-hero-actions .admin-btn,
            .admin-booking-detail-hero-actions .admin-booking-release-form button {
                width: 100%;
            }
        }
    </style>

    <div class="admin-bookings-page admin-booking-details-page">
        <section class="content-card admin-booking-detail-hero">
            <div class="admin-booking-detail-hero-copy">
                <p class="page-kicker">Booking Review</p>
                <h2>{{ $booking->property->title ?? 'Booking details' }}</h2>
                <p>Scan booking, payment, and payout information in one place.</p>
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
                    {{ $booking->isConfirmed() ? 'Ready for release after payment is verified.' : 'Pulled directly from the booking record.' }}
                </div>
            </article>

            <article class="content-card admin-booking-summary-card" id="payment-status">
                <span class="admin-summary-label">Payment status</span>
                <div class="admin-summary-value {{ $paymentClass }}">{{ $paymentState === 'paid' ? 'Paid' : ucfirst($paymentState) }}</div>
                <div class="admin-summary-note">
                    Rs {{ number_format((float) $booking->getTotalPaid(), 2) }} paid of Rs {{ number_format((float) ($booking->total_rent ?? 0), 2) }} total.
                </div>
            </article>

            <article class="content-card admin-booking-summary-card" id="payout-status">
                <span class="admin-summary-label">Payout status</span>
                <div class="admin-summary-value {{ $latestPayoutClass }}">
                    {{ $latestSuccessfulPayment ? $latestSuccessfulPayment->getPayoutStatusLabel() : 'Not applicable' }}
                </div>
                <div class="admin-summary-note">
                    {{ $latestSuccessfulPayment ? 'Manual payout tracking is based on the latest successful payment.' : 'No successful payment is available yet.' }}
                </div>
            </article>
        </section>

        <section class="admin-booking-detail-layout">
            <article class="content-card admin-booking-detail-card">
                <div class="admin-booking-detail-body">
                    <div class="admin-booking-overview-top">
                        <div class="admin-booking-overview-thumb">
                            <img src="{{ $propertyImageUrl }}" alt="{{ $booking->property->title ?? 'Booking property' }}">
                        </div>

                        <div class="admin-booking-overview-copy">
                            <div class="admin-booking-section-title">
                                <div>
                                    <h3>Booking overview</h3>
                                    <p>Guest, property, and stay details at a glance.</p>
                                </div>
                            </div>

                            <div class="admin-booking-user-row">
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
                        </div>
                    </div>

                    <div class="admin-booking-meta-grid">
                        <div class="admin-booking-mini-card">
                            <span>Property</span>
                            <strong>{{ $booking->property->title ?? 'N/A' }}</strong>
                            <p>{{ $booking->property->city ?? 'N/A' }}</p>
                        </div>

                        <div class="admin-booking-mini-card">
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

                        <div class="admin-booking-mini-card">
                            <span>Stay dates</span>
                            <strong>{{ optional($booking->check_in_date)->format('M d, Y') ?? 'N/A' }}</strong>
                            <p>Until {{ optional($booking->check_out_date)->format('M d, Y') ?? 'N/A' }}</p>
                        </div>

                        <div class="admin-booking-mini-card">
                            <span>Booking value</span>
                            <strong>Rs {{ number_format((float) ($booking->total_rent ?? 0), 2) }}</strong>
                            <p>Advance paid Rs {{ number_format((float) ($booking->advance_payment ?? 0), 2) }}</p>
                        </div>
                    </div>

                    @if($booking->special_requests)
                        <div class="admin-booking-mini-card">
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
                            <h3>Payment &amp; payout</h3>
                            <p>Track payment history and the owner's payout route.</p>
                        </div>
                    </div>

                    <div class="admin-booking-side-stack">
                        <div class="admin-booking-payment-stack">
                            <div class="admin-booking-mini-card">
                                <span>Latest payment</span>
                                <strong>{{ $latestPayment?->getStatusLabel() ?? 'No payment yet' }}</strong>
                                <p>
                                    {{ $latestPayment?->payment_method ? ucfirst($latestPayment->payment_method) : 'No payment method yet' }}
                                </p>
                            </div>

                            <div class="admin-booking-mini-card">
                                <span>Total paid</span>
                                <strong>Rs {{ number_format((float) $booking->getTotalPaid(), 2) }}</strong>
                                <p>
                                    {{ $booking->isFullyPaid() ? 'Booking is fully paid.' : 'Amount remaining: Rs ' . number_format((float) $booking->getAmountPending(), 2) }}
                                </p>
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

                                    $historyPayoutState = $payment->isSuccessful()
                                        ? ($payment->payout_status ?? 'pending')
                                        : 'not_applicable';
                                    $historyPayoutClass = match ($historyPayoutState) {
                                        'completed' => 'status-approved',
                                        'pending' => 'status-pending',
                                        default => 'status-neutral',
                                    };
                                @endphp

                                <div class="admin-payment-history-item">
                                    <div>
                                        <strong>{{ $payment->getStatusLabel() }}</strong>
                                        <p>{{ $payment->getMethodLabel() }} &middot; {{ $payment->getTypeLabel() }}</p>
                                    </div>
                                    <div class="admin-payment-history-item-actions">
                                        <span class="status-pill {{ $historyClass }}">
                                            Rs {{ number_format((float) $payment->amount, 2) }}
                                        </span>

                                        <span class="status-pill {{ $historyPayoutClass }}">
                                            Payout: {{ $payment->isSuccessful() ? $payment->getPayoutStatusLabel() : 'N/A' }}
                                        </span>

                                        @if($payment->isSuccessful() && $payment->isPayoutPending())
                                            <form method="POST" action="{{ route('admin.payments.mark-paid', $payment) }}" class="admin-payment-history-action-form">
                                                @csrf
                                                <button type="submit" class="admin-btn admin-btn-success admin-btn-sm">
                                                    Mark as Paid
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="admin-empty-note">No payment records found.</div>
                            @endforelse
                        </div>

                        <div class="admin-booking-owner-payout">
                            <div class="admin-booking-section-title">
                                <div>
                                    <h3>Owner payout information</h3>
                                    <p>Manual payout details for the property owner.</p>
                                </div>
                            </div>

                            @if($bookingOwner)
                                <div class="admin-booking-meta-grid">
                                    <div class="admin-booking-mini-card">
                                        <span>Owner name</span>
                                        <strong>{{ $bookingOwner->name ?? 'N/A' }}</strong>
                                        <p>{{ $bookingOwner->email ?? 'N/A' }}</p>
                                    </div>

                                    <div class="admin-booking-mini-card">
                                        <span>Payout method</span>
                                        <strong>{{ $bookingOwner->payout_method ? ucfirst($bookingOwner->payout_method) : 'Not set' }}</strong>
                                        <p>Use the stored payout route for manual transfer.</p>
                                    </div>

                                    <div class="admin-booking-mini-card">
                                        <span>Account name</span>
                                        <strong>{{ $bookingOwner->payout_account_name ?? 'Not set' }}</strong>
                                        <p>Recipient name for the payout.</p>
                                    </div>

                                    <div class="admin-booking-mini-card">
                                        <span>Account number</span>
                                        <strong>{{ $bookingOwner->payout_account_number ?? 'Not set' }}</strong>
                                        <p>Number or wallet ID used for payout.</p>
                                    </div>

                                    <div class="admin-booking-mini-card">
                                        <span>Khalti / eSewa number</span>
                                        <strong>{{ $bookingOwner->payout_wallet_number ?? 'Not set' }}</strong>
                                        <p>Wallet number used for digital payout.</p>
                                    </div>

                                    <div class="admin-booking-mini-card">
                                        <span>Bank name</span>
                                        <strong>{{ $bookingOwner->payout_bank_name ?? 'Not set' }}</strong>
                                        <p>Bank used when the owner prefers bank transfer.</p>
                                    </div>
                                </div>

                                @if($bookingOwnerPayoutQrUrl || $bookingOwner->payout_notes)
                                    <div class="admin-booking-owner-payout-extra">
                                        @if($bookingOwnerPayoutQrUrl)
                                            <div class="admin-booking-owner-payout-qr">
                                                <span>QR code</span>
                                                <img src="{{ $bookingOwnerPayoutQrUrl }}" alt="Owner payout QR">
                                            </div>
                                        @endif

                                        @if($bookingOwner->payout_notes)
                                            <div class="admin-booking-owner-payout-notes">
                                                <span>Notes</span>
                                                <p>{{ $bookingOwner->payout_notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="admin-empty-note">No owner payout information available.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </article>
        </section>

        @if($relatedDisputesCount > 0)
            <section class="content-card admin-booking-disputes-card">
                <div class="admin-booking-section-title">
                    <div>
                        <h3>Related disputes</h3>
                        <p>Only reports related to this booking, its property, or the guest are shown here.</p>
                    </div>
                </div>

                <div class="admin-booking-disputes-list">
                    @foreach($relatedReports as $report)
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

                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
