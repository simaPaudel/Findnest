<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $property->title }} - FindNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* FindNest Theme Variables */
        :root {
            --fn-red: #FF385C;
            --fn-red-hover: #E11D48;
            --fn-white: #FFFFFF;
            --fn-gray: #F7F7F7;
            --fn-charcoal: #1F2937;
            --fn-gray-light: #E5E7EB;
            --fn-gray-dark: #6B7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--fn-white);
            color: var(--fn-charcoal);
        }

        .fn-bg-red { background-color: var(--fn-red); }
        .fn-bg-white { background-color: var(--fn-white); }
        .fn-bg-gray { background-color: var(--fn-gray); }
        .fn-text-red { color: var(--fn-red); }
        .fn-text-charcoal { color: var(--fn-charcoal); }
        .fn-text-white { color: var(--fn-white); }
        .fn-text-gray { color: var(--fn-gray-dark); }

        /* Navbar */
        .fn-navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--fn-gray-light);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.04);
        }

        .fn-nav-link {
            color: var(--fn-charcoal);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
        }

        .fn-nav-link:hover {
            background: rgba(255, 56, 92, 0.08);
            color: var(--fn-red);
        }

        /* Buttons */
        .fn-btn-primary {
            background: linear-gradient(135deg, var(--fn-red) 0%, #ff1744 100%);
            color: var(--fn-white);
            padding: 14px 32px;
            border-radius: 16px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(255, 56, 92, 0.25);
            display: inline-block;
            text-decoration: none;
        }

        .fn-btn-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 32px rgba(255, 56, 92, 0.35);
        }

        .fn-btn-secondary {
            background: transparent;
            color: var(--fn-charcoal);
            padding: 12px 28px;
            border-radius: 16px;
            font-weight: 500;
            border: 2px solid var(--fn-gray-light);
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-block;
            text-decoration: none;
        }

        .fn-btn-secondary:hover {
            border-color: var(--fn-red);
            background: rgba(255, 56, 92, 0.05);
            color: var(--fn-red);
        }

        /* Cards */
        .fn-glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(229, 231, 235, 0.8);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            padding: 24px;
        }

        /* Badge */
        .fn-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 16px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            backdrop-filter: blur(8px);
        }

        .fn-badge-red {
            background: rgba(255, 56, 92, 0.15);
            color: var(--fn-red);
            border: 1px solid rgba(255, 56, 92, 0.3);
        }

        .fn-badge-gray {
            background: rgba(107, 114, 128, 0.15);
            color: var(--fn-gray-dark);
            border: 1px solid rgba(107, 114, 128, 0.3);
        }

        .fn-badge-green {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        /* Star Rating */
        .star-rating {
            display: inline-flex;
            gap: 4px;
        }

        .star {
            color: #D1D5DB;
        }

        .star.filled {
            color: #FBBF24;
        }

        /* Image container */
        .property-image {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .property-image img {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }

        /* Save Button */
        .save-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            background: transparent;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: none;
            z-index: 10;
            padding: 0;
        }

        .save-btn svg {
            width: 28px;
            height: 28px;
            color: white;
            stroke-width: 2;
            filter: drop-shadow(0 1px 3px rgba(0, 0, 0, 0.3));
            transition: all 0.3s ease;
        }

        .save-btn:hover svg {
            color: var(--fn-red);
            transform: scale(1.15);
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.4));
        }

        .save-btn.saved svg {
            color: var(--fn-red);
            fill: var(--fn-red);
            filter: drop-shadow(0 2px 6px rgba(255, 56, 92, 0.4));
        }

        /* Info Grid */
        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: var(--fn-gray);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background: rgba(255, 56, 92, 0.05);
            transform: translateY(-2px);
        }

        .info-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--fn-red) 0%, #ff1744 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        /* Review Card */
        .review-card {
            padding: 20px;
            border: 1px solid var(--fn-gray-light);
            border-radius: 16px;
            transition: all 0.3s ease;
        }

        .review-card:hover {
            border-color: var(--fn-red);
            box-shadow: 0 4px 16px rgba(255, 56, 92, 0.1);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="fn-navbar">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-2xl font-bold fn-text-red">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    FindNest
                </a>

                <div class="flex items-center gap-4">
                    <a href="{{ route('listings.index') }}" class="fn-nav-link">Browse Listings</a>
                    @auth
                        <a href="{{ route('user.dashboard') }}" class="fn-nav-link">Dashboard</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="fn-nav-link">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="fn-nav-link">Login</a>
                        <a href="{{ route('register') }}" class="fn-btn-primary">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <a href="{{ route('listings.index') }}" class="fn-text-gray hover:fn-text-red transition">← Back to Listings</a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Property Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Image Section -->
                    <div class="property-image">
                        @php
                            $photos = is_array($property->photos) ? $property->photos : (json_decode($property->photos, true) ?? []);
                            $firstPhoto = $photos[0] ?? null;
                        @endphp
                        @if($firstPhoto)
                            <img src="{{ asset('storage/' . $firstPhoto) }}" alt="{{ $property->title }}">
                        @else
                            <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1200&h=600&fit=crop" alt="{{ $property->title }}">
                        @endif

                        <!-- Save Button (only for authenticated users) -->
                        @auth
                            <button class="save-btn save-property-btn" 
                                    data-property-id="{{ $property->id }}"
                                    title="Save this listing">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </button>
                        @endauth
                    </div>

                    <!-- Title and Location -->
                    <div class="fn-glass-card">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h1 class="text-3xl font-bold fn-text-charcoal mb-2">{{ $property->title }}</h1>
                                <p class="fn-text-gray text-lg">
                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $property->city ?? $property->location }}
                                </p>
                            </div>
                            @if($property->is_verified)
                                <span class="fn-badge fn-badge-green">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Verified
                                </span>
                            @endif
                        </div>

                        <div class="text-4xl font-bold fn-text-red mb-4">
                            @npr($property->rent_price)<span class="text-xl fn-text-gray">/month</span>
                        </div>

                        <!-- Property Info Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div class="info-item">
                                <div class="info-icon">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm fn-text-gray">Room Type</div>
                                    <div class="font-semibold capitalize">{{ $property->room_type ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm fn-text-gray">Gender</div>
                                    <div class="font-semibold capitalize">{{ $property->gender_preference ?? 'Any' }}</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm fn-text-gray">Furnished</div>
                                    <div class="font-semibold">{{ $property->furnished ? 'Yes' : 'No' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($property->description)
                    <div class="fn-glass-card">
                        <h2 class="text-2xl font-bold fn-text-charcoal mb-4">About This Property</h2>
                        <p class="fn-text-gray leading-relaxed whitespace-pre-line">{{ $property->description }}</p>
                    </div>
                    @endif

                    <!-- Address Details -->
                    @if($property->address || $property->landmark)
                    <div class="fn-glass-card">
                        <h2 class="text-2xl font-bold fn-text-charcoal mb-4">Location Details</h2>
                        <div class="space-y-2">
                            @if($property->address)
                                <p class="fn-text-gray"><strong>Address:</strong> {{ $property->address }}</p>
                            @endif
                            @if($property->landmark)
                                <p class="fn-text-gray"><strong>Nearby Landmark:</strong> {{ $property->landmark }}</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Amenities -->
                    @if($property->amenities && count($property->amenities) > 0)
                    <div class="fn-glass-card">
                        <h2 class="text-2xl font-bold fn-text-charcoal mb-4">Amenities</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($property->amenities as $amenity)
                                <div class="flex items-center gap-2 fn-text-gray">
                                    <svg class="w-5 h-5 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="capitalize">{{ is_array($amenity) ? $amenity['name'] ?? $amenity : $amenity }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Rules -->
                    @if($property->rules)
                    <div class="fn-glass-card">
                        <h2 class="text-2xl font-bold fn-text-charcoal mb-4">House Rules</h2>
                        <p class="fn-text-gray leading-relaxed whitespace-pre-line">{{ $property->rules }}</p>
                    </div>
                    @endif

                    <!-- Reviews Section -->
                    <div class="fn-glass-card">
                        <h2 class="text-2xl font-bold fn-text-charcoal mb-4">
                            Reviews 
                            @if($reviewCount > 0)
                                <span class="fn-text-gray text-lg">({{ $reviewCount }})</span>
                            @endif
                        </h2>

                        @if($reviewCount > 0)
                            <!-- Average Rating -->
                            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                <div class="text-3xl font-bold fn-text-red mb-2">{{ number_format($avgRating, 1) }}/5</div>
                                <div class="star-rating mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-6 h-6 star {{ $i <= round($avgRating) ? 'filled' : '' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                                <p class="fn-text-gray text-sm">Based on {{ $reviewCount }} {{ $reviewCount == 1 ? 'review' : 'reviews' }}</p>
                            </div>

                            <!-- Review List -->
                            <div class="space-y-4">
                                @foreach($reviews as $review)
                                    <div class="review-card">
                                        <div class="flex items-start justify-between mb-3">
                                            <div>
                                                <h4 class="font-semibold fn-text-charcoal">{{ $review->user->name ?? 'Anonymous' }}</h4>
                                                <p class="text-sm fn-text-gray">{{ $review->created_at->format('M d, Y') }}</p>
                                            </div>
                                            <div class="star-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 star {{ $i <= $review->rating ? 'filled' : '' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="fn-text-gray">{{ $review->review_text }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 mx-auto fn-text-gray mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                </svg>
                                <p class="fn-text-gray">No reviews yet. Be the first to review this property!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column - Owner & Booking -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-6">
                        <!-- Owner Info Card -->
                        <div class="fn-glass-card">
                            <h3 class="text-xl font-bold fn-text-charcoal mb-4">Property Owner</h3>
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 rounded-full fn-bg-red flex items-center justify-center text-white text-2xl font-bold">
                                    {{ strtoupper(substr($property->owner->name ?? 'O', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-semibold fn-text-charcoal">{{ $property->owner->name ?? 'Owner' }}</h4>
                                    <p class="text-sm fn-text-gray">Property Owner</p>
                                </div>
                            </div>
                            
                            @auth
                                @if($property->owner->email)
                                    <div class="mt-4 p-3 fn-bg-gray rounded-lg">
                                        <p class="text-sm fn-text-gray mb-1">Contact Email:</p>
                                        <p class="font-medium fn-text-charcoal">{{ $property->owner->email }}</p>
                                    </div>
                                @endif
                            @else
                                <div class="mt-4 p-3 fn-bg-gray rounded-lg text-center">
                                    <p class="text-sm fn-text-gray">
                                        <svg class="w-5 h-5 inline mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        Login to view contact details
                                    </p>
                                </div>
                            @endauth
                        </div>

                        <!-- Booking CTA -->
                        <div class="fn-glass-card">
                            <h3 class="text-xl font-bold fn-text-charcoal mb-4">Interested?</h3>
                            @auth
                                <a href="#" class="fn-btn-primary w-full text-center block">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Request Booking
                                </a>
                                <p class="text-sm fn-text-gray mt-3 text-center">Owner will respond within 24 hours</p>
                            @else
                                <a href="{{ route('login') }}" class="fn-btn-primary w-full text-center block">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    Login to Book
                                </a>
                                <p class="text-sm fn-text-gray mt-3 text-center">Create an account to request bookings</p>
                            @endauth
                        </div>

                        <!-- Quick Info -->
                        @if($property->total_rooms)
                        <div class="fn-glass-card">
                            <h3 class="text-xl font-bold fn-text-charcoal mb-3">Quick Info</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="fn-text-gray">Total Rooms:</span>
                                    <span class="font-semibold">{{ $property->total_rooms }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="fn-text-gray">Status:</span>
                                    <span class="fn-badge fn-badge-green">Available</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Roommate Match Section -->
            <div class="mt-16">
                <div class="fn-glass-card">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold fn-text-charcoal mb-2">
                            <svg class="w-8 h-8 inline mr-2 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Roommate Match (Optional)
                        </h2>
                        <p class="fn-text-gray">Find compatible roommates for this area and budget.</p>
                    </div>

                    @auth
                        @php
                            $pref = \App\Models\RoommatePreference::where('user_id', auth()->id())->first();
                        @endphp

                        @if($pref)
                            <!-- User has preferences -->
                            <div class="mb-6 p-6 fn-bg-gray rounded-lg">
                                <h3 class="font-semibold fn-text-charcoal mb-4">Your Roommate Preferences</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @if($pref->preferred_location)
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <svg class="w-5 h-5 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <span class="text-sm fn-text-gray">Preferred Location</span>
                                            </div>
                                            <p class="font-medium fn-text-charcoal">{{ $pref->preferred_location }}</p>
                                        </div>
                                    @endif

                                    @if($pref->budget_range)
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <svg class="w-5 h-5 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-sm fn-text-gray">Budget Range</span>
                                            </div>
                                            <p class="font-medium fn-text-charcoal">{{ $pref->budget_range }}</p>
                                        </div>
                                    @endif

                                    @if($pref->gender_preference)
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <svg class="w-5 h-5 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span class="text-sm fn-text-gray">Gender Preference</span>
                                            </div>
                                            <p class="font-medium fn-text-charcoal capitalize">{{ $pref->gender_preference }}</p>
                                        </div>
                                    @endif
                                </div>

                                @if($pref->interests)
                                    <div class="mt-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-5 h-5 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                            <span class="text-sm fn-text-gray">Interests</span>
                                        </div>
                                        <p class="fn-text-charcoal">{{ $pref->interests }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="text-center">
                                <a href="{{ route('roommates.matches') }}" class="fn-btn-primary">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    See My Matches
                                </a>
                                <p class="text-sm fn-text-gray mt-3">Find compatible roommates based on your preferences</p>
                            </div>
                        @else
                            <!-- User has no preferences yet -->
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 mx-auto fn-text-gray mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="fn-text-gray mb-6">You haven't set up your roommate preferences yet.</p>
                                <a href="{{ route('roommates.profile') }}" class="fn-btn-primary">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Set Up Preferences
                                </a>
                            </div>
                        @endif
                    @else
                        <!-- User not logged in -->
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 mx-auto fn-text-gray mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <p class="fn-text-gray mb-6">Login to see compatible roommate matches for this area and budget.</p>
                            <a href="{{ route('login') }}" class="fn-btn-primary">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                Login to See Roommate Matches
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Similar Properties Section -->
            @if($similar->count() > 0)
            <div class="mt-16">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold fn-text-charcoal mb-2">Similar Properties</h2>
                    <p class="fn-text-gray">You might also be interested in these listings</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($similar as $p)
                        @php
                            $photos = is_array($p->photos) ? $p->photos : (json_decode($p->photos, true) ?? []);
                            $firstPhoto = $photos[0] ?? null;
                        @endphp
                        <a href="{{ route('listings.show', $p->id) }}" class="fn-glass-card overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                            <div class="relative h-48 overflow-hidden rounded-t-lg">
                                @if($firstPhoto)
                                    <img src="{{ asset('storage/' . $firstPhoto) }}" 
                                         alt="{{ $p->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=400&h=300&fit=crop" 
                                         alt="{{ $p->title }}" 
                                         class="w-full h-full object-cover">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold fn-text-charcoal text-lg mb-2 truncate">{{ $p->title }}</h3>
                                <p class="text-sm fn-text-gray mb-3">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $p->city ?? $p->location }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="text-xl font-bold fn-text-red">@npr($p->rent_price)</span>
                                    <span class="text-sm fn-text-gray">/month</span>
                                </div>
                                @if($p->room_type)
                                    <div class="mt-3">
                                        <span class="fn-badge fn-badge-gray text-xs capitalize">{{ $p->room_type }}</span>
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="fn-bg-gray py-8 mt-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 text-center">
            <p class="fn-text-gray">&copy; 2026 FindNest. All rights reserved.</p>
        </div>
    </footer>

    <!-- Save Listing Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all save buttons
            const saveButtons = document.querySelectorAll('.save-property-btn');

            saveButtons.forEach(button => {
                const propertyId = button.dataset.propertyId;

                // Check if property is already saved
                checkSaveStatus(propertyId, button);

                // Add click handler
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleSave(propertyId, button);
                });
            });

            function checkSaveStatus(propertyId, button) {
                fetch(`/user/saved-listings/check/${propertyId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.is_saved) {
                        button.classList.add('saved');
                    }
                })
                .catch(error => console.error('Error checking save status:', error));
            }

            function toggleSave(propertyId, button) {
                const isSaved = button.classList.contains('saved');
                const url = isSaved 
                    ? `/user/saved-listings/unsave/${propertyId}`
                    : `/user/saved-listings/save/${propertyId}`;
                const method = isSaved ? 'DELETE' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.is_saved) {
                            button.classList.add('saved');
                            showNotification('Listing saved successfully!', 'success');
                        } else {
                            button.classList.remove('saved');
                            showNotification('Listing removed from saved', 'info');
                        }
                    } else {
                        showNotification(data.message || 'Error saving listing', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error saving listing', 'error');
                });
            }

            function showNotification(message, type) {
                // Simple notification
                const notification = document.createElement('div');
                notification.textContent = message;
                notification.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 12px 20px;
                    border-radius: 12px;
                    font-weight: 500;
                    z-index: 9999;
                    animation: slideIn 0.3s ease;
                    ${type === 'success' ? 'background: #10b981; color: white;' : ''}
                    ${type === 'error' ? 'background: #ef4444; color: white;' : ''}
                    ${type === 'info' ? 'background: #3b82f6; color: white;' : ''}
                `;
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }
        });

        // Add CSS animation for notifications
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
