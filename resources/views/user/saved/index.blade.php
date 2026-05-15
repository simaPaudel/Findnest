@extends('user.layout')

@section('title', 'Saved Listings')
@section('page-title', 'Saved Listings')

@section('content')
<div class="py-8">
    <div class="mb-8">
        <div class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 shadow-sm">
            {{ $savedListings->total() }} saved listing{{ $savedListings->total() === 1 ? '' : 's' }}
        </div>
        <p class="mt-3 text-slate-500">Your collection of favorite properties</p>
    </div>

    @if($savedListings->count() > 0)
    <div class="saved-listings-grid grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-12 mb-12 items-start">
        @foreach($savedListings as $saved)
            @php
                $property = $saved->property;
            @endphp

            @continue(!$property)

            @php
                $primaryImage = $property->images->firstWhere('is_primary', true) ?? $property->images->sortBy('order')->first();
                $imageUrl = $primaryImage ? $primaryImage->getUrl() : asset('images/property-placeholder.jpg');

                $minRoomPrice = $property->min_room_price !== null ? (float) $property->min_room_price : ($property->rooms->min('price') !== null ? (float) $property->rooms->min('price') : null);
                $maxRoomPrice = $property->max_room_price !== null ? (float) $property->max_room_price : ($property->rooms->max('price') !== null ? (float) $property->rooms->max('price') : null);
                $availableRooms = (int) ($property->available_rooms_count ?? 0);

                if ($property->rental_mode === 'per_room') {
                    if ($minRoomPrice === null || $maxRoomPrice === null) {
                        $priceAmount = 'Price on request';
                        $priceSuffix = null;
                    } elseif ($minRoomPrice === $maxRoomPrice) {
                        $priceAmount = 'Rs ' . number_format($minRoomPrice);
                        $priceSuffix = '/month';
                    } else {
                        $priceAmount = 'Rs ' . number_format($minRoomPrice) . ' - Rs ' . number_format($maxRoomPrice);
                        $priceSuffix = '/month';
                    }

                    $subtitle = $property->property_availability_label
                        ?? ($availableRooms === 1
                            ? '1 room available'
                            : ($availableRooms > 1 ? $availableRooms . ' rooms available' : 'No rooms available right now'));
                } else {
                    $priceAmount = 'Rs ' . number_format((float) $property->rent_price);
                    $priceSuffix = '/month';
                    $subtitle = $property->property_availability_label ?? 'Available for booking';
                }

                $availabilityLabel = $property->is_property_bookable ? 'Available' : 'Unavailable';
                $availabilityClass = $property->is_property_bookable ? 'available' : 'unavailable';
                $priceToneClass = $priceAmount === 'Price on request' ? 'is-muted' : 'is-red';
                $propertyReviewCount = (int) ($property->property_reviews_count ?? 0);
                $propertyAverageRating = (float) ($property->property_average_rating ?? 0);
            @endphp

            <a href="{{ route('listings.show', $property) }}" class="listing-card">
                <div class="listing-image-wrap aspect-[4/3] mb-3">
                    <img
                        src="{{ $imageUrl }}"
                        alt="{{ $property->title }}"
                        class="listing-image"
                        loading="lazy"
                        onerror="this.src='{{ asset('images/property-placeholder.jpg') }}'"
                    >

                    <div class="absolute inset-x-0 top-0 flex items-start justify-between gap-3 p-3">
                        <span class="listing-chip bg-white/92 text-slate-800 shadow-sm">
                            {{ $property->rental_mode === 'per_room' ? 'Room choice' : 'Whole place' }}
                        </span>

                        <button
                            class="save-btn saved"
                            title="Remove from saved listings"
                            onclick="handleUnsave(event, this, {{ $property->id }}, {{ $saved->id }})"
                        >
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="listing-details">
                    <div class="flex items-start justify-between gap-3">
                        <h2 class="text-[15px] font-semibold text-slate-950 leading-6 line-clamp-1">{{ $property->title }}</h2>
                        <span class="shrink-0 text-sm font-semibold text-slate-900">{{ $property->city ?: 'Nepal' }}</span>
                    </div>

                    <p class="text-sm text-slate-500 line-clamp-1">{{ $property->address ?: ($property->location ?: 'Location not specified') }}</p>
                    <p class="flex items-center gap-1.5 text-sm text-slate-600">
                        <span class="text-amber-400">★</span>
                        @if($propertyReviewCount > 0)
                            <span>{{ number_format($propertyAverageRating, 1) }} · {{ $propertyReviewCount }} {{ $propertyReviewCount === 1 ? 'review' : 'reviews' }}</span>
                        @else
                            <span>No reviews yet</span>
                        @endif
                    </p>

                    <div class="flex items-center gap-2">
                        <span class="listing-status-pill {{ $availabilityClass }}">{{ $availabilityLabel }}</span>
                        <p class="text-sm text-slate-500 line-clamp-1">{{ $subtitle }}</p>
                    </div>

                    <p class="text-sm text-slate-500 line-clamp-1">{{ $property->getPropertyTypeLabel() }}</p>

                    <div class="listing-price-row">
                        <p class="listing-price">
                            <span class="listing-price-value {{ $priceToneClass }}">{{ $priceAmount }}</span>
                            @if($priceSuffix)
                                <span class="listing-price-suffix">{{ $priceSuffix }}</span>
                            @endif
                        </p>

                        <span class="listing-view-btn">View</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    @if($savedListings->hasPages())
        <div class="flex justify-center">
            {{ $savedListings->links() }}
        </div>
    @endif
    @else
    <div class="rounded-3xl border border-slate-200 bg-white px-6 py-14 text-center shadow-sm">
        <svg class="w-20 h-20 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
        </svg>
        <h3 class="text-2xl font-bold text-slate-900 mb-2">No Saved Listings Yet</h3>
        <p class="text-slate-500 mb-8 text-lg">Start exploring and save your favorite properties to your collection</p>
        <a href="{{ route('listings.index') }}" class="inline-flex items-center justify-center rounded-xl bg-rose-500 px-6 py-3 font-semibold text-white hover:bg-rose-600 transition">
            Browse Listings
        </a>
    </div>
    @endif
</div>

<style>
    .saved-listings-grid {
        max-width: 1650px;
    }

    .listing-card {
        display: block;
        color: inherit;
        text-decoration: none;
    }

    .listing-card:hover .listing-image {
        transform: scale(1.04);
    }

    .listing-image-wrap {
        position: relative;
        overflow: hidden;
        border-radius: 22px;
        background: #f1f5f9;
    }

    .listing-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s ease;
    }

    .listing-chip {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.45rem 0.85rem;
        font-size: 0.72rem;
        font-weight: 700;
        line-height: 1;
    }

    .listing-details {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .listing-status-pill {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.3rem 0.65rem;
        font-size: 0.68rem;
        font-weight: 700;
        line-height: 1;
        white-space: nowrap;
    }

    .listing-status-pill.available {
        background: #ecfdf5;
        color: #047857;
    }

    .listing-status-pill.unavailable {
        background: #fff7ed;
        color: #c2410c;
    }

    .listing-price-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-top: 0.15rem;
    }

    .listing-price {
        display: flex;
        align-items: baseline;
        gap: 0.2rem;
        min-width: 0;
    }

    .listing-price-value {
        font-size: 0.95rem;
        font-weight: 700;
    }

    .listing-price-value.is-red {
        color: #e11d48;
    }

    .listing-price-value.is-muted {
        color: #64748b;
    }

    .listing-price-suffix {
        color: #94a3b8;
        font-size: 0.72rem;
        font-weight: 600;
    }

    .listing-view-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: #ff385c;
        color: #ffffff;
        padding: 0.42rem 0.85rem;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        text-decoration: none;
        transition: background 0.2s ease, transform 0.2s ease;
        white-space: nowrap;
    }

    .listing-view-btn:hover {
        background: #e11d48;
        transform: translateY(-1px);
    }

    .save-btn {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(15, 23, 42, 0.32);
        backdrop-filter: blur(8px);
        color: white;
        cursor: pointer;
        z-index: 10;
    }

    .save-btn svg {
        width: 19px;
        height: 19px;
        stroke-width: 2;
        transition: transform 0.2s ease;
    }

    .save-btn:hover svg {
        transform: scale(1.08);
    }

    .save-btn.saved {
        background: #ff385c;
    }

    .line-clamp-1,
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-1 {
        -webkit-line-clamp: 1;
    }

    .line-clamp-2 {
        -webkit-line-clamp: 2;
    }

    @media (min-width: 1280px) {
        .saved-listings-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    @media (max-width: 1279px) and (min-width: 768px) {
        .saved-listings-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) and (min-width: 640px) {
        .saved-listings-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 639px) {
        .saved-listings-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    function handleUnsave(event, button, propertyId, savedListingId) {
        event.preventDefault();
        event.stopPropagation();

        if (confirm('Remove from saved listings?')) {
            fetch(`/user/saved-listings/${savedListingId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
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
</script>
@endsection
