@extends('user.layout')

@section('title', 'My Bookings')
@section('page-title', 'My Bookings')

@section('content')
<div class="fn-glass-card p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold fn-text-charcoal">All Your Bookings</h2>
        <div class="text-sm fn-text-gray">
            Total: {{ $bookings->total() }} bookings
        </div>
    </div>

    @if($bookings->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b fn-border-gray">
                        <th class="text-left py-4 px-4 font-semibold fn-text-charcoal">Property</th>
                        <th class="text-left py-4 px-4 font-semibold fn-text-charcoal">Check-in</th>
                        <th class="text-left py-4 px-4 font-semibold fn-text-charcoal">Duration</th>
                        <th class="text-left py-4 px-4 font-semibold fn-text-charcoal">Total Rent</th>
                        <th class="text-left py-4 px-4 font-semibold fn-text-charcoal">Status</th>
                        <th class="text-left py-4 px-4 font-semibold fn-text-charcoal">Payment</th>
                        <th class="text-left py-4 px-4 font-semibold fn-text-charcoal">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr class="border-b fn-border-gray hover:bg-gray-50 transition">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    @php
                                        $firstPhoto = !empty($booking->property->photos) ? $booking->property->photos[0] : null;
                                    @endphp
                                    @if($firstPhoto)
                                        <img src="{{ asset('storage/' . $firstPhoto) }}" 
                                             alt="{{ $booking->property->title }}" 
                                             class="w-12 h-12 rounded-lg object-cover"
                                             onerror="this.src='https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=100&h=100&fit=crop'">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=100&h=100&fit=crop" 
                                             alt="{{ $booking->property->title }}" 
                                             class="w-12 h-12 rounded-lg object-cover">
                                    @endif
                                    <div>
                                        <a href="{{ route('listings.show', $booking->property->id) }}" class="font-semibold fn-text-charcoal hover:fn-text-red line-clamp-1">
                                            {{ $booking->property->title }}
                                        </a>
                                        <p class="text-xs fn-text-gray">{{ $booking->property->city }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-sm fn-text-charcoal">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-sm fn-text-charcoal">{{ $booking->duration_months }} month(s)</span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-sm font-semibold fn-text-red">@npr($booking->total_rent)</span>
                            </td>
                            <td class="py-4 px-4">
                                @if($booking->status === 'confirmed')
                                    <span class="fn-badge-green text-xs px-3 py-1 rounded-lg">Confirmed</span>
                                @elseif($booking->status === 'pending')
                                    <span class="fn-badge-yellow text-xs px-3 py-1 rounded-lg">Pending</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="fn-badge-gray text-xs px-3 py-1 rounded-lg">Cancelled</span>
                                @else
                                    <span class="fn-badge fn-badge-gray text-xs">{{ ucfirst($booking->status) }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                @if($booking->payment_status === 'paid')
                                    <span class="text-green-600 text-xs font-medium">Paid</span>
                                @elseif($booking->payment_status === 'partial')
                                    <span class="text-yellow-600 text-xs font-medium">Partial</span>
                                @else
                                    <span class="text-red-600 text-xs font-medium">Unpaid</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                @if(in_array($booking->status, ['pending', 'confirmed']))
                                    <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="text-xs fn-text-red hover:underline font-medium">
                                            Cancel
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs fn-text-gray">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <svg class="w-20 h-20 fn-text-gray mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xl font-semibold fn-text-charcoal mb-2">No Bookings Yet</h3>
            <p class="fn-text-gray mb-6">Start by browsing our available listings</p>
            <a href="{{ route('listings.index') }}" class="fn-btn-primary inline-block">Browse Listings</a>
        </div>
    @endif
</div>
@endsection
