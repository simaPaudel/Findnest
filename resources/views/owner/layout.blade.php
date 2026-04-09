<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Owner Dashboard') - FindNest</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/owner.css') }}" rel="stylesheet">
</head>

<body>
    <div class="owner-layout">
        <aside class="owner-sidebar">
            <div class="sidebar-header">
                <a href="{{ url('/') }}" class="logo">
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

                <a href="{{ route('owner.messages.index') }}" class="nav-link {{ request()->routeIs('owner.messages.*') || request()->routeIs('owner.conversations.*') ? 'active' : '' }}">
                    Messages
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

        <div class="owner-main">
            <header class="owner-topbar">
                <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>

                <div class="topbar-actions">
                    @include('components.notification-dropdown')

                    <a href="{{ route('owner.messages.index') }}" class="topbar-message-link {{ request()->routeIs('owner.messages.*') || request()->routeIs('owner.conversations.*') ? 'active' : '' }}" title="Messages" aria-label="Messages">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8m-8 4h5m7 6l-3.5-2H7a4 4 0 01-4-4V8a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-4 4h-1.5L12 20z"></path>
                        </svg>
                        <span class="topbar-message-badge" id="owner-unread-badge" hidden>0</span>
                    </a>

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

            <main class="owner-content">
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
            </main>
        </div>
    </div>

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
