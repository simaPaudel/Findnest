@extends('user.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Saved Listings -->
    <div class="fn-glass-card p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 fn-bg-red rounded-xl bg-opacity-10">
                <svg class="w-6 h-6 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
        </div>
        <h3 class="text-2xl font-bold fn-text-charcoal mb-1">{{ $savedListingsCount }}</h3>
        <p class="text-sm fn-text-gray">Saved Listings</p>
    </div>

    <!-- Roommate Matches -->
    <div class="fn-glass-card p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 fn-bg-red rounded-xl bg-opacity-10">
                <svg class="w-6 h-6 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
        <h3 class="text-2xl font-bold fn-text-charcoal mb-1">{{ $roommateMatchesCount }}</h3>
        <p class="text-sm fn-text-gray">Roommate Matches</p>
        @if(!$roommatePrefExists)
            <a href="{{ route('user.roommate-preferences.edit') }}" class="text-xs fn-text-red hover:underline mt-2 inline-block">Set preferences</a>
        @endif
    </div>

    <!-- Active Bookings -->
    <div class="fn-glass-card p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 fn-bg-red rounded-xl bg-opacity-10">
                <svg class="w-6 h-6 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <h3 class="text-2xl font-bold fn-text-charcoal mb-1">{{ $activeBookingsCount }}</h3>
        <p class="text-sm fn-text-gray">Active Bookings</p>
    </div>

    <!-- Profile Complete -->
    <div class="fn-glass-card p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 fn-bg-red rounded-xl bg-opacity-10">
                <svg class="w-6 h-6 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        @php
            $user = auth()->user();
            $completeness = 50; // Base
            if($user->bio) $completeness += 15;
            if($user->phone) $completeness += 15;
            if($user->gender) $completeness += 10;
            if($user->profile_photo) $completeness += 10;
        @endphp
        <h3 class="text-2xl font-bold fn-text-charcoal mb-1">{{ $completeness }}%</h3>
        <p class="text-sm fn-text-gray">Profile Complete</p>
        @if($completeness < 100)
            <a href="{{ route('user.profile.edit') }}" class="text-xs fn-text-red hover:underline mt-2 inline-block">Complete now</a>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Section -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Featured Listings -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold fn-text-charcoal">Featured Listings</h2>
                <a href="{{ route('listings.index') }}" class="fn-text-red hover:underline text-sm font-medium">View All</a>
            </div>

            @if($featuredListings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($featuredListings as $property)
                        <a href="{{ route('listings.show', $property->id) }}" class="fn-glass-card overflow-hidden cursor-pointer block">
                            <div class="relative h-48 overflow-hidden">
                                @php
                                    $firstPhoto = !empty($property->photos) ? $property->photos[0] : null;
                                @endphp
                                @if($firstPhoto)
                                    <img src="{{ asset('storage/' . $firstPhoto) }}" 
                                         alt="{{ $property->title }}" 
                                         class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                                         onerror="this.src='https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&h=400&fit=crop'">
                                @else
                                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&h=400&fit=crop" 
                                         alt="{{ $property->title }}" 
                                         class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                @if($property->is_verified)
                                    <div class="absolute top-4 right-4">
                                        <span class="fn-badge-green text-white bg-green-500 text-xs px-3 py-1 rounded-lg">Verified</span>
                                    </div>
                                @endif
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h3 class="text-lg font-bold fn-text-white mb-1 line-clamp-1">{{ $property->title }}</h3>
                                    <p class="text-sm fn-text-white opacity-90">{{ $property->city }}</p>
                                </div>
                            </div>
                            <div class="p-5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs fn-text-gray mb-1">Starting from</p>
                                        <p class="text-xl font-bold fn-text-red">@npr($property->rent_price)/mo</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="fn-badge fn-badge-gray">{{ ucfirst($property->room_type) }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="fn-glass-card p-12 text-center">
                    <svg class="w-16 h-16 fn-text-gray mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <p class="fn-text-gray">No featured listings available</p>
                    <a href="{{ route('listings.index') }}" class="fn-btn-primary mt-4 inline-block">Browse All Listings</a>
                </div>
            @endif
        </section>
    </div>

    <!-- Sidebar -->
    <div class="space-y-8">
        <!-- Recent Activity -->
        <section>
            <h2 class="text-xl font-bold fn-text-charcoal mb-6">Recent Activity</h2>
            <div class="fn-glass-card p-6 space-y-4">
                @forelse($recentBookings as $booking)
                    <div class="pb-4 border-b fn-border-gray last:border-0 last:pb-0">
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="font-semibold fn-text-charcoal text-sm line-clamp-1">{{ $booking->property->title }}</h4>
                            @if($booking->status === 'confirmed')
                                <span class="fn-badge-green text-xs px-2 py-1 rounded">Confirmed</span>
                            @elseif($booking->status === 'pending')
                                <span class="fn-badge-yellow text-xs px-2 py-1 rounded">Pending</span>
                            @else
                                <span class="fn-badge-gray text-xs px-2 py-1 rounded">{{ ucfirst($booking->status) }}</span>
                            @endif
                        </div>
                        <p class="text-xs fn-text-gray">{{ $booking->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-sm fn-text-gray text-center py-4">No recent activity</p>
                @endforelse
            </div>
        </section>

        <!-- Notifications -->
        <section>
            <h2 class="text-xl font-bold fn-text-charcoal mb-6">Notifications</h2>
            <div class="fn-glass-card p-6 space-y-4">
                @php
                    $unpaidBookings = $recentBookings->where('payment_status', 'unpaid');
                @endphp
                
                @if($unpaidBookings->count() > 0)
                    @foreach($unpaidBookings as $booking)
                        <div class="flex gap-3 pb-4 border-b fn-border-gray last:border-0 last:pb-0">
                            <div class="p-2 fn-bg-red rounded-lg bg-opacity-10 h-fit">
                                <svg class="w-5 h-5 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium fn-text-charcoal mb-1">Payment Due</p>
                                <p class="text-xs fn-text-gray">{{ $booking->property->title }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if(!$roommatePrefExists)
                    <div class="flex gap-3 pb-4 border-b fn-border-gray last:border-0 last:pb-0">
                        <div class="p-2 fn-bg-red rounded-lg bg-opacity-10 h-fit">
                            <svg class="w-5 h-5 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium fn-text-charcoal mb-1">Set Roommate Preferences</p>
                            <p class="text-xs fn-text-gray mb-2">Get better roommate matches</p>
                            <a href="{{ route('user.roommate-preferences.edit') }}" class="text-xs fn-text-red hover:underline font-medium">Set Now →</a>
                        </div>
                    </div>
                @endif

                @if($completeness < 100)
                    <div class="flex gap-3">
                        <div class="p-2 fn-bg-red rounded-lg bg-opacity-10 h-fit">
                            <svg class="w-5 h-5 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium fn-text-charcoal mb-1">Complete Your Profile</p>
                            <p class="text-xs fn-text-gray mb-2">{{ $completeness }}% complete</p>
                            <a href="{{ route('user.profile.edit') }}" class="text-xs fn-text-red hover:underline font-medium">Complete Now →</a>
                        </div>
                    </div>
                @endif

                @if($unpaidBookings->count() == 0 && $roommatePrefExists && $completeness >= 100)
                    <p class="text-sm fn-text-gray text-center py-4">No notifications</p>
                @endif
            </div>
        </section>

        <!-- Quick Actions -->
        <section>
            <h2 class="text-xl font-bold fn-text-charcoal mb-6">Quick Actions</h2>
            <div class="fn-glass-card p-6 space-y-3">
                <a href="{{ route('listings.index') }}" class="fn-btn-primary w-full block text-center">Browse Listings</a>
                <a href="{{ route('user.bookings.index') }}" class="fn-btn-secondary w-full block text-center">View My Bookings</a>
            </div>
        </section>
    </div>
</div>
@endsection
