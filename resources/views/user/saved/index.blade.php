@extends('user.layout')

@section('title', 'Saved Listings')
@section('page-title', 'Saved Listings')

@section('content')
<div class="fn-glass-card p-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold fn-text-charcoal">Your Saved Listings</h2>
        <div class="text-sm fn-text-gray">
            Total: {{ $savedListings->count() }} properties
        </div>
    </div>

    @if($savedListings->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($savedListings as $listing)
                <div class="fn-glass-card overflow-hidden">
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ asset('storage/' . $listing->photo) }}" 
                             alt="{{ $listing->title }}" 
                             class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                             onerror="this.src='https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&h=400&fit=crop'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <button class="absolute top-4 right-4 p-2 rounded-full fn-bg-white bg-opacity-80 hover:bg-opacity-100 transition">
                            <svg class="w-5 h-5 fn-text-red" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-5">
                        <h3 class="font-semibold fn-text-charcoal mb-1 line-clamp-1">{{ $listing->title }}</h3>
                        <p class="text-sm fn-text-gray mb-3">{{ $listing->location }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold fn-text-red">@npr($listing->price)/mo</span>
                            <a href="{{ route('listings.show', $listing->id) }}" class="fn-btn-secondary text-sm py-2 px-4">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16">
            <svg class="w-20 h-20 fn-text-gray mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <h3 class="text-xl font-semibold fn-text-charcoal mb-2">No Saved Listings Yet</h3>
            <p class="fn-text-gray mb-6">Start browsing and save your favorite properties</p>
            <a href="{{ route('listings.index') }}" class="fn-btn-primary inline-block">Browse Listings</a>
        </div>
    @endif
</div>
@endsection
