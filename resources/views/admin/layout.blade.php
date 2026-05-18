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
    @php
        $adminAvatarUrl = null;
        $adminAvatarInitial = 'A';

        if ($adminNavUser) {
            $adminAvatarUrl = method_exists($adminNavUser, 'profilePhotoUrl')
                ? $adminNavUser->profilePhotoUrl()
                : null;
            $adminAvatarInitial = method_exists($adminNavUser, 'avatarInitial')
                ? $adminNavUser->avatarInitial()
                : strtoupper(substr(data_get($adminNavUser, 'name', 'A'), 0, 1));
        }
    @endphp
    <div class="admin-page">
        <nav class="admin-navbar">
            <div class="admin-navbar-container">
                <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                    <x-findnest-logo variant="inline" size="sm" />
                    <span class="brand-badge">Admin</span>
                </a>

                <div class="admin-navbar-center">
                    <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('admin.properties.index') }}" class="admin-nav-link {{ request()->routeIs('admin.properties.*') ? 'active' : '' }}">Properties</a>
                    <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
                    <a href="{{ route('admin.owner-applications.index') }}" class="admin-nav-link {{ request()->routeIs('admin.owner-applications.*') ? 'active' : '' }}">Host Applications</a>
                    <a href="{{ route('admin.reviews.index') }}" class="admin-nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">Reviews</a>
                    <a href="{{ route('admin.bookings.index') }}" class="admin-nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">Bookings</a>
                </div>

                <div class="admin-navbar-end">
                    @include('components.notification-dropdown')

                    <details class="admin-profile-menu">
                        <summary class="admin-profile-trigger {{ request()->routeIs('admin.profile.*') ? 'is-active' : '' }}" aria-label="Profile menu">
                            <div class="admin-avatar">
                                @if($adminAvatarUrl)
                                    <img
                                        src="{{ $adminAvatarUrl }}"
                                        alt="{{ data_get($adminNavUser, 'name', 'Admin') }}"
                                        onerror="this.style.display='none'; this.nextElementSibling.removeAttribute('hidden');"
                                    >
                                    <span class="admin-avatar-fallback" hidden>{{ $adminAvatarInitial }}</span>
                                @else
                                    <span class="admin-avatar-fallback">{{ $adminAvatarInitial }}</span>
                                @endif
                            </div>
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

        @php($adminPagebarHidden = trim($__env->yieldContent('hide_pagebar')))
        @if($adminPagebarHidden !== 'true')
            <header class="admin-pagebar">
                <div>
                    @php($adminPageKicker = trim($__env->yieldContent('page_kicker')))
                    @if($adminPageKicker !== '')
                        <p class="page-kicker">{{ $adminPageKicker }}</p>
                    @endif
                    <h1 class="page-title">@yield('page_title', 'Admin Dashboard')</h1>
                </div>

                <div class="admin-pagebar-meta">
                    @yield('page_meta', now()->format('l, F j, Y'))
                </div>
            </header>
        @endif

        <main class="admin-main">
            <section class="admin-content">
                @if (session('success'))
                    <div class="admin-alert admin-alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="admin-alert admin-alert-error">
                        {{ session('error') }}
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
