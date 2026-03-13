<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Listings - FindNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
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
            transition: all 0.3s ease;
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
            transition: all 0.4s ease;
            box-shadow: 0 4px 20px rgba(255, 56, 92, 0.25);
            display: inline-block;
            text-decoration: none;
        }

        .fn-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 32px rgba(255, 56, 92, 0.35);
        }

        .fn-btn-secondary {
            background: transparent;
            color: var(--fn-charcoal);
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 500;
            border: 2px solid var(--fn-gray-light);
            cursor: pointer;
            transition: all 0.3s ease;
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
            transition: all 0.4s ease;
        }

        .fn-glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 48px rgba(255, 56, 92, 0.12);
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

        .fn-badge-green {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .fn-badge-gray {
            background: rgba(107, 114, 128, 0.15);
            color: var(--fn-gray-dark);
            border: 1px solid rgba(107, 114, 128, 0.3);
        }

        /* Search Input */
        .fn-search-input {
            background: var(--fn-gray);
            border: 1px solid transparent;
            border-radius: 14px;
            padding: 14px 20px;
            color: var(--fn-charcoal);
            outline: none;
            transition: all 0.3s ease;
            width: 100%;
        }

        .fn-search-input:focus {
            border-color: var(--fn-red);
            background: var(--fn-white);
            box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.1);
        }

        .fn-image-overlay {
            background: linear-gradient(180deg, rgba(31, 41, 55, 0) 0%, rgba(31, 41, 55, 0.9) 100%);
        }

        .property-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            cursor: pointer;
        }

        .property-card img {
            transition: transform 0.6s ease;
        }

        .property-card:hover img {
            transform: scale(1.08);
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
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold fn-text-charcoal mb-4">Browse Properties</h1>
                <p class="fn-text-gray text-lg">Find your perfect accommodation from our verified listings</p>
            </div>

            <!-- Search & Filters -->
            <div class="fn-glass-card p-6 mb-8">
                <form action="{{ route('listings.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium fn-text-charcoal mb-2">Location</label>
                            <input type="text" name="q" placeholder="City, area, or keyword..." class="fn-search-input" value="{{ request('q') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium fn-text-charcoal mb-2">Max Price</label>
                            <input type="number" name="max_price" placeholder="e.g., 50000" class="fn-search-input" value="{{ request('max_price') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium fn-text-charcoal mb-2">Room Type</label>
                            <select name="room_type" class="fn-search-input">
                                <option value="">All Types</option>
                                <option value="single" {{ request('room_type') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="shared" {{ request('room_type') == 'shared' ? 'selected' : '' }}>Shared</option>
                                <option value="flat" {{ request('room_type') == 'flat' ? 'selected' : '' }}>Flat</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4 items-center">
                        <button type="submit" class="fn-btn-primary">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search
                        </button>
                        @if(request()->hasAny(['q', 'max_price', 'room_type', 'sort']))
                            <a href="{{ route('listings.index') }}" class="fn-btn-secondary">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear Filters
                            </a>
                        @endif
                        <div class="ml-auto">
                            <select name="sort" class="fn-search-input" onchange="this.form.submit()">
                                <option value="">Sort By</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Results Count -->
            <div class="mb-6 flex items-center justify-between">
                <p class="fn-text-gray">
                    Found <span class="font-semibold fn-text-charcoal">{{ $properties->total() }}</span> {{ $properties->total() == 1 ? 'property' : 'properties' }}
                    @if(request('q'))
                        matching "<span class="font-semibold fn-text-red">{{ request('q') }}</span>"
                    @endif
                </p>
            </div>

            <!-- Property Grid -->
            @if($properties->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach($properties as $property)
                        @php
                            $photos = is_array($property->photos) ? $property->photos : (json_decode($property->photos, true) ?? []);
                            $firstPhoto = $photos[0] ?? null;
                        @endphp
                        <a href="{{ route('listings.show', $property) }}" class="fn-glass-card overflow-hidden property-card">
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
                                        <span class="fn-badge fn-badge-green">Verified</span>
                                    @endif
                                </div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h3 class="text-xl font-bold fn-text-white mb-1">{{ $property->title }}</h3>
                                    <p class="text-sm fn-text-white opacity-90 mb-2">{{ $property->city ?? $property->location }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-2xl font-bold fn-text-white">@npr($property->rent_price)/mo</span>
                                        @if($property->room_type)
                                            <span class="fn-badge fn-badge-gray text-xs capitalize">{{ $property->room_type }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $properties->links() }}
                </div>
            @else
                <!-- No Results -->
                <div class="fn-glass-card p-12 text-center">
                    <svg class="w-24 h-24 mx-auto fn-text-gray mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h2 class="text-2xl font-bold fn-text-charcoal mb-4">No Properties Found</h2>
                    <p class="fn-text-gray mb-6">Try adjusting your search criteria or clearing filters</p>
                    <a href="{{ route('listings.index') }}" class="fn-btn-primary">View All Properties</a>
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
</body>
</html>
