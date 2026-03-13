<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindNest - Find Your Perfect Accommodation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* FindNest Airbnb-Style Theme - CSS Variables */
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

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--fn-white);
            color: var(--fn-charcoal);
            overflow-x: hidden;
        }

        /* Utility Classes - FindNest Theme */
        .fn-bg-red { background-color: var(--fn-red); }
        .fn-bg-white { background-color: var(--fn-white); }
        .fn-bg-gray { background-color: var(--fn-gray); }
        .fn-bg-charcoal { background-color: var(--fn-charcoal); }
        
        .fn-text-red { color: var(--fn-red); }
        .fn-text-charcoal { color: var(--fn-charcoal); }
        .fn-text-white { color: var(--fn-white); }
        .fn-text-gray { color: var(--fn-gray-dark); }

        /* Glassmorphism Card */
        .fn-glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(229, 231, 235, 0.8);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .fn-glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 48px rgba(255, 56, 92, 0.12);
        }

        /* Primary Button */
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
            position: relative;
            overflow: hidden;
        }

        .fn-btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .fn-btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }

        .fn-btn-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 32px rgba(255, 56, 92, 0.35);
            color: var(--fn-white);
        }

        /* Secondary Button */
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
            transform: translateY(-2px);
        }

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
            position: relative;
        }

        .fn-nav-link:hover {
            background: rgba(255, 56, 92, 0.08);
            color: var(--fn-red);
        }

        .fn-nav-link.active {
            color: var(--fn-red);
        }

        .fn-nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 16px;
            right: 16px;
            height: 2px;
            background: var(--fn-red);
            border-radius: 2px;
        }

        /* Mobile Menu */
        .fn-mobile-menu {
            display: none;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(16px);
            border-top: 1px solid var(--fn-gray-light);
        }

        .fn-mobile-menu.active {
            display: block;
        }

        /* Hero Section */
        .fn-hero {
            background: linear-gradient(135deg, #FFF5F7 0%, var(--fn-white) 100%);
            position: relative;
            overflow: hidden;
        }

        .fn-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(255, 56, 92, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse 8s ease-in-out infinite;
        }

        .fn-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 56, 92, 0.06) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse 10s ease-in-out infinite reverse;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.6; }
            50% { transform: scale(1.15); opacity: 1; }
        }

        /* Search Bar */
        .fn-search-bar {
            background: var(--fn-white);
            backdrop-filter: blur(16px);
            border: 2px solid var(--fn-gray-light);
            border-radius: 20px;
            padding: 8px;
            display: flex;
            gap: 8px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .fn-search-bar:hover {
            box-shadow: 0 12px 40px rgba(255, 56, 92, 0.12);
        }

        .fn-search-input {
            background: var(--fn-gray);
            border: 1px solid transparent;
            border-radius: 14px;
            padding: 14px 20px;
            color: var(--fn-charcoal);
            outline: none;
            transition: all 0.3s ease;
            flex: 1;
        }

        .fn-search-input:focus {
            border-color: var(--fn-red);
            background: var(--fn-white);
            box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.1);
        }

        .fn-search-input::placeholder {
            color: var(--fn-gray-dark);
        }

        /* Listing Card */
        .fn-listing-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            cursor: pointer;
        }

        .fn-listing-card img {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .fn-listing-card:hover img {
            transform: scale(1.08);
        }

        .fn-image-overlay {
            background: linear-gradient(180deg, rgba(31, 41, 55, 0) 0%, rgba(31, 41, 55, 0.9) 100%);
        }

        /* Fade-in animation */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in:nth-child(1) { animation-delay: 0.1s; }
        .fade-in:nth-child(2) { animation-delay: 0.2s; }
        .fade-in:nth-child(3) { animation-delay: 0.3s; }
        .fade-in:nth-child(4) { animation-delay: 0.4s; }
        .fade-in:nth-child(5) { animation-delay: 0.5s; }
        .fade-in:nth-child(6) { animation-delay: 0.6s; }

        /* Step Card */
        .fn-step-card {
            position: relative;
            padding: 32px;
            text-align: center;
            transition: all 0.4s ease;
        }

        .fn-step-card:hover {
            transform: translateY(-8px);
        }

        .fn-step-number {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--fn-red) 0%, #ff1744 100%);
            color: var(--fn-white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-weight: 700;
            font-size: 2rem;
            box-shadow: 0 8px 24px rgba(255, 56, 92, 0.25);
            transition: all 0.4s ease;
        }

        .fn-step-card:hover .fn-step-number {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 12px 32px rgba(255, 56, 92, 0.35);
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

        /* Responsive */
        @media (max-width: 768px) {
            .fn-search-bar {
                flex-direction: column;
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--fn-gray);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--fn-red);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--fn-red-hover);
        }
    </style>
</head>
<body class="fn-bg-white">
    <!-- Sticky Navigation -->
    <nav class="fn-navbar">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 text-2xl font-bold fn-text-red">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    FindNest
                </a>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center gap-2" id="navbar-links">
                    <a href="#featured" class="fn-nav-link" data-section="featured">Browse Listings</a>
                    <a href="#how-it-works" class="fn-nav-link" data-section="how-it-works">How It Works</a>
                    <a href="#roommates" class="fn-nav-link" data-section="roommates">Find Roommates</a>
                    <a href="{{ route('login') }}" class="fn-nav-link ml-4">Login</a>
                    <a href="{{ route('register') }}" class="fn-btn-primary">Get Started</a>
                </div>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-toggle" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6 fn-text-charcoal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="fn-mobile-menu md:hidden">
                <div class="py-4 space-y-2">
                    <a href="#featured" class="fn-nav-link block" data-section="featured">Browse Listings</a>
                    <a href="#how-it-works" class="fn-nav-link block" data-section="how-it-works">How It Works</a>
                    <a href="#roommates" class="fn-nav-link block" data-section="roommates">Find Roommates</a>
                    <a href="{{ route('login') }}" class="fn-nav-link block mt-4">Login</a>
                    <a href="{{ route('register') }}" class="fn-btn-primary block text-center mt-2">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="fn-hero py-20 lg:py-32 relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold fn-text-charcoal mb-6 leading-tight">
                    Find Your Perfect Accommodation
                </h1>
                <p class="text-lg md:text-xl fn-text-gray mb-8 max-w-2xl mx-auto">
                    Discover verified rooms and connect with ideal roommates in one trusted platform.
                </p>

                <!-- Search Bar -->
                <form action="{{ route('listings.index') }}" method="GET" class="fn-search-bar max-w-4xl mx-auto flex-col md:flex-row">
                    <input type="text" name="q" placeholder="Location (e.g., Downtown, Campus)" class="fn-search-input" value="{{ request('q') }}">
                    <input type="text" name="max_price" placeholder="Max Price (e.g., Rs 50000)" class="fn-search-input md:max-w-xs" value="{{ request('max_price') }}">
                    <button type="submit" class="fn-btn-primary px-8 whitespace-nowrap">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search
                        </span>
                    </button>
                </form>

                <!-- Quick Stats -->
                <div class="grid grid-cols-3 gap-8 mt-16 max-w-2xl mx-auto">
                    <div class="fade-in">
                        <div class="text-3xl md:text-4xl font-bold fn-text-red mb-2">{{ $stats['totalListings'] }}+</div>
                        <div class="text-sm fn-text-gray">Properties</div>
                    </div>
                    <div class="fade-in">
                        <div class="text-3xl md:text-4xl font-bold fn-text-red mb-2">{{ $stats['activeOwners'] }}</div>
                        <div class="text-sm fn-text-gray">Active Owners</div>
                    </div>
                    <div class="fade-in">
                        <div class="text-3xl md:text-4xl font-bold fn-text-red mb-2">{{ $stats['verifiedListings'] }}</div>
                        <div class="text-sm fn-text-gray">Verified</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Listings Section -->
    <section id="featured" class="py-20 relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold fn-text-charcoal mb-4">Featured Listings</h2>
                <p class="fn-text-gray text-lg">Handpicked properties perfect for comfortable living</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($featuredListings as $property)
                    @php
                        $photos = is_array($property->photos) ? $property->photos : (json_decode($property->photos, true) ?? []);
                        $firstPhoto = $photos[0] ?? null;
                    @endphp
                    <a href="{{ route('listings.show', $property) }}" class="fn-glass-card overflow-hidden fn-listing-card fade-in">
                        <div class="relative h-64 overflow-hidden">
                            @if($firstPhoto)
                                <img src="{{ asset('storage/' . $firstPhoto) }}" 
                                     alt="{{ $property->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=600&h=400&fit=crop" 
                                     alt="{{ $property->title }}" 
                                     class="w-full h-full object-cover">
                            @endif
                            <div class="absolute inset-0 fn-image-overlay"></div>
                            <div class="absolute top-4 right-4">
                                @if($property->is_verified)
                                    <span class="fn-badge fn-badge-gray">Verified</span>
                                @else
                                    <span class="fn-badge fn-badge-red">Featured</span>
                                @endif
                            </div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <h3 class="text-xl font-bold fn-text-white mb-1">{{ $property->title }}</h3>
                                <p class="text-sm fn-text-white opacity-90 mb-2">{{ $property->city ?? $property->location }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-bold fn-text-white">@npr($property->rent_price)/mo</span>
                                    <div class="flex items-center gap-3 text-sm fn-text-white opacity-90">
                                        <span>{{ $property->room_type ?? 'Room' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="fn-text-gray text-lg">No featured listings available at the moment.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-12">
                <a href="{{ url('/listings') }}" class="fn-btn-primary text-lg px-10">Browse All Listings</a>
            </div>
        </div>
    </section>

    <!-- How FindNest Works -->
    <section id="how-it-works" class="py-20 relative fn-bg-gray">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold fn-text-charcoal mb-4">How FindNest Works</h2>
                <p class="fn-text-gray text-lg">Your journey to the perfect accommodation in 3 simple steps</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Step 1 -->
                <div class="fn-step-card">
                    <div class="fn-step-number">1</div>
                    <div class="flex justify-center mb-6">
                        <svg class="w-16 h-16 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold fn-text-charcoal mb-3">Search & Filter</h3>
                    <p class="fn-text-gray leading-relaxed">
                        Browse through verified properties using smart filters for location, price, and amenities to find your perfect match.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="fn-step-card">
                    <div class="fn-step-number">2</div>
                    <div class="flex justify-center mb-6">
                        <svg class="w-16 h-16 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold fn-text-charcoal mb-3">Connect</h3>
                    <p class="fn-text-gray leading-relaxed">
                        Find compatible roommates based on lifestyle preferences and connect with property owners directly.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="fn-step-card">
                    <div class="fn-step-number">3</div>
                    <div class="flex justify-center mb-6">
                        <svg class="w-16 h-16 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold fn-text-charcoal mb-3">Move In</h3>
                    <p class="fn-text-gray leading-relaxed">
                        Book securely with integrated payment options and get ready to enjoy your new home.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Roommate Matching Section -->
    <section id="roommates" class="py-20 relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="fn-glass-card overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                    <!-- Image Side -->
                    <div class="relative h-96 lg:h-auto overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&h=600&fit=crop" 
                             alt="Roommate Matching" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent to-white/50"></div>
                    </div>

                    <!-- Content Side -->
                    <div class="p-8 lg:p-12 flex flex-col justify-center">
                        <div class="mb-6">
                            <span class="fn-badge fn-badge-gray mb-4 inline-block">Smart Matching</span>
                            <h2 class="text-3xl md:text-4xl font-bold fn-text-charcoal mb-4">
                                Find Your Perfect Roommate
                            </h2>
                            <p class="fn-text-gray text-lg leading-relaxed mb-6">
                                Our intelligent matching algorithm connects you with compatible roommates based on lifestyle, study habits, and personal preferences.
                            </p>
                        </div>

                        <div class="space-y-4 mb-8">
                            <div class="flex items-start gap-4">
                                <div class="p-2 fn-bg-red rounded-lg bg-opacity-10 mt-1">
                                    <svg class="w-5 h-5 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold fn-text-charcoal mb-1">Compatibility Score</h4>
                                    <p class="fn-text-gray text-sm">See match percentages based on your preferences</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="p-2 fn-bg-red rounded-lg bg-opacity-10 mt-1">
                                    <svg class="w-5 h-5 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold fn-text-charcoal mb-1">Direct Messaging</h4>
                                    <p class="fn-text-gray text-sm">Chat with potential roommates before deciding</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="p-2 fn-bg-red rounded-lg bg-opacity-10 mt-1">
                                    <svg class="w-5 h-5 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold fn-text-charcoal mb-1">Verified Profiles</h4>
                                    <p class="fn-text-gray text-sm">All users are verified for your safety</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            @auth
                                <a href="{{ url('/roommates/profile') }}" class="fn-btn-primary">Start Matching Now</a>
                            @else
                                <a href="{{ route('login') }}" class="fn-btn-primary">Start Matching Now</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call-to-Action Section -->
    <section class="py-20 relative fn-bg-gray">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 fn-bg-red opacity-10 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-4xl mx-auto px-6 lg:px-12 text-center relative z-10">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold fn-text-charcoal mb-6">
                Ready to Find Your Perfect Home?
            </h2>
            <p class="text-lg md:text-xl fn-text-gray mb-10 max-w-2xl mx-auto">
                Join thousands of people who have already found their ideal accommodation and roommates through FindNest.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="fn-btn-primary text-lg px-10">Create Free Account</a>
                <a href="{{ route('login') }}" class="fn-btn-secondary text-lg px-10">Sign In</a>
            </div>

            <!-- Trust Indicators -->
            <div class="grid grid-cols-3 gap-8 mt-16 max-w-2xl mx-auto">
                <div class="text-center">
                    <svg class="w-12 h-12 fn-text-red mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <p class="text-sm fn-text-gray">Verified<br>Properties</p>
                </div>
                <div class="text-center">
                    <svg class="w-12 h-12 fn-text-red mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <p class="text-sm fn-text-gray">Secure<br>Payments</p>
                </div>
                <div class="text-center">
                    <svg class="w-12 h-12 fn-text-red mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <p class="text-sm fn-text-gray">24/7<br>Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="footer" class="fn-bg-charcoal border-t border-gray-200 py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Brand -->
                <div>
                    <div class="flex items-center gap-2 text-xl font-bold fn-text-red mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        FindNest
                    </div>
                    <p class="fn-text-white opacity-70 text-sm">
                        Your trusted platform for accommodation and roommate matching.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold fn-text-white mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="fn-text-white opacity-70 hover:opacity-100 hover:fn-text-red text-sm transition">Browse Listings</a></li>
                        <li><a href="#" class="fn-text-white opacity-70 hover:opacity-100 hover:fn-text-red text-sm transition">Find Roommates</a></li>
                        <li><a href="#" class="fn-text-white opacity-70 hover:opacity-100 hover:fn-text-red text-sm transition">Post Property</a></li>
                        <li><a href="#" class="fn-text-white opacity-70 hover:opacity-100 hover:fn-text-red text-sm transition">How It Works</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="font-semibold fn-text-white mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="fn-text-white opacity-70 hover:opacity-100 hover:fn-text-red text-sm transition">Help Center</a></li>
                        <li><a href="#" class="fn-text-white opacity-70 hover:opacity-100 hover:fn-text-red text-sm transition">Privacy Policy</a></li>
                        <li><a href="#" class="fn-text-white opacity-70 hover:opacity-100 hover:fn-text-red text-sm transition">Terms of Service</a></li>
                        <li><a href="#" class="fn-text-white opacity-70 hover:opacity-100 hover:fn-text-red text-sm transition">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Connect -->
                <div>
                    <h4 class="font-semibold fn-text-white mb-4">Connect</h4>
                    <div class="flex gap-3">
                        <a href="#" class="p-2 bg-gray-700 rounded-lg hover:bg-red-600 transition">
                            <svg class="w-5 h-5 fn-text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="p-2 bg-gray-700 rounded-lg hover:bg-red-600 transition">
                            <svg class="w-5 h-5 fn-text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="p-2 bg-gray-700 rounded-lg hover:bg-red-600 transition">
                            <svg class="w-5 h-5 fn-text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-700 pt-8 text-center">
                <p class="fn-text-white opacity-60 text-sm">
                    &copy; 2026 FindNest. All rights reserved. Your trusted housing partner.
                </p>
            </div>
        </div>
    </footer>

    <!-- Navigation & Scroll Script -->
    <script>
        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('active');
            });
        }

        // Smooth scroll fallback for older browsers
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href === '#' || !href.startsWith('#')) return;
                
                e.preventDefault();
                const target = document.querySelector(href);
                
                if (target) {
                    // Close mobile menu if open
                    if (mobileMenu) {
                        mobileMenu.classList.remove('active');
                    }
                    
                    // Smooth scroll to target
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Active link highlighting with IntersectionObserver
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.fn-nav-link[data-section]');

        const observerOptions = {
            root: null,
            rootMargin: '-20% 0px -70% 0px',
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Remove active class from all links
                    navLinks.forEach(link => link.classList.remove('active'));
                    
                    // Add active class to corresponding link
                    const correspondingLink = document.querySelector(
                        `.fn-nav-link[data-section="${entry.target.id}"]`
                    );
                    if (correspondingLink) {
                        correspondingLink.classList.add('active');
                    }
                }
            });
        }, observerOptions);

        // Observe all sections
        sections.forEach(section => {
            observer.observe(section);
        });
    </script>
</body>
</html>