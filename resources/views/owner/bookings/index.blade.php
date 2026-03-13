@extends('owner.layout')

@section('title', 'Booking Requests')
@section('page-title', 'Booking Requests')

@section('content')
<div class="content-card">
    <div class="table-responsive">
        @if($bookings->count() > 0)
            <table class="data-table">
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
                            <td class="text-mono">#{{ $booking->id }}</td>
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
                            <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                            <td>{{ $booking->duration_months }} month(s)</td>
                            <td class="text-bold">@npr($booking->total_rent)</td>
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
                                    @if($booking->confirmed_at)
                                        <small class="text-muted">Confirmed: {{ \Carbon\Carbon::parse($booking->confirmed_at)->format('M d, Y') }}</small>
                                    @elseif($booking->cancelled_at)
                                        <small class="text-muted">Cancelled: {{ \Carbon\Carbon::parse($booking->cancelled_at)->format('M d, Y') }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

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
@endsection
