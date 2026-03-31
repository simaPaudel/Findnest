@extends('user.layout')

@section('title', 'Saved Listings')
@section('page-title', 'Saved Listings')

@section('content')
<div class="py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-3xl font-bold fn-text-charcoal">Saved Listings</h1>
            <div class="fn-text-gray text-sm px-4 py-2 fn-glass-card">
                {{ $savedListings->total() }} property(ies) saved
            </div>
        </div>
        <p class="fn-text-gray">Your collection of favorite properties</p>
    </div>

    @if($savedListings->count() > 0)
        <!-- Listings Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($savedListings as $saved)
                @php
                    $property = $saved->property;
                    if (!$property) continue; // Skip if property doesn't exist
                    
                    $photos = is_array($property->photos) 
                        ? $property->photos 
                        : (json_decode($property->photos, true) ?? []);
                    $firstPhoto = $photos[0] ?? null;
                @endphp

                <div class="group fn-glass-card overflow-hidden hover:shadow-lg transition-all duration-300">
                    <!-- Image Container -->
                    <div class="relative h-56 overflow-hidden bg-gray-200">
                        @if($firstPhoto)
                            <img src="{{ asset('storage/' . $firstPhoto) }}" 
                                 alt="{{ $property->title }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                 onerror="this.src='https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&h=400&fit=crop'">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-300 to-gray-400">
                                <svg class="w-16 h-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <!-- Save Button -->
                        <button class="save-btn absolute top-3 right-3 bg-transparent border-none w-10 h-10 cursor-pointer flex items-center justify-center"
                                onclick="handleUnsave(this, {{ $property->id }}, {{ $saved->id }})">
                            <svg class="w-6 h-6 text-white transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24" style="filter: drop-shadow(0 1px 3px rgba(0,0,0,0.3))">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </button>

                        <!-- Badges -->
                        <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                            @if($property->is_verified)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold fn-bg-white text-green-700">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></path></svg>
                                    Verified
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5">
                        <!-- Title -->
                        <h3 class="font-semibold fn-text-charcoal text-base line-clamp-1 mb-1">
                            {{ $property->title }}
                        </h3>

                        <!-- Location -->
                        <div class="flex items-center text-sm fn-text-gray mb-3">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="truncate">{{ $property->city ?? $property->location }}</span>
                        </div>

                        <!-- Room Type & Features -->
                        <div class="flex items-center justify-between mb-4 text-xs">
                            <span class="inline-block px-2.5 py-1 rounded-full fn-bg-gray fn-text-gray">
                                {{ $property->room_type ?? 'Room' }}
                            </span>
                            <span class="inline-block px-2.5 py-1 rounded-full fn-bg-gray fn-text-gray">
                                @if($property->total_rooms)
                                    {{ $property->total_rooms }} room{{ $property->total_rooms > 1 ? 's' : '' }}
                                @else
                                    Shared
                                @endif
                            </span>
                        </div>

                        <!-- Price & CTA -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="flex flex-col">
                                <span class="text-2xl font-bold fn-text-charcoal">₹{{ number_format($property->rent_price, 0) }}</span>
                                <span class="text-xs fn-text-gray">per month</span>
                            </div>
                            <a href="{{ route('listings.show', $property->id) }}" 
                               class="inline-flex items-center justify-center px-4 py-2 rounded-lg fn-text-red font-semibold hover:fn-bg-red hover:text-white transition-all duration-300 border border-red-300 hover:border-transparent">
                                View Details
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($savedListings->hasPages())
            <div class="flex justify-center">
                {{ $savedListings->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="fn-glass-card p-16 text-center">
            <div class="flex justify-center mb-6">
                <svg class="w-24 h-24 fn-text-gray opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold fn-text-charcoal mb-2">No Saved Listings Yet</h3>
            <p class="fn-text-gray mb-8 text-lg">Start exploring and save your favorite properties to your collection</p>
            <a href="{{ route('listings.index') }}" class="inline-block px-6 py-3 rounded-lg fn-bg-red text-white font-semibold hover:opacity-90 transition">
                Browse Listings
            </a>
        </div>
    @endif
</div>

<style>
    .fn-bg-gray {
        background-color: #f7f7f7;
    }

    .fn-text-gray {
        color: #595959;
    }

    .fn-bg-red {
        background-color: #FF385C;
    }

    .fn-text-red {
        color: #FF385C;
    }

    .fn-glass-card {
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .save-btn svg {
        color: white;
        transition: all 0.3s ease;
    }

    .save-btn.saved svg {
        color: #FF385C;
        fill: #FF385C;
    }

    .group:hover .save-btn svg {
        transform: scale(1.1);
    }
</style>

<script>
    function handleUnsave(button, propertyId, savedListingId) {
        if (confirm('Remove from saved listings?')) {
            // Delete saved listing via CSRF POST
            fetch(`/user/saved-listings/${savedListingId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    // Reload the page to reflect changes
                    location.reload();
                } else {
                    alert('Failed to remove from saved listings');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }
    }

    // Check saved status on load
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.save-btn').forEach(btn => {
            btn.classList.add('saved');
        });
    });
</script>
@endsection
