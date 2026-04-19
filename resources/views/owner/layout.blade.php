<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Owner Dashboard') - FindNest</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/owner.css') }}?v={{ filemtime(public_path('css/owner.css')) }}" rel="stylesheet">
</head>

<body>
    @php
        $ownerLayoutUser = null;

        try {
            if (auth()->check()) {
                $ownerLayoutUser = auth()->user();
            }
        } catch (\Throwable $e) {
            $ownerLayoutUser = null;
        }
    @endphp
    <nav class="owner-navbar">
        <div class="owner-navbar-container">
            <a href="{{ route('owner.dashboard') }}" class="owner-brand">
                <x-findnest-logo variant="inline" size="sm" />
                <span class="owner-brand-badge">Owner</span>
            </a>

            <div class="owner-navbar-center">
                <a href="{{ route('owner.dashboard') }}" class="owner-nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('owner.listings.index') }}" class="owner-nav-link {{ request()->routeIs('owner.listings.index') ? 'active' : '' }}">Properties</a>
                <a href="{{ route('owner.listings.create') }}" class="owner-nav-link {{ request()->routeIs('owner.listings.create') ? 'active' : '' }}">Add Property</a>
                <a href="{{ route('owner.bookings.index') }}" class="owner-nav-link {{ request()->routeIs('owner.bookings.*') ? 'active' : '' }}">Booking Requests</a>
                <a href="{{ route('owner.reviews.index') }}" class="owner-nav-link {{ request()->routeIs('owner.reviews.*') ? 'active' : '' }}">Reviews</a>
            </div>

            <div class="owner-navbar-end">
                @include('components.notification-dropdown')

                <a href="{{ route('owner.messages.index') }}" class="owner-message-link {{ request()->routeIs('owner.messages.*') || request()->routeIs('owner.conversations.*') ? 'active' : '' }}" aria-label="Messages">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8m-8 4h5m7 6l-3.5-2H7a4 4 0 01-4-4V8a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-4 4h-1.5L12 20z"></path>
                    </svg>
                    <span class="owner-message-badge" id="owner-unread-badge" hidden>0</span>
                </a>

                <details class="owner-profile-menu">
                    <summary class="owner-profile-trigger" aria-label="Profile menu">
                        @if($ownerLayoutUser && $ownerLayoutUser->profile_photo)
                            <img src="{{ asset('storage/' . $ownerLayoutUser->profile_photo) }}" alt="Profile" class="profile-photo">
                        @else
                            <svg class="owner-profile-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        @endif
                    </summary>

                    <div class="owner-profile-panel" role="menu" aria-label="Profile menu">
                        <a href="{{ route('owner.profile.edit') }}" class="owner-profile-item">Profile</a>

                        <form method="POST" action="{{ route('logout') }}" class="owner-profile-logout-form">
                            @csrf
                            <button type="submit" class="owner-profile-item owner-profile-logout-button">Logout</button>
                        </form>
                    </div>
                </details>
            </div>
        </div>
    </nav>

    <main class="owner-main">
        <div class="owner-content">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
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
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const unreadBadge = document.getElementById('owner-unread-badge');
            if (!unreadBadge) {
                return;
            }

            const refreshUnread = () => {
                fetch('{{ route('owner.conversations.unread-count') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Unable to fetch unread count');
                    }

                    return response.json();
                })
                .then((data) => {
                    const total = Number(data.total_unread || 0);

                    if (total > 0) {
                        unreadBadge.hidden = false;
                        unreadBadge.textContent = total > 99 ? '99+' : String(total);
                    } else {
                        unreadBadge.hidden = true;
                    }
                })
                .catch(() => {
                    // keep UI stable if unread endpoint fails temporarily
                });
            };

            refreshUnread();
            window.setInterval(refreshUnread, 30000);
        });
    </script>

</body>

</html>
