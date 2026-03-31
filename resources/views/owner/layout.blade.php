<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Owner Dashboard') - FindNest</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/owner.css') }}" rel="stylesheet">
</head>

<body>
    <div class="owner-layout">
        <!-- Sidebar -->
        <aside class="owner-sidebar">
            <div class="sidebar-header">
                <a href="{{ url('/') }}" class="logo">
                    <svg class="logo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="logo-text">FindNest</span>
                    <span class="logo-badge">Owner</span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('owner.dashboard') }}" class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>

                <a href="{{ route('owner.listings.index') }}" class="nav-link {{ request()->routeIs('owner.listings.index') ? 'active' : '' }}">
                    My Listings
                </a>

                <a href="{{ route('owner.listings.create') }}" class="nav-link {{ request()->routeIs('owner.listings.create') ? 'active' : '' }}">
                    Add Listing
                </a>

                <a href="{{ route('owner.bookings.index') }}" class="nav-link {{ request()->routeIs('owner.bookings.*') ? 'active' : '' }}">
                    Booking Requests
                </a>

                <a href="{{ route('owner.reviews.index') }}" class="nav-link {{ request()->routeIs('owner.reviews.*') ? 'active' : '' }}">
                    Reviews
                </a>

                <a href="{{ route('owner.profile.edit') }}" class="nav-link {{ request()->routeIs('owner.profile.*') ? 'active' : '' }}">
                    Profile
                </a>

                <div class="nav-divider"></div>

                <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
                    @csrf
                    <button type="submit" class="nav-link">
                        Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="owner-main">
            <!-- Top Bar -->
            <header class="owner-topbar">
                <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>

                <div class="topbar-actions">

                    <div class="profile-chip">
                        @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" class="profile-photo">
                        @else
                        <div class="profile-initials">{{ substr(auth()->user()->name, 0, 1) }}</div>
                        @endif
                        <span class="profile-name">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="owner-content">
                @if(session('success'))
                <div class="alert alert-success">
                    <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-error">
                    <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <strong>There were some errors with your submission:</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>