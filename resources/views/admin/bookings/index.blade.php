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
                            <th>Booking ID</th>
                            <th>User</th>
                            <th>Property</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Total Rent</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td>#{{ $booking->id }}</td>
                                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                <td>{{ $booking->property->title ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-pill status-neutral">
                                        {{ $booking->status ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-pill status-neutral">
                                        {{ $booking->payment_status ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $booking->check_in_date ?? 'N/A' }}</td>
                                <td>{{ $booking->check_out_date ?? 'N/A' }}</td>
                                <td>Rs {{ number_format((float) ($booking->total_rent ?? 0), 2) }}</td>
                                <td>{{ optional($booking->created_at)->format('M d, Y') ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="empty-cell">No bookings found.</td>
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
