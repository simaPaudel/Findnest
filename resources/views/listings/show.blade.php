<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $property->title }} - FindNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #0f172a;
            background: #ffffff;
        }

        .surface {
            border: 1px solid #e2e8f0;
            border-radius: 22px;
            background: #ffffff;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.05);
        }

        .muted-surface {
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #f8fafc;
        }

        .tag {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.5rem 0.9rem;
            font-size: 0.78rem;
            font-weight: 600;
            line-height: 1;
        }

        .btn-primary {
            display: inline-flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            padding: 0.95rem 1.2rem;
            background: #ff385c;
            color: #ffffff;
            font-weight: 700;
            text-decoration: none;
            transition: background 0.2s ease;
        }

        .btn-primary:hover {
            background: #e11d48;
        }

        .btn-secondary {
            display: inline-flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            padding: 0.95rem 1.2rem;
            border: 1px solid #cbd5e1;
            color: #0f172a;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .gallery-shell {
            overflow: hidden;
        }

        .gallery-stage {
            position: relative;
            aspect-ratio: 16 / 10;
            background: #f8fafc;
            overflow: hidden;
        }

        .gallery-backdrop {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            filter: blur(28px);
            transform: scale(1.1);
            opacity: 0.22;
        }

        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.18) 0%, rgba(248,250,252,0.55) 100%);
        }

        .gallery-main {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            padding: 1rem;
        }

        .gallery-main-image {
            display: block;
            border-radius: 18px;
            box-shadow: 0 18px 36px rgba(15, 23, 42, 0.14);
            background: #ffffff;
            image-rendering: auto;
        }

        .gallery-main-image.landscape {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .gallery-main-image.portrait {
            height: 100%;
            width: auto;
            max-width: min(100%, 72%);
            object-fit: contain;
            object-position: center;
        }

        .gallery-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 42px;
            height: 42px;
            border: none;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.94);
            color: #0f172a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.16);
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .gallery-nav:hover {
            background: #ffffff;
            transform: translateY(-50%) scale(1.04);
        }

        .gallery-nav.prev {
            left: 16px;
        }

        .gallery-nav.next {
            right: 16px;
        }

        .gallery-counter {
            position: absolute;
            right: 16px;
            bottom: 16px;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.72);
            color: #ffffff;
            padding: 0.45rem 0.8rem;
            font-size: 0.78rem;
            font-weight: 600;
            line-height: 1;
        }

        .gallery-thumbs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
            gap: 0.85rem;
        }

        .gallery-thumb {
            border: 2px solid transparent;
            border-radius: 16px;
            overflow: hidden;
            background: #f8fafc;
            cursor: pointer;
            transition: border-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .gallery-thumb:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 18px rgba(15, 23, 42, 0.08);
        }

        .gallery-thumb.active {
            border-color: #ff385c;
            box-shadow: 0 12px 20px rgba(255, 56, 92, 0.12);
        }

        .gallery-thumb img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            aspect-ratio: 4 / 3;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    @php
        $images = $property->orderedImages ?? collect();
        $galleryItems = $images->map(function ($image) use ($property) {
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
        $listedRooms = $property->rooms;
        $availableRooms = $listedRooms->filter(fn ($room) => (bool) $room->is_bookable);
        $priceSourceRooms = $availableRooms->isNotEmpty() ? $availableRooms : $listedRooms;
        $minRoomPrice = $priceSourceRooms->min('price');
        $maxRoomPrice = $priceSourceRooms->max('price');

        if ($property->rental_mode === 'per_room') {
            if ($minRoomPrice === null || $maxRoomPrice === null) {
                $headlinePrice = 'Price on request';
            } elseif ((float) $minRoomPrice === (float) $maxRoomPrice) {
                $headlinePrice = 'From Rs ' . number_format((float) $minRoomPrice) . ' / month';
            } else {
                $headlinePrice = 'Rs ' . number_format((float) $minRoomPrice) . ' - Rs ' . number_format((float) $maxRoomPrice) . ' / month';
            }
        } else {
            $headlinePrice = 'Rs ' . number_format((float) $property->rent_price) . ' / month';
        }

        $addressLine = $property->address ?: ($property->location ?: $property->city);
        $propertyAvailabilityClasses = $property->is_property_bookable
            ? 'bg-emerald-50 text-emerald-700'
            : 'bg-amber-50 text-amber-700';
    @endphp

    <main class="py-10 lg:py-12">
        <div class="mx-auto max-w-7xl px-4 lg:px-6">
            <div class="space-y-8">
                <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-slate-500 transition hover:text-slate-900">
                    Back to Home
                </a>

                @if(session('success'))
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm font-medium text-amber-700">
                        {{ session('error') }}
                    </div>
                @endif

                <section class="grid grid-cols-1 gap-8 xl:grid-cols-[1.65fr_0.9fr]">
                    <div class="space-y-6">
                        <div class="flex flex-wrap gap-2">
                            <span class="tag bg-slate-900 text-white">{{ $property->rental_mode === 'per_room' ? 'Individual Rooms' : 'Full Property' }}</span>
                            <span class="tag bg-slate-100 text-slate-700">{{ $property->getPropertyTypeLabel() }}</span>
                            <span class="tag {{ $propertyAvailabilityClasses }}">{{ $property->property_availability_label }}</span>
                            @if($property->is_verified)
                                <span class="tag bg-emerald-50 text-emerald-700">Verified</span>
                            @endif
                            @if($property->furnished)
                                <span class="tag bg-slate-100 text-slate-700">Furnished</span>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <h1 class="max-w-4xl text-2xl font-semibold leading-tight tracking-tight text-slate-950 lg:text-[2rem]">
                                {{ $property->title }}
                            </h1>
                            <p class="text-base text-slate-500">
                                {{ $addressLine }}@if($property->city), {{ $property->city }}@endif
                            </p>
                        </div>

                        <section class="space-y-4 js-property-gallery" data-images='@json($galleryItems->values())'>
                            <div class="surface gallery-shell">
                                <div class="gallery-stage">
                                    <div class="gallery-backdrop js-gallery-backdrop" style="background-image: url('{{ $galleryItems->first()['url'] }}')"></div>
                                    <div class="gallery-overlay"></div>
                                    <div class="gallery-main">
                                        <img src="{{ $galleryItems->first()['url'] }}" alt="{{ $galleryItems->first()['alt'] }}" class="gallery-main-image landscape js-gallery-main-image" loading="eager" decoding="async">
                                    </div>

                                    @if($galleryItems->count() > 1)
                                        <button type="button" class="gallery-nav prev js-gallery-prev" aria-label="Previous image">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                        </button>
                                        <button type="button" class="gallery-nav next js-gallery-next" aria-label="Next image">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>
                                        <div class="gallery-counter js-gallery-counter">1/{{ $galleryItems->count() }}</div>
                                    @endif
                                </div>
                            </div>

                            @if($galleryItems->count() > 1)
                                <div class="gallery-thumbs">
                                    @foreach($galleryItems as $galleryIndex => $galleryItem)
                                        <button type="button" class="gallery-thumb {{ $galleryIndex === 0 ? 'active' : '' }} js-gallery-thumb" data-index="{{ $galleryIndex }}">
                                            <img src="{{ $galleryItem['url'] }}" alt="{{ $galleryItem['alt'] }}" loading="lazy" decoding="async">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </section>
                    </div>

                    <aside class="space-y-4 xl:pt-24">
                        <div class="surface p-6 xl:sticky xl:top-24">
                            <div class="border-b border-slate-200 pb-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Booking Summary</p>
                                <p class="mt-3 text-2xl font-semibold leading-tight text-slate-950">{{ $headlinePrice }}</p>
                                <p class="mt-2 text-sm leading-6 text-slate-500">
                                    @if($property->canRentRooms())
                                        Pick a room from the section below and continue to booking.
                                    @else
                                        Book the complete property as one monthly rental.
                                    @endif
                                </p>
                            </div>

                            <div class="mt-5 flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-base font-bold text-rose-500">
                                    {{ strtoupper(substr($property->owner->name ?? 'O', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $property->owner->name ?? 'Owner' }}</p>
                                    <p class="text-sm text-slate-500">Property owner</p>
                                </div>
                            </div>

                            <div class="mt-6 space-y-3">
                                @if($property->canRentRooms())
                                    <a href="#available-rooms" class="btn-primary">Choose a Room</a>
                                @elseif(auth()->check())
                                    <a href="{{ route('listings.request-booking', $property->id) }}" class="btn-primary">Request Booking</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn-primary">Sign In to Book</a>
                                @endif
                                @auth
                                    <form action="{{ route('user.saved-listings.save', $property) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-secondary">Save Listing</button>
                                    </form>
                                    @if(auth()->user()->isUser() && auth()->id() !== (int) $property->owner_id)
                                        <button type="button" class="btn-secondary js-contact-owner" data-property-id="{{ $property->id }}">Contact Owner</button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn-secondary">Sign In to Save</a>
                                    <a href="{{ route('login') }}" class="btn-secondary">Sign In to Message</a>
                                @endauth
                            </div>

                            @if($property->owner->email)
                                <div class="mt-6 border-t border-slate-200 pt-5">
                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Contact Email</p>
                                    <p class="mt-2 break-all text-sm font-medium text-slate-700">{{ $property->owner->email }}</p>
                                </div>
                            @endif
                        </div>
                    </aside>
                </section>

                <section class="grid grid-cols-1 gap-8 xl:grid-cols-[1.65fr_0.9fr]">
                    <div class="space-y-8">
                        @if($property->canRentRooms())
                            <section id="available-rooms" class="surface p-7">
                                <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                                    <div>
                                        <h2 class="text-2xl font-bold text-slate-950">Room Details</h2>
                                        <p class="mt-2 text-sm text-slate-500">Choose the room that fits your budget and move-in plan.</p>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-700">
                                        {{ $listedRooms->count() }} {{ $listedRooms->count() === 1 ? 'room listed' : 'rooms listed' }}
                                        @if($listedRooms->isNotEmpty())
                                            <span class="text-slate-400">·</span>
                                            {{ $availableRooms->count() }} {{ $availableRooms->count() === 1 ? 'available' : 'available' }}
                                        @endif
                                    </p>
                                </div>

                                @if($listedRooms->isNotEmpty())
                                    <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                                        @foreach($listedRooms as $room)
                                            @php
                                                $roomImageUrl = $room->getFirstImageUrl();
                                                $roomAvailabilityLabel = $room->is_bookable ? 'Available' : 'Unavailable';
                                                $roomAvailabilityClasses = $room->is_bookable
                                                    ? 'bg-emerald-50 text-emerald-700'
                                                    : 'bg-amber-50 text-amber-700';
                                            @endphp
                                            <div class="overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-sm">
                                                <div class="aspect-[16/10] bg-slate-100">
                                                    <img src="{{ $roomImageUrl }}" alt="{{ $room->room_name }}" class="h-full w-full object-cover">
                                                </div>
                                                <div class="p-5">
                                                    <div class="flex items-start justify-between gap-3">
                                                        <div>
                                                            <h3 class="text-xl font-bold text-slate-950">{{ $room->room_name }}</h3>
                                                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                                                {{ $room->room_features ?: 'Private room option inside this property.' }}
                                                            </p>
                                                        </div>
                                                        <span class="tag {{ $roomAvailabilityClasses }}">{{ $roomAvailabilityLabel }}</span>
                                                    </div>

                                                    <div class="mt-5 grid grid-cols-2 gap-3">
                                                        <div class="muted-surface p-4">
                                                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Price</p>
                                                            <p class="mt-2 text-sm font-bold text-slate-900">Rs {{ number_format((float) $room->price) }} / month</p>
                                                        </div>
                                                        <div class="muted-surface p-4">
                                                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Capacity</p>
                                                            <p class="mt-2 text-sm font-bold text-slate-900">{{ $room->capacity }} {{ $room->capacity === 1 ? 'person' : 'people' }}</p>
                                                        </div>
                                                        <div class="muted-surface p-4">
                                                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Booking Status</p>
                                                            <p class="mt-2 text-sm font-bold text-slate-900">{{ $roomAvailabilityLabel }}</p>
                                                        </div>
                                                        <div class="muted-surface p-4">
                                                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Location</p>
                                                            <p class="mt-2 text-sm font-bold text-slate-900">{{ $property->city ?: 'Listed location' }}</p>
                                                        </div>
                                                    </div>

                                                    <div class="mt-5">
                                                        @if($room->is_bookable)
                                                            @auth
                                                                <a href="{{ route('listings.request-booking', ['property' => $property->id, 'room' => $room->id]) }}" class="btn-primary">Book This Room</a>
                                                            @else
                                                                <a href="{{ route('login') }}" class="btn-primary">Sign In to Book</a>
                                                            @endauth
                                                        @else
                                                            <span class="inline-flex w-full items-center justify-center rounded-[14px] border border-slate-200 bg-slate-100 px-4 py-[0.95rem] text-sm font-semibold text-slate-400">
                                                                Unavailable
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="mt-6 muted-surface p-6 text-center text-sm text-slate-500">
                                        No rooms are currently available for booking in this property.
                                    </div>
                                @endif
                            </section>
                        @endif

                        @if($property->description)
                            <section class="surface p-7">
                                <h2 class="text-2xl font-bold text-slate-950">About This Property</h2>
                                <p class="mt-4 whitespace-pre-line text-base leading-8 text-slate-600">{{ $property->description }}</p>
                            </section>
                        @endif

                        <section class="surface p-7">
                            <h2 class="text-2xl font-bold text-slate-950">Property Details</h2>
                            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="muted-surface p-5">
                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Address</p>
                                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $property->address ?: 'Not specified' }}</p>
                                </div>
                                <div class="muted-surface p-5">
                                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">City</p>
                                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $property->city ?: ($property->location ?: 'Not specified') }}</p>
                                </div>
                                @if($property->landmark)
                                    <div class="muted-surface p-5">
                                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Nearby Landmark</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ $property->landmark }}</p>
                                    </div>
                                @endif
                                @if($property->total_rooms)
                                    <div class="muted-surface p-5">
                                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Total Rooms</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900">{{ $property->total_rooms }}</p>
                                    </div>
                                @endif
                            </div>
                        </section>

                        @if(!is_null($property->latitude) && !is_null($property->longitude))
                            <section class="surface p-7">
                                @include('components.leaflet-property-map', [
                                    'mapId' => 'property-show-map',
                                    'mode' => 'readonly',
                                    'initialLatitude' => $property->latitude,
                                    'initialLongitude' => $property->longitude,
                                    'defaultLatitude' => 27.7172,
                                    'defaultLongitude' => 85.3240,
                                    'defaultZoom' => 15,
                                    'height' => '340px',
                                    'title' => 'Location Map',
                                    'helpText' => 'This is the saved location for the listed property.',
                                ])
                            </section>
                        @endif

                        @if($property->amenities && $property->amenities->count() > 0)
                            <section class="surface p-7">
                                <h2 class="text-2xl font-bold text-slate-950">Amenities</h2>
                                <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    @foreach($property->amenities as $amenity)
                                        <div class="muted-surface px-4 py-4 text-sm font-medium text-slate-700">
                                            {{ $amenity->name ?? $amenity }}
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endif

                        @if($property->rules)
                            <section class="surface p-7">
                                <h2 class="text-2xl font-bold text-slate-950">House Rules</h2>
                                <p class="mt-4 whitespace-pre-line text-base leading-8 text-slate-600">{{ $property->rules }}</p>
                            </section>
                        @endif

                        <section class="surface p-7">
                            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold text-slate-950">Reviews</h2>
                                    <p class="mt-2 text-sm text-slate-500">
                                        @if($reviewCount > 0)
                                            {{ $reviewCount }} {{ $reviewCount === 1 ? 'review' : 'reviews' }} from previous tenants
                                        @else
                                            No reviews yet for this property
                                        @endif
                                    </p>
                                </div>
                                @if($reviewCount > 0)
                                    <div class="text-left md:text-right">
                                        <p class="text-3xl font-bold text-slate-950">{{ number_format($avgRating, 1) }}</p>
                                        <p class="text-sm text-slate-500">Average rating</p>
                                    </div>
                                @endif
                            </div>

                            @if($reviewCount > 0)
                                <div class="mt-6 space-y-5">
                                    @foreach($reviews as $review)
                                        <div class="border-b border-slate-200 pb-5 last:border-b-0 last:pb-0">
                                            <div class="flex items-start justify-between gap-4">
                                                <div>
                                                    <p class="font-semibold text-slate-900">{{ $review->user->name ?? 'Anonymous' }}</p>
                                                    <p class="mt-1 text-sm text-slate-500">{{ $review->created_at->format('M d, Y') }}</p>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                            <p class="mt-3 text-sm leading-7 text-slate-600">{{ $review->review_text }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </section>
                    </div>

                    <div class="space-y-6">
                        @auth
                            @php
                                $pref = \App\Models\RoommatePreference::where('user_id', auth()->id())->first();
                            @endphp

                            @if($pref)
                                <section class="surface p-6">
                                    <h2 class="text-xl font-bold text-slate-950">Roommate Match Snapshot</h2>
                                    <p class="mt-2 text-sm leading-6 text-slate-500">Use your saved preferences to explore shared-living matches for this area.</p>

                                    <div class="mt-5 space-y-3">
                                        @if($pref->preferred_location)
                                            <div class="muted-surface p-4">
                                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Preferred Location</p>
                                                <p class="mt-2 text-sm font-semibold text-slate-900">{{ $pref->preferred_location }}</p>
                                            </div>
                                        @endif
                                        @if($pref->budget_range)
                                            <div class="muted-surface p-4">
                                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Budget Range</p>
                                                <p class="mt-2 text-sm font-semibold text-slate-900">{{ $pref->budget_range }}</p>
                                            </div>
                                        @endif
                                        @if($pref->gender_preference)
                                            <div class="muted-surface p-4">
                                                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">Gender Preference</p>
                                                <p class="mt-2 text-sm font-semibold capitalize text-slate-900">{{ $pref->gender_preference }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-5">
                                        <a href="{{ route('roommates.matches') }}" class="btn-secondary">See Roommate Matches</a>
                                    </div>
                                </section>
                            @endif
                        @endauth
                    </div>
                </section>

                @if($similar->count() > 0)
                    <section class="space-y-6 border-t border-slate-200 pt-10">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-950">Similar Properties Nearby</h2>
                            <p class="mt-2 text-sm text-slate-500">A few comparable listings you may also want to explore.</p>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
                            @foreach($similar as $p)
                                @php
                                    $similarImage = $p->images->firstWhere('is_primary', true) ?? $p->images->sortBy('order')->first();
                                    $similarImageUrl = $similarImage ? $similarImage->getUrl() : asset('images/property-placeholder.jpg');
                                    $similarMin = $p->min_room_price !== null ? (float) $p->min_room_price : null;
                                    $similarMax = $p->max_room_price !== null ? (float) $p->max_room_price : null;

                                    if ($p->rental_mode === 'per_room') {
                                        if ($similarMin === null || $similarMax === null) {
                                            $similarPrice = 'Price on request';
                                        } elseif ($similarMin === $similarMax) {
                                            $similarPrice = 'From Rs ' . number_format($similarMin) . ' / month';
                                        } else {
                                            $similarPrice = 'Rs ' . number_format($similarMin) . ' - Rs ' . number_format($similarMax) . ' / month';
                                        }
                                    } else {
                                        $similarPrice = 'Rs ' . number_format((float) $p->rent_price) . ' / month';
                                    }
                                @endphp

                                <a href="{{ route('listings.show', $p->id) }}" class="block overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                                    <div class="aspect-[4/3] bg-slate-100">
                                        <img src="{{ $similarImageUrl }}" alt="{{ $p->title }}" class="h-full w-full object-cover">
                                    </div>
                                    <div class="p-4">
                                        <div class="flex flex-wrap gap-2">
                                            <span class="tag bg-slate-100 text-slate-700">{{ $p->rental_mode === 'per_room' ? 'Per Room' : 'Full Property' }}</span>
                                        </div>
                                        <h3 class="mt-3 text-base font-bold leading-6 text-slate-950">{{ $p->title }}</h3>
                                        <p class="mt-2 text-sm text-slate-500">{{ $p->city ?: ($p->location ?: 'Location not specified') }}</p>
                                        <p class="mt-4 text-sm font-semibold text-slate-900">{{ $similarPrice }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
        </div>
    </main>

    <footer class="mt-10 border-t border-slate-200 py-8">
        <div class="mx-auto max-w-7xl px-4 text-center text-sm text-slate-500 lg:px-6">
            &copy; 2026 FindNest. All rights reserved.
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.js-property-gallery').forEach(function (gallery) {
                const images = JSON.parse(gallery.dataset.images || '[]');
                if (!images.length) return;

                const mainImage = gallery.querySelector('.js-gallery-main-image');
                const backdrop = gallery.querySelector('.js-gallery-backdrop');
                const counter = gallery.querySelector('.js-gallery-counter');
                const prevButton = gallery.querySelector('.js-gallery-prev');
                const nextButton = gallery.querySelector('.js-gallery-next');
                const thumbs = Array.from(gallery.querySelectorAll('.js-gallery-thumb'));
                let index = 0;

                function updateFit() {
                    if (!mainImage.naturalWidth || !mainImage.naturalHeight) {
                        mainImage.classList.remove('portrait');
                        mainImage.classList.add('landscape');
                        return;
                    }

                    const portrait = mainImage.naturalHeight > mainImage.naturalWidth;
                    mainImage.classList.toggle('portrait', portrait);
                    mainImage.classList.toggle('landscape', !portrait);
                }

                function render() {
                    const current = images[index];
                    if (!current) return;

                    mainImage.src = current.url;
                    mainImage.alt = current.alt || '';
                    if (backdrop) {
                        backdrop.style.backgroundImage = `url('${current.url}')`;
                    }
                    if (counter) {
                        counter.textContent = `${index + 1}/${images.length}`;
                    }

                    thumbs.forEach(function (thumb, thumbIndex) {
                        thumb.classList.toggle('active', thumbIndex === index);
                    });
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

                thumbs.forEach(function (thumb) {
                    thumb.addEventListener('click', function () {
                        index = Number(thumb.dataset.index || 0);
                        render();
                    });
                });

                render();
            });
            const contactOwnerButton = document.querySelector('.js-contact-owner');
            if (contactOwnerButton) {
                contactOwnerButton.addEventListener('click', async function () {
                    if (contactOwnerButton.disabled) {
                        return;
                    }
                    contactOwnerButton.disabled = true;
                    contactOwnerButton.textContent = 'Opening chat...';
                    try {
                        const response = await fetch('{{ route('user.conversations.property.create-or-open', ['propertyId' => $property->id]) }}', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await response.json();
                        if (!response.ok) {
                            throw new Error(data.message || 'Unable to open conversation.');
                        }
                        if (!data.conversation_id) {
                            throw new Error('Conversation could not be created.');
                        }
                        window.location.href = '{{ route('user.messages.index') }}?conversation=' + data.conversation_id;
                    } catch (error) {
                        alert(error.message || 'Unable to open conversation right now.');
                        contactOwnerButton.disabled = false;
                        contactOwnerButton.textContent = 'Contact Owner';
                    }
                });
            }
        });
    </script>
</body>
</html>
