<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - FindNest</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    @php
        $adminNavUser = null;

        try {
            if (auth()->check()) {
                $adminNavUser = auth()->user();
            }
        } catch (\Throwable $e) {
            $adminNavUser = null;
        }
    @endphp
    <div class="admin-page">
        <nav class="admin-navbar">
            <div class="admin-navbar-container">
                <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                    <span class="brand-mark">FN</span>
                    <span>
                        <span class="brand-name">FindNest</span>
                        <span class="brand-badge">Admin</span>
                    </span>
                </a>

                <div class="admin-navbar-center">
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('admin.properties.index') }}" class="admin-nav-link {{ request()->routeIs('admin.properties.*') ? 'active' : '' }}">Properties</a>
                    <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
                    <a href="{{ route('admin.reviews.index') }}" class="admin-nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">Reviews</a>
                    <a href="{{ route('admin.bookings.index') }}" class="admin-nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">Bookings</a>
                </div>

                <div class="admin-navbar-end">
                    @include('components.notification-dropdown')

                    <a href="{{ route('admin.reports.index') }}" class="admin-message-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" title="Reports" aria-label="Reports">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 14a2 2 0 01-2 2H8l-4 4v-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8z"></path>
                        </svg>
                    </a>

                    <details class="admin-profile-menu">
                        <summary class="admin-profile-trigger {{ request()->routeIs('admin.profile.*') ? 'is-active' : '' }}" aria-label="Profile menu">
                            <div class="admin-avatar">
                                {{ strtoupper(substr(data_get($adminNavUser, 'name', 'A'), 0, 1)) }}
                            </div>
                            <svg class="admin-profile-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </summary>

                        <div class="admin-profile-panel" role="menu" aria-label="Profile menu">
                            <a href="{{ route('admin.profile.edit') }}" class="admin-profile-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">Profile</a>
                            <form method="POST" action="{{ route('logout') }}" class="admin-profile-logout-form">
                                @csrf
                                <button type="submit" class="admin-profile-item admin-profile-logout-button">Logout</button>
                            </form>
                        </div>
                    </details>
                </div>
            </div>
        </nav>

        <header class="admin-pagebar">
            <div>
                <p class="page-kicker">@yield('page_kicker', 'Control Center')</p>
                <h1 class="page-title">@yield('page_title', 'Admin Dashboard')</h1>
            </div>

            <div class="admin-pagebar-meta">
                @yield('page_meta', now()->format('l, F j, Y'))
            </div>
        </header>

        <main class="admin-main">
            <section class="admin-content">
                @if (session('success'))
                    <div class="admin-alert admin-alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="admin-alert admin-alert-error">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </section>
        </main>
    </div>
</body>
</html>
