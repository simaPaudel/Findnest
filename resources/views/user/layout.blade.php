<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'User Dashboard') - FindNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* FindNest Theme - CSS Variables */
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
            background: var(--fn-gray);
            color: var(--fn-charcoal);
            min-height: 100vh;
        }

        /* Utility Classes */
        .fn-bg-red { background-color: var(--fn-red); }
        .fn-bg-white { background-color: var(--fn-white); }
        .fn-bg-gray { background-color: var(--fn-gray); }
        .fn-text-red { color: var(--fn-red); }
        .fn-text-charcoal { color: var(--fn-charcoal); }
        .fn-text-white { color: var(--fn-white); }
        .fn-text-gray { color: var(--fn-gray-dark); }
        .fn-border-red { border-color: var(--fn-red); }
        .fn-border-gray { border-color: var(--fn-gray-light); }

        /* Glass Card */
        .fn-glass-card {
            background: var(--fn-white);
            border: 1px solid var(--fn-gray-light);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .fn-glass-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(255, 56, 92, 0.12);
        }

        /* Buttons */
        .fn-btn-primary {
            background: linear-gradient(135deg, var(--fn-red) 0%, #ff1744 100%);
            color: var(--fn-white);
            padding: 12px 28px;
            border-radius: 16px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(255, 56, 92, 0.25);
        }

        .fn-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(255, 56, 92, 0.35);
        }

        .fn-btn-secondary {
            background: transparent;
            color: var(--fn-charcoal);
            padding: 10px 24px;
            border-radius: 16px;
            font-weight: 500;
            border: 2px solid var(--fn-gray-light);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .fn-btn-secondary:hover {
            border-color: var(--fn-red);
            background: rgba(255, 56, 92, 0.05);
            color: var(--fn-red);
        }

        /* Badge */
        .fn-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 16px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .fn-badge-red { background: rgba(255, 56, 92, 0.1); color: var(--fn-red); }
        .fn-badge-green { background: rgba(16, 185, 129, 0.1); color: #10B981; }
        .fn-badge-yellow { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }
        .fn-badge-gray { background: var(--fn-gray-light); color: var(--fn-gray-dark); }

        /* Sidebar */
        .fn-sidebar {
            background: var(--fn-white);
            border-right: 1px solid var(--fn-gray-light);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.04);
        }

        .fn-sidebar-item {
            padding: 14px 24px;
            margin: 6px 12px;
            border-radius: 16px;
            color: var(--fn-charcoal);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .fn-sidebar-item:hover,
        .fn-sidebar-item.active {
            background: rgba(255, 56, 92, 0.08);
            color: var(--fn-red);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--fn-gray); }
        ::-webkit-scrollbar-thumb { background: var(--fn-gray-dark); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--fn-red); }

        /* Alert */
        .fn-alert {
            padding: 16px 20px;
            border-radius: 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .fn-alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .fn-alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        @media (max-width: 1024px) {
            .fn-sidebar {
                position: fixed;
                left: -100%;
                top: 0;
                height: 100vh;
                width: 280px;
                z-index: 1000;
                transition: left 0.3s ease;
            }
            .fn-sidebar.open { left: 0; }
        }
    </style>
    @stack('styles')
</head>

<body class="fn-bg-gray">
    <div class="flex min-h-screen">
        <!-- Sidebar Navigation -->
        <aside class="fn-sidebar w-72 hidden lg:block fixed h-full">
            <div class="p-8">
                <h1 class="text-2xl font-bold fn-text-red mb-2">FindNest</h1>
                <p class="text-sm fn-text-gray">Housing Platform</p>
            </div>

            <nav class="mt-8">
                <a href="{{ route('user.dashboard') }}" class="fn-sidebar-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('listings.index') }}" class="fn-sidebar-item">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span>Find Listings</span>
                </a>
                <a href="{{ route('user.roommate-preferences.edit') }}" class="fn-sidebar-item {{ request()->routeIs('user.roommate*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Roommate Match</span>
                </a>
                <a href="{{ route('user.saved-listings.index') }}" class="fn-sidebar-item {{ request()->routeIs('user.saved*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span>Saved Listings</span>
                </a>
                <a href="{{ route('user.bookings.index') }}" class="fn-sidebar-item {{ request()->routeIs('user.bookings*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>My Bookings</span>
                </a>
                <a href="{{ route('user.profile.edit') }}" class="fn-sidebar-item {{ request()->routeIs('user.profile*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Profile</span>
                </a>
            </nav>

            <div class="absolute bottom-8 left-0 right-0 px-8">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="fn-sidebar-item w-full justify-start hover:bg-red-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-72">
            <!-- Header -->
            <header class="fn-bg-white border-b fn-border-gray sticky top-0 z-50 backdrop-blur-md bg-opacity-95">
                <div class="px-6 lg:px-12 py-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold fn-text-charcoal">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-sm fn-text-gray mt-0.5">Welcome back, {{ auth()->user()->name }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <!-- Notification Bell -->
                        <button class="relative p-2 hover:bg-gray-100 rounded-xl transition">
                            <svg class="w-6 h-6 fn-text-charcoal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <div class="px-6 lg:px-12 pt-6">
                @if(session('success'))
                    <div class="fn-alert fn-alert-success">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="fn-alert fn-alert-error">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
            </div>

            <!-- Content -->
            <div class="p-6 lg:px-12 lg:pb-12">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>

</html>
