@extends('admin.layout')

@section('title', 'Bookings')
@section('page_title', 'Booking Monitoring')

@section('content')
    <div class="admin-dashboard">
        <section class="content-card">
            <div class="content-card-header">
                <div>
                    <h2>All Bookings</h2>
                    <p>{{ $bookings->total() }} booking{{ $bookings->total() === 1 ? '' : 's' }} currently tracked.</p>
                </div>
            </div>

            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Booking</th>
                            <th>User</th>
                            <th>Property</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Stay</th>
                            <th>Total Rent</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            @php
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
                            @endphp
                            <tr>
                                <td>
                                    <div class="primary-text">#{{ $booking->id }}</div>
                                    <div class="muted-text">{{ optional($booking->created_at)->format('M d, Y') ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="primary-text">{{ $booking->user->name ?? 'N/A' }}</div>
                                    <div class="muted-text">{{ $booking->user->email ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="primary-text">{{ $booking->property->title ?? 'N/A' }}</div>
                                    <div class="muted-text">
                                        @if($booking->room)
                                            Room: {{ $booking->room->room_name }}
                                        @else
                                            Full property booking
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="status-pill {{ $statusClass }}">
                                        {{ $booking->getStatusLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-pill {{ $paymentClass }}">
                                        {{ $paymentState === 'paid' ? 'Paid' : ucfirst($paymentState) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="primary-text">{{ optional($booking->check_in_date)->format('M d, Y') ?? 'N/A' }}</div>
                                    <div class="muted-text">
                                        Until {{ optional($booking->check_out_date)->format('M d, Y') ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="primary-text">Rs {{ number_format((float) ($booking->total_rent ?? 0), 2) }}</div>
                                    <div class="muted-text">
                                        Paid Rs {{ number_format((float) $booking->getTotalPaid(), 2) }}
                                    </div>
                                </td>
                                <td>
                                    @if($booking->status === 'confirmed' && $booking->hasSuccessfulPayment())
                                        <div class="admin-action-row">
                                            <form method="POST" action="{{ route('admin.bookings.release', $booking) }}">
                                                @csrf
                                                <button type="submit" class="admin-action-button">
                                                    Release Booking
                                                </button>
                                            </form>
                                            <div class="admin-meta-note">
                                                Marks the stay completed and reopens availability.
                                            </div>
                                        </div>
                                    @elseif($booking->status === 'completed')
                                        <div class="admin-meta-note">Released and completed.</div>
                                    @elseif($booking->status === 'confirmed')
                                        <div class="admin-meta-note">Waiting for successful payment before release.</div>
                                    @else
                                        <div class="admin-meta-note">No release action needed.</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="empty-cell">No bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($bookings->hasPages())
                <div style="padding: 18px 22px; border-top: 1px solid var(--fn-line);">
                    {{ $bookings->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection
