@extends('admin.layout')

@section('title', $property->title . ' | Property Details')
@section('page_title', 'Property Details')
@section('hide_pagebar', 'true')

@section('content')
    @php
        $galleryImages = $property->images
            ->sortBy(function ($image) {
                return [
                    $image->is_primary ? 0 : 1,
                    $image->order ?? PHP_INT_MAX,
                    $image->id,
                ];
            })
            ->values();

        $galleryItems = $galleryImages->map(function ($image) use ($property) {
            return [
                'url' => $image->getUrl(),
                'alt' => $image->alt_text ?: $property->title,
            ];
        })->values();

        if ($galleryItems->isEmpty()) {
            $galleryItems = collect([[
                'url' => asset('images/property-placeholder.jpg'),
                'alt' => $property->title,
            ]]);
        }

        $heroImageUrl = $galleryItems->first()['url'];
        $heroImageAlt = $galleryItems->first()['alt'];
        $backTab = request('tab', $property->status === 'pending' ? 'requests' : 'current');
        $statusLabel = ucfirst((string) $property->status);
        $verificationLabel = $property->is_verified ? 'Verified' : 'Unverified';
        $verificationButtonLabel = $property->is_verified ? 'Unverify Property' : 'Verify Property';
        $verificationButtonClass = $property->is_verified ? 'admin-btn-danger' : 'admin-btn-success';
        $rentLabel = $property->canRentRooms()
            ? $property->getOwnerPriceLabel()
            : 'Rs ' . number_format((float) $property->rent_price) . ' / month';
        $availableRoomCount = $property->rooms->where('availability', true)->count();
    @endphp

    <div class="admin-dashboard admin-properties-page admin-property-detail-page">
        <div class="admin-property-detail-header">
            <a href="{{ route('admin.properties.index', ['tab' => $backTab]) }}" class="admin-property-back-link">
                Back to properties
            </a>
            <span class="admin-property-detail-note">Admin-only property review and moderation view.</span>
        </div>

        <section class="content-card admin-property-hero-card">
            <div class="admin-property-hero-grid">
                <div class="admin-property-gallery js-admin-property-gallery" data-images='@json($galleryItems)'>
                    <div class="admin-property-gallery-main">
                        <div
                            class="admin-property-gallery-backdrop js-admin-property-gallery-backdrop"
                            style="background-image: url('{{ $heroImageUrl }}')"
                        ></div>
                        <div class="admin-property-gallery-overlay"></div>
                        <div class="admin-property-gallery-frame">
                            <img
                                src="{{ $heroImageUrl }}"
                                alt="{{ $heroImageAlt }}"
                                class="admin-property-gallery-image js-admin-property-main-image"
                                loading="eager"
                                decoding="async"
                            >
                        </div>

                        @if ($galleryItems->count() > 1)
                            <button type="button" class="admin-property-gallery-nav prev js-admin-property-gallery-prev" aria-label="Previous image">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button type="button" class="admin-property-gallery-nav next js-admin-property-gallery-next" aria-label="Next image">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div class="admin-property-gallery-counter js-admin-property-gallery-counter">1/{{ $galleryItems->count() }}</div>
                        @endif
                    </div>

                    @if ($galleryItems->count() > 1)
                        <div class="admin-property-gallery-thumbs">
                            @foreach ($galleryItems as $index => $galleryItem)
                                <button
                                    type="button"
                                    class="admin-property-gallery-thumb {{ $index === 0 ? 'active' : '' }} js-admin-property-gallery-thumb"
                                    data-index="{{ $index }}"
                                >
                                    <img src="{{ $galleryItem['url'] }}" alt="{{ $galleryItem['alt'] }}" loading="lazy" decoding="async">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <aside class="content-card admin-property-summary-card">
                    <div class="admin-property-hero-copy">
                        <p class="admin-section-label">Property overview</p>
                        <h2>{{ $property->title }}</h2>
                        <p class="admin-property-hero-owner">
                            Owner: {{ $property->owner->name ?? 'N/A' }}
                            <span class="admin-property-owner-separator">|</span>
                            Trust Points: {{ (int) data_get($property->owner, 'trust_points', 0) }}
                        </p>

                        <div class="admin-property-hero-badges">
                            <span class="status-pill status-neutral">{{ $statusLabel }}</span>
                            <span class="status-pill {{ $property->is_verified ? 'status-approved' : 'status-neutral' }}">{{ $verificationLabel }}</span>
                            <span class="status-pill status-neutral">{{ $property->getPropertyTypeLabel() }}</span>
                            <span class="status-pill status-neutral">{{ $property->getRentalModeLabel() }}</span>
                        </div>

                        <div class="admin-property-hero-stats">
                            <article class="admin-property-mini-stat">
                                <span>Rent</span>
                                <strong>{{ $rentLabel }}</strong>
                            </article>

                            <article class="admin-property-mini-stat">
                                <span>Rooms</span>
                                <strong>{{ $roomCount }} total / {{ $availableRoomCount }} available</strong>
                            </article>

                            <article class="admin-property-mini-stat">
                                <span>Location</span>
                                <strong>{{ $property->city ?: 'N/A' }}</strong>
                            </article>

                            <article class="admin-property-mini-stat">
                                <span>Updated</span>
                                <strong>{{ optional($property->updated_at)->format('M d, Y') ?? 'N/A' }}</strong>
                            </article>
                        </div>
                    </div>

                    <div class="admin-property-action-stack">
                        <a href="{{ route('admin.properties.edit', $property) }}" class="admin-btn admin-btn-primary">Edit Property</a>

                        <form method="POST" action="{{ route('admin.properties.verify', $property) }}">
                            @csrf
                            <button type="submit" class="admin-btn {{ $verificationButtonClass }}">{{ $verificationButtonLabel }}</button>
                        </form>

                        <form method="POST" action="{{ route('admin.properties.destroy', $property) }}" onsubmit="return confirm('Remove this property? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn admin-btn-danger admin-btn-danger-soft">Remove Property</button>
                        </form>
                    </div>
                </aside>
            </div>
        </section>

        <div class="admin-property-detail-main">
            <section class="content-card">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Property Details</h2>
                        <p>Core listing information shown in the admin panel.</p>
                    </div>
                </div>

                <div class="admin-detail-list">
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Title</span>
                        <span class="admin-detail-value">{{ $property->title }}</span>
                    </div>
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Owner</span>
                        <span class="admin-detail-value">{{ $property->owner->name ?? 'N/A' }}</span>
                    </div>
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Status</span>
                        <span class="admin-detail-value">{{ $statusLabel }}</span>
                    </div>
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Verification</span>
                        <span class="admin-detail-value">{{ $verificationLabel }}</span>
                    </div>
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Property Type</span>
                        <span class="admin-detail-value">{{ $property->getPropertyTypeLabel() }}</span>
                    </div>
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Rental Mode</span>
                        <span class="admin-detail-value">{{ $property->getRentalModeLabel() }}</span>
                    </div>
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Rent</span>
                        <span class="admin-detail-value">{{ $rentLabel }}</span>
                    </div>
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">City</span>
                        <span class="admin-detail-value">{{ $property->city ?: 'N/A' }}</span>
                    </div>
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Address</span>
                        <span class="admin-detail-value">{{ $property->address ?: 'N/A' }}</span>
                    </div>
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Location</span>
                        <span class="admin-detail-value">{{ $property->location ?: 'N/A' }}</span>
                    </div>
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Landmark</span>
                        <span class="admin-detail-value">{{ $property->landmark ?: 'N/A' }}</span>
                    </div>
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Total Rooms</span>
                        <span class="admin-detail-value">{{ $property->total_rooms ?: 'N/A' }}</span>
                    </div>
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Furnished</span>
                        <span class="admin-detail-value">{{ $property->furnished ? 'Yes' : 'No' }}</span>
                    </div>
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Gender Preference</span>
                        <span class="admin-detail-value">{{ $property->gender_preference ? ucfirst($property->gender_preference) : 'N/A' }}</span>
                    </div>
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Created</span>
                        <span class="admin-detail-value">{{ optional($property->created_at)->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                    <div class="admin-detail-row">
                        <span class="admin-detail-label">Updated</span>
                        <span class="admin-detail-value">{{ optional($property->updated_at)->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                </div>
            </section>

            @if ($property->description)
                <section class="content-card">
                    <div class="content-card-header admin-panel-header">
                        <div>
                            <h2>About This Property</h2>
                            <p>Free-form listing copy and notes.</p>
                        </div>
                    </div>

                    <div class="admin-property-section-body">
                        <p class="admin-property-section-copy">{{ $property->description }}</p>
                    </div>
                </section>
            @endif

            @if ($property->canRentRooms())
                <section class="content-card">
                    <div class="content-card-header admin-panel-header">
                        <div>
                            <h2>Room Details</h2>
                            <p>Room-level information without renter-facing actions.</p>
                        </div>

                        <p class="admin-property-room-summary">
                            {{ $roomCount }} total
                            @if ($property->rooms->isNotEmpty())
                                <span class="admin-property-room-summary-divider">·</span>
                                {{ $availableRoomCount }} available
                            @endif
                        </p>
                    </div>

                    @if ($property->rooms->isNotEmpty())
                        <div class="admin-property-room-grid">
                            @foreach ($property->rooms as $room)
                                @php
                                    $roomAvailabilityLabel = $room->availability ? 'Available' : 'Unavailable';
                                    $roomAvailabilityClass = $room->availability ? 'status-approved' : 'status-neutral';
                                    $roomImageUrl = $room->images->first()?->getUrl() ?? $room->getFirstImageUrl();
                                @endphp

                                <article class="admin-property-room-card">
                                    <div class="admin-property-room-media">
                                        <img src="{{ $roomImageUrl }}" alt="{{ $room->room_name }}" loading="lazy" decoding="async">
                                    </div>

                                    <div class="admin-property-room-body">
                                        <div class="admin-property-review-top">
                                            <div class="admin-property-review-copy">
                                                <h3 class="admin-property-room-title">{{ $room->room_name }}</h3>
                                                <p>{{ $room->room_number ? 'Room #' . $room->room_number : 'Room details' }}</p>
                                            </div>

                                            <span class="status-pill {{ $roomAvailabilityClass }}">{{ $roomAvailabilityLabel }}</span>
                                        </div>

                                        <p class="admin-property-room-copy">{{ $room->room_features ?: 'Private room option inside this property.' }}</p>

                                        <div class="admin-property-room-meta">
                                            <div class="admin-property-room-meta-item">
                                                <span>Price</span>
                                                <strong>Rs {{ number_format((float) $room->price) }} / month</strong>
                                            </div>
                                            <div class="admin-property-room-meta-item">
                                                <span>Capacity</span>
                                                <strong>{{ $room->capacity }} {{ $room->capacity === 1 ? 'person' : 'people' }}</strong>
                                            </div>
                                            <div class="admin-property-room-meta-item">
                                                <span>Occupancy</span>
                                                <strong>{{ $room->current_occupancy }} occupied</strong>
                                            </div>
                                            <div class="admin-property-room-meta-item">
                                                <span>Status</span>
                                                <strong>{{ $roomAvailabilityLabel }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="admin-review-empty">
                            <p class="admin-review-empty-title">No rooms have been added yet.</p>
                            <p class="admin-review-empty-copy">Room-based listings will show here once the property has room entries.</p>
                        </div>
                    @endif
                </section>
            @endif

            @if(!is_null($property->latitude) && !is_null($property->longitude))
                <section class="content-card">
                    <div class="content-card-header admin-panel-header">
                        <div>
                            <h2>Location Map</h2>
                            <p>This is the saved location for the listed property.</p>
                        </div>
                    </div>

                    <div class="admin-property-section-body">
                        @include('components.leaflet-property-map', [
                            'mapId' => 'admin-property-map',
                            'mode' => 'readonly',
                            'initialLatitude' => $property->latitude,
                            'initialLongitude' => $property->longitude,
                            'defaultLatitude' => 27.7172,
                            'defaultLongitude' => 85.3240,
                            'defaultZoom' => 15,
                            'height' => '340px',
                            'title' => null,
                            'helpText' => null,
                        ])
                    </div>
                </section>
            @endif

            @if ($property->amenities && $property->amenities->count())
                <section class="content-card">
                    <div class="content-card-header admin-panel-header">
                        <div>
                            <h2>Amenities</h2>
                            <p>Facilities attached to this listing.</p>
                        </div>
                    </div>

                    <div class="admin-property-chip-grid">
                        @foreach ($property->amenities as $amenity)
                            <span class="admin-property-chip">{{ $amenity->name ?? $amenity }}</span>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($property->rules)
                <section class="content-card">
                    <div class="content-card-header admin-panel-header">
                        <div>
                            <h2>House Rules</h2>
                            <p>Rules and restrictions attached to the listing.</p>
                        </div>
                    </div>

                    <div class="admin-property-section-body">
                        <p class="admin-property-section-copy">{{ $property->rules }}</p>
                    </div>
                </section>
            @endif

            <section class="content-card">
                <div class="content-card-header admin-panel-header">
                    <div>
                        <h2>Reviews</h2>
                        <p>
                            @if($reviewCount > 0)
                                {{ $reviewCount }} approved property review{{ $reviewCount === 1 ? '' : 's' }} visible to admins.
                            @else
                                No reviews have been added yet.
                            @endif
                        </p>
                    </div>

                    @if($reviewCount > 0)
                        <div class="admin-review-summary">
                            <span class="admin-review-summary-score">{{ number_format((float) $avgRating, 1) }}</span>
                            <span class="admin-review-summary-label">Average rating</span>
                        </div>
                    @endif
                </div>

                @if($reviewCount > 0)
                    <div class="admin-property-review-list">
                        @foreach ($reviews as $review)
                            <article class="admin-property-review-card">
                                <div class="admin-property-review-top">
                                    <div class="admin-property-review-copy">
                                        <h3>{{ $review->user->name ?? 'Anonymous' }}</h3>
                                        <p>{{ optional($review->created_at)->format('M d, Y') ?? 'N/A' }}</p>
                                    </div>

                                    <span class="admin-property-review-rating">{{ number_format((float) $review->rating, 1) }}/5</span>
                                </div>

                                <p class="admin-property-review-text">{{ $review->review_text }}</p>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="admin-review-empty">
                        <p class="admin-review-empty-title">No reviews have been added yet.</p>
                        <p class="admin-review-empty-copy">Once verified tenants review this property, their feedback will appear here.</p>
                    </div>
                @endif
            </section>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const gallery = document.querySelector('.js-admin-property-gallery');
                if (!gallery) {
                    return;
                }

                const images = JSON.parse(gallery.dataset.images || '[]');
                if (!images.length) {
                    return;
                }

                const mainImage = gallery.querySelector('.js-admin-property-main-image');
                const backdrop = gallery.querySelector('.js-admin-property-gallery-backdrop');
                const counter = gallery.querySelector('.js-admin-property-gallery-counter');
                const prevButton = gallery.querySelector('.js-admin-property-gallery-prev');
                const nextButton = gallery.querySelector('.js-admin-property-gallery-next');
                const thumbs = Array.from(gallery.querySelectorAll('.js-admin-property-gallery-thumb'));
                let index = 0;

                function updateFit() {
                    if (!mainImage.naturalWidth || !mainImage.naturalHeight) {
                        mainImage.classList.remove('portrait');
                        mainImage.classList.add('landscape');
                        return;
                    }

                    if (mainImage.naturalHeight > mainImage.naturalWidth) {
                        mainImage.classList.add('portrait');
                        mainImage.classList.remove('landscape');
                    } else {
                        mainImage.classList.add('landscape');
                        mainImage.classList.remove('portrait');
                    }
                }

                function render() {
                    const item = images[index];
                    if (!item) {
                        return;
                    }

                    mainImage.src = item.url;
                    mainImage.alt = item.alt || mainImage.alt || '';

                    if (backdrop) {
                        backdrop.style.backgroundImage = `url('${item.url}')`;
                    }

                    if (counter) {
                        counter.textContent = `${index + 1}/${images.length}`;
                    }

                    thumbs.forEach(function (thumb, thumbIndex) {
                        thumb.classList.toggle('active', thumbIndex === index);
                    });

                    updateFit();
                }

                mainImage.addEventListener('load', updateFit);

                if (prevButton) {
                    prevButton.addEventListener('click', function () {
                        index = (index - 1 + images.length) % images.length;
                        render();
                    });
                }

                if (nextButton) {
                    nextButton.addEventListener('click', function () {
                        index = (index + 1) % images.length;
                        render();
                    });
                }

                thumbs.forEach(function (thumb, thumbIndex) {
                    thumb.addEventListener('click', function () {
                        index = thumbIndex;
                        render();
                    });
                });

                render();
            });
        </script>
    </div>
@endsection
