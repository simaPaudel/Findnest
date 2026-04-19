<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindNest - Find Your Perfect Accommodation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* FindNest Theme - CSS Variables */
        :root {
            --fn-red: #FF385C;
            --fn-red-hover: #E11D48;
            --fn-white: #FFFFFF;
            --fn-gray: #F7F7F7;
            --fn-gray-light: #F3F4F6;
            --fn-charcoal: #1F2937;
            --fn-gray-dark: #6B7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--fn-white);
            color: var(--fn-charcoal);
            line-height: 1.6;
        }

        /* Utility Classes */
        .fn-bg-red { background-color: var(--fn-red); }
        .fn-bg-white { background-color: var(--fn-white); }
        .fn-bg-gray { background-color: var(--fn-gray); }
        .fn-bg-charcoal { background-color: var(--fn-charcoal); }
        .fn-text-red { color: var(--fn-red); }
        .fn-text-charcoal { color: var(--fn-charcoal); }
        .fn-text-white { color: var(--fn-white); }
        .fn-text-gray { color: var(--fn-gray-dark); }

        /* Buttons */
        .fn-btn-primary {
            background: var(--fn-red);
            color: var(--fn-white);
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-block;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(255, 56, 92, 0.2);
        }

        .fn-btn-primary:hover {
            background: var(--fn-red-hover);
            box-shadow: 0 4px 12px rgba(255, 56, 92, 0.3);
            transform: translateY(-2px);
        }

        .fn-btn-secondary {
            background: transparent;
            color: var(--fn-charcoal);
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            border: 1px solid var(--fn-gray-light);
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-block;
            text-decoration: none;
        }

        .fn-btn-secondary:hover {
            border-color: var(--fn-red);
            color: var(--fn-red);
            background: rgba(255, 56, 92, 0.03);
        }

        /* Search Bar */
        .fn-search-bar {
            background: var(--fn-white);
            border: 1px solid #E5E7EB;
            border-radius: 999px;
            padding: 10px;
            display: flex;
            gap: 10px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .fn-search-bar:focus-within {
            border-color: var(--fn-red);
            box-shadow: 0 4px 12px rgba(255, 56, 92, 0.1);
        }

        .fn-search-input {
            background: transparent;
            border: 1px solid transparent;
            border-radius: 999px;
            padding: 14px 18px;
            color: var(--fn-charcoal);
            outline: none;
            flex: 1;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .fn-search-input:focus {
            background: var(--fn-white);
            border-color: var(--fn-red);
        }

        .fn-search-input::placeholder {
            color: var(--fn-gray-dark);
        }

        .fn-search-select {
            background: transparent;
            border: 1px solid transparent;
            border-radius: 999px;
            padding: 14px 18px;
            color: var(--fn-charcoal);
            outline: none;
            flex: 1;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .fn-search-select:focus {
            background: var(--fn-white);
            border-color: var(--fn-red);
        }


        .search-shell {
            background: #fff;
            border: 1px solid #E5E7EB;
            border-radius: 999px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        }

        /* Cards */
        .fn-glass-card {
            background: var(--fn-white);
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .fn-glass-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: var(--fn-red);
        }

        /* Listing Card */
        .fn-listing-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            cursor: pointer;
            border: 1px solid #E5E7EB;
            background: #fff;
            transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        }

        .fn-listing-card img {
            transition: transform 0.3s ease;
        }

        .fn-listing-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.08);
        }

        .fn-listing-card:hover img {
            transform: scale(1.05);
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
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .save-btn:hover {
            background: rgba(255, 56, 92, 0.92);
            transform: scale(1.04);
        }

        .save-btn svg {
            width: 19px;
            height: 19px;
            stroke-width: 2;
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
            -webkit-line-clamp: 1;
        }

        /* Step Timeline */
        .fn-step-timeline {
            position: relative;
            width: 100%;
        }

        .fn-step-track {
            display: none;
        }

        .fn-step-card {
            position: relative;
            overflow: hidden;
            background: var(--fn-white);
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            padding: 3.5rem 1.5rem 1.5rem;
            min-height: 230px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .fn-step-card:hover {
            transform: translateY(-4px);
            border-color: var(--fn-red);
        }

        .fn-step-icon {
            position: absolute;
            left: 50%;
            top: 1.55rem;
            width: 56px;
            height: 56px;
            border-radius: 999px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: var(--fn-red);
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translateX(-50%);
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }

        .fn-step-card:hover .fn-step-icon {
            background: var(--fn-red);
            border-color: var(--fn-red);
            color: var(--fn-white);
        }

        .fn-step-title {
            position: relative;
            z-index: 1;
            margin: 0;
            margin-top: 0.75rem;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--fn-charcoal);
        }

        .fn-step-copy {
            position: relative;
            z-index: 1;
            margin-top: 0.65rem;
            color: var(--fn-gray-dark);
            font-size: 0.95rem;
            line-height: 1.65;
            max-width: 20rem;
        }

        @media (min-width: 1024px) {
            .fn-step-track {
                display: block;
                position: absolute;
                left: 0;
                right: 0;
                top: 3.25rem;
                height: 1px;
                background: #e2e8f0;
            }
        }

        @media (max-width: 768px) {
            .fn-search-bar {
                flex-direction: column;
            }

            .fn-step-card {
                min-height: 0;
                padding: 3.25rem 1.25rem 1.25rem;
            }

            .fn-step-icon {
                top: 1.35rem;
                width: 52px;
                height: 52px;
            }
        }

        /* Badge */
        .fn-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .fn-badge-red {
            background: rgba(255, 56, 92, 0.12);
            color: var(--fn-red);
            border: 1px solid rgba(255, 56, 92, 0.2);
        }

        .fn-badge-gray {
            background: rgba(107, 114, 128, 0.08);
            color: var(--fn-gray-dark);
            border: 1px solid rgba(107, 114, 128, 0.15);
        }


        .fn-hero-shell {
            background:
                radial-gradient(circle at top left, rgba(255, 56, 92, 0.05), transparent 35%),
                linear-gradient(180deg, #ffffff 0%, #fff8fa 100%);
            border-bottom: 1px solid #f1f5f9;
        }

        .fn-section-title {
            font-size: 2.25rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.03em;
            color: var(--fn-charcoal);
        }

        .fn-section-copy {
            color: var(--fn-gray-dark);
            font-size: 1rem;
        }

        /* Fade-in animation */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.7s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in:nth-child(1) { animation-delay: 0.08s; }
        .fade-in:nth-child(2) { animation-delay: 0.12s; }
        .fade-in:nth-child(3) { animation-delay: 0.16s; }

        /* Navbar */
        .fn-navbar {
            background: var(--fn-white);
            border-bottom: 1px solid #E5E7EB;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .fn-nav-link {
            color: var(--fn-charcoal);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .fn-nav-link:hover,
        .fn-nav-link.active {
            color: var(--fn-red);
            background: rgba(255, 56, 92, 0.05);
        }

        @media (max-width: 768px) {
            .fn-search-bar {
                flex-direction: column;
            }
        }
    </style>
</head>
<body class="fn-bg-white">
    <!-- Navbar -->
    @include('components.navbar')

    <!-- Search Section -->
    <section class="py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-4 lg:px-6">
            <form action="{{ route('home') }}" method="GET" class="max-w-5xl mx-auto">
                <div class="search-shell p-2 flex flex-col gap-2 md:flex-row md:items-center md:gap-0">
                    <div class="flex-1 px-4 py-3 md:border-r md:border-slate-200">
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Where</label>
                        <input type="text" name="q" placeholder="Search destinations" value="{{ request('q') }}" class="w-full bg-transparent outline-none text-sm text-slate-800">
                    </div>

                    <div class="flex-1 px-4 py-3 md:border-r md:border-slate-200">
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Max Price</label>
                        <input type="number" name="max_price" placeholder="Set budget" value="{{ request('max_price') }}" class="w-full bg-transparent outline-none text-sm text-slate-800">
                    </div>

                    <div class="flex-1 px-4 py-3 md:border-r md:border-slate-200">
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Property Type</label>
                        <select name="property_type" class="w-full bg-transparent outline-none text-sm text-slate-800">
                            <option value="">All types</option>
                            <option value="house" {{ request('property_type') == 'house' ? 'selected' : '' }}>House</option>
                            <option value="flat" {{ request('property_type') == 'flat' ? 'selected' : '' }}>Flat</option>
                            <option value="apartment" {{ request('property_type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                            <option value="room" {{ request('property_type') == 'room' ? 'selected' : '' }}>Room</option>
                        </select>
                    </div>

                    <button type="submit" class="h-12 w-12 shrink-0 rounded-full bg-rose-500 text-white flex items-center justify-center hover:bg-rose-600 transition md:mr-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Explore Stays Section -->
    <section id="featured" class="py-8 lg:py-10">
        <div class="w-full px-4 lg:px-6">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-slate-950">Explore stays</h2>
                <p class="mt-2 text-sm text-slate-500">
                    <span class="font-semibold text-slate-900">{{ $featuredListings->count() }}</span>
                    {{ $featuredListings->count() === 1 ? 'property' : 'properties' }} available
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-x-6 gap-y-10 items-start">
                @forelse($featuredListings->take(4) as $property)
                    @php
                        $imageUrl = $property->getFirstImageUrl();
                        $minRoomPrice = $property->min_room_price !== null ? (float) $property->min_room_price : null;
                        $maxRoomPrice = $property->max_room_price !== null ? (float) $property->max_room_price : null;

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

                            $subtitle = $property->property_availability_label ?? 'All rooms booked';
                        } else {
                            $priceAmount = 'Rs ' . number_format((float) $property->rent_price);
                            $priceSuffix = '/month';
                            $subtitle = $property->property_availability_label ?? 'Available for booking';
                        }

                        $availabilityLabel = $property->is_property_bookable ? 'Available' : 'Unavailable';
                        $availabilityClass = $property->is_property_bookable ? 'available' : 'unavailable';
                        $priceToneClass = $priceAmount === 'Price on request' ? 'is-muted' : 'is-red';
                    @endphp

                    <a href="{{ route('listings.show', $property) }}" class="listing-card">
                        <div class="listing-image-wrap aspect-[4/3] mb-3">
                            <img src="{{ $imageUrl }}" alt="{{ $property->title }}" class="listing-image">

                            <div class="absolute inset-x-0 top-0 flex items-start justify-between gap-3 p-3">
                                @if($property->is_verified)
                                    <span class="listing-chip bg-white/92 text-slate-800 shadow-sm">Verified</span>
                                @else
                                    <span class="listing-chip bg-white/92 text-slate-800 shadow-sm">{{ $property->rental_mode === 'per_room' ? 'Room choice' : 'Whole place' }}</span>
                                @endif

                                <button type="button" class="save-btn" tabindex="-1" onclick="event.preventDefault(); event.stopPropagation(); showGuestSavePrompt();" title="Sign up to save property">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="listing-details">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-[15px] font-semibold text-slate-950 leading-6 line-clamp-1">{{ $property->title }}</h3>
                                <span class="shrink-0 text-sm font-semibold text-slate-900">{{ $property->city ?: 'Nepal' }}</span>
                            </div>

                            <p class="text-sm text-slate-500 line-clamp-1">{{ $property->address ?: ($property->location ?: 'Location not specified') }}</p>
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
                @empty
                    <div class="col-span-4 text-center py-12">
                        <p class="text-slate-500 text-base">No featured listings available at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <!-- How FindNest Works -->
    <section id="how-it-works" class="bg-slate-50 py-14 lg:py-16">
        <div class="mx-auto max-w-7xl px-4 lg:px-6">
            <div class="mx-auto max-w-2xl text-center">
                <span class="inline-flex items-center rounded-full border border-rose-100 bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-rose-500 shadow-sm">
                    Simple Process
                </span>
                <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 lg:text-[2.5rem]">How FindNest Works</h2>
                <p class="mt-3 text-sm leading-7 text-slate-500 lg:text-base">Your journey to the right accommodation in three simple, well-guided steps.</p>
            </div>

            <div class="fn-step-timeline relative mt-10 lg:mt-12">
                <div class="fn-step-track" aria-hidden="true"></div>

                <div class="grid grid-cols-1 gap-5 lg:grid-cols-3 lg:gap-6 items-stretch">
                    <article class="fn-step-card group">
                        <div class="fn-step-icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-4.35-4.35m1.35-5.15a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"></path>
                            </svg>
                        </div>
                        <h3 class="fn-step-title">Search &amp; Filter</h3>
                        <p class="fn-step-copy">Browse verified properties using smart filters for location, price, and amenities.</p>
                    </article>

                    <article class="fn-step-card group">
                        <div class="fn-step-icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-1a4 4 0 00-5-3.87M17 20H7m10 0v-1c0-.7-.15-1.37-.42-1.98M7 20H2v-1a4 4 0 015-3.87M7 20v-1c0-.7.15-1.37.42-1.98m0 0a5 5 0 019.16 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM8 10a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="fn-step-title">Connect</h3>
                        <p class="fn-step-copy">Find compatible roommates and connect with property owners directly.</p>
                    </article>

                    <article class="fn-step-card group">
                        <div class="fn-step-icon">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 11.5l9-7 9 7M5.5 10v9a1 1 0 001 1h3.5v-5a1 1 0 011-1h2a1 1 0 011 1v5h3.5a1 1 0 001-1v-9"></path>
                            </svg>
                        </div>
                        <h3 class="fn-step-title">Move In</h3>
                        <p class="fn-step-copy">Book securely with integrated payment and get ready for your new home.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <!-- Roommate Matching Section -->
    <section id="roommates" class="py-14 lg:py-16">
        <div class="mx-auto max-w-7xl px-4 lg:px-6">
            <div class="overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-[0_18px_48px_rgba(15,23,42,0.06)]">
                <div class="grid grid-cols-1 lg:grid-cols-[1.08fr_0.92fr]">
                    <div class="relative min-h-[300px] overflow-hidden bg-slate-100 lg:min-h-[420px]">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1200&h=900&fit=crop"
                            alt="Roommate Matching"
                            class="absolute inset-0 h-full w-full object-cover transition duration-500 hover:scale-105">
                    </div>

                    <div class="flex flex-col items-start justify-center p-7 text-left sm:p-8 lg:p-12">
                        <span class="inline-flex w-fit items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                            Smart Matching
                        </span>
                        <h2 class="mt-4 text-3xl font-bold leading-tight tracking-tight text-slate-950 lg:text-[2.25rem]">Find Your Perfect Roommate</h2>
                        <p class="mt-4 max-w-xl text-sm leading-7 text-slate-500 lg:text-base">Our intelligent matching algorithm connects you with compatible roommates based on lifestyle and preferences.</p>

                        <div class="mt-7 space-y-4">
                            <div class="flex items-start gap-3">
                                <span class="mt-1 flex h-6 w-6 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0l-3.25-3.25a1 1 0 011.414-1.414l2.543 2.543 6.543-6.543a1 1 0 011.414 0z"/></svg>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Compatibility Score</p>
                                    <p class="mt-1 text-sm text-slate-500">Match percentages based on preferences</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <span class="mt-1 flex h-6 w-6 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M2 5a2 2 0 012-2h12a2 2 0 012 2v6a2 2 0 01-2 2H6l-4 4V5z"/></svg>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Direct Messaging</p>
                                    <p class="mt-1 text-sm text-slate-500">Chat before deciding</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <span class="mt-1 flex h-6 w-6 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1a4 4 0 00-4 4v2H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2V9a2 2 0 00-2-2h-1V5a4 4 0 00-4-4zm2 6V5a2 2 0 10-4 0v2h4z" clip-rule="evenodd"/></svg>
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Verified Profiles</p>
                                    <p class="mt-1 text-sm text-slate-500">All users verified for safety</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            @auth
                                <a href="{{ url('/roommates/profile') }}" class="inline-flex items-center justify-center rounded-2xl bg-rose-500 px-6 py-3 text-sm font-semibold text-white shadow-[0_12px_24px_rgba(255,56,92,0.22)] transition hover:-translate-y-0.5 hover:bg-rose-600">Start Matching Now</a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-2xl bg-rose-500 px-6 py-3 text-sm font-semibold text-white shadow-[0_12px_24px_rgba(255,56,92,0.22)] transition hover:-translate-y-0.5 hover:bg-rose-600">Start Matching Now</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call-to-Action Section -->
    <section class="bg-slate-50 py-14 lg:py-16">
        <div class="mx-auto max-w-7xl px-4 lg:px-6">
            <div class="overflow-hidden rounded-[32px] border border-slate-200 bg-white shadow-[0_18px_48px_rgba(15,23,42,0.06)]">
                <div class="grid grid-cols-1 lg:grid-cols-[1.05fr_0.95fr]">
                    <div class="p-8 text-left sm:p-10 lg:p-12">
                        <span class="inline-flex items-center rounded-full border border-rose-100 bg-rose-50 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-rose-500 shadow-sm">
                            Get Started
                        </span>
                        <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-950 lg:text-[2.5rem]">Ready to Find Your Perfect Home?</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-500 lg:text-base">Join thousands of people who have found their ideal accommodation and roommates through FindNest.</p>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl bg-rose-500 px-7 py-3 text-sm font-semibold text-white shadow-[0_12px_24px_rgba(255,56,92,0.22)] transition hover:-translate-y-0.5 hover:bg-rose-600">Create Free Account</a>
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-7 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Sign In</a>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 bg-slate-50/60 p-8 sm:p-10 lg:border-l lg:border-t-0 lg:p-12">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A12.01 12.01 0 0112 3c-3.183 0-6.073 1.234-8.235 3.25C3.272 7.62 3 8.298 3 9c0 5.25 3.438 9.94 9 11 5.562-1.06 9-5.75 9-11 0-.702-.272-1.38-.765-1.75z"></path>
                                    </svg>
                                </div>
                                <p class="mt-3 text-sm font-semibold text-slate-900">Verified Properties</p>
                                <p class="mt-1 text-xs leading-6 text-slate-500">Trusted listings from approved owners.</p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2V9a2 2 0 00-2-2h-1V6a5 5 0 10-10 0v1H6a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="mt-3 text-sm font-semibold text-slate-900">Secure Payments</p>
                                <p class="mt-1 text-xs leading-6 text-slate-500">Book confidently through a safer payment flow.</p>
                            </div>

                            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-rose-50 text-rose-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18.364 5.636A9 9 0 105.636 18.364M9 9h.01M15 9h.01M8 13a5 5 0 018 0"></path>
                                    </svg>
                                </div>
                                <p class="mt-3 text-sm font-semibold text-slate-900">Helpful Support</p>
                                <p class="mt-1 text-xs leading-6 text-slate-500">Clear assistance when you need guidance.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('components.footer')

    <script>

        function showGuestSavePrompt() {
            const existingToast = document.getElementById('guest-save-toast');
            if (existingToast) {
                existingToast.remove();
            }

            const toast = document.createElement('div');
            toast.id = 'guest-save-toast';
            toast.innerHTML = `
                <div style="font-weight:600; margin-bottom:6px;">Sign up to save property</div>
                <div style="font-size:13px; opacity:0.9;">Create an account to save listings to your favorites.</div>
                <a href="{{ route('register') }}" style="display:inline-flex; margin-top:10px; color:#fff; font-weight:600; text-decoration:underline;">Sign up</a>
            `;
            toast.style.cssText = `
                position: fixed;
                right: 20px;
                bottom: 20px;
                width: min(320px, calc(100vw - 32px));
                background: #111827;
                color: #fff;
                padding: 16px 18px;
                border-radius: 16px;
                box-shadow: 0 20px 40px rgba(15, 23, 42, 0.22);
                z-index: 9999;
                animation: slideIn 0.25s ease;
            `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOut 0.25s ease';
                setTimeout(() => toast.remove(), 250);
            }, 2600);
        }

        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('active');
            });
        }

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#' || !href.startsWith('#')) return;

                e.preventDefault();
                const target = document.querySelector(href);

                if (target) {
                    if (mobileMenu) {
                        mobileMenu.classList.remove('active');
                    }
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Active link highlighting
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.fn-nav-link[data-section]');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    navLinks.forEach(link => link.classList.remove('active'));
                    const correspondingLink = document.querySelector(`.fn-nav-link[data-section="${entry.target.id}"]`);
                    if (correspondingLink) {
                        correspondingLink.classList.add('active');
                    }
                }
            });
        }, { root: null, rootMargin: '-20% 0px -70% 0px', threshold: 0 });

        sections.forEach(section => {
            observer.observe(section);
        });
    </script>
</body>
</html>
