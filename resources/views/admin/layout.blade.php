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
    <div class="admin-shell">
        <aside class="admin-sidebar" id="admin-sidebar" data-admin-sidebar>
            <div class="admin-brand">
                <a href="{{ route('admin.dashboard') }}" class="brand-link">
                    <span class="brand-mark">FN</span>
                    <span>
                        <span class="brand-name">FindNest</span>
                        <span class="brand-badge">Admin</span>
                    </span>
                </a>
            </div>

            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.properties.index') }}" class="admin-nav-link {{ request()->routeIs('admin.properties.*') ? 'active' : '' }}">
                    <span>Properties</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span>Users</span>
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="admin-nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <span>Reviews</span>
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="admin-nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                    <span>Bookings</span>
                </a>
            </nav>

            <div class="admin-sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="admin-nav-link admin-nav-link-button">
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="admin-backdrop" data-admin-backdrop hidden></div>

        <main class="admin-main">
            <header class="admin-topbar">
                <div class="admin-topbar-heading">
                    <button type="button" class="admin-menu-toggle" data-admin-menu-toggle aria-controls="admin-sidebar" aria-expanded="false" aria-label="Toggle navigation">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <div>
                    <p class="page-kicker">Control Center</p>
                    <h1 class="page-title">@yield('page_title', 'Admin Dashboard')</h1>
                    </div>
                </div>

                <div class="admin-topbar-actions">
                    @include('components.notification-dropdown')

                    <div class="admin-profile">
                        <div class="admin-avatar">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <p class="admin-profile-name">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="admin-profile-role">Administrator</p>
                        </div>
                    </div>
                </div>
            </header>

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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('[data-admin-sidebar]');
            const backdrop = document.querySelector('[data-admin-backdrop]');
            const toggle = document.querySelector('[data-admin-menu-toggle]');

            if (!sidebar || !backdrop || !toggle) {
                return;
            }

            const setOpen = (open) => {
                sidebar.classList.toggle('is-open', open);
                backdrop.hidden = !open;
                backdrop.classList.toggle('is-open', open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                document.body.classList.toggle('admin-sidebar-open', open);
            };

            toggle.addEventListener('click', function () {
                setOpen(!sidebar.classList.contains('is-open'));
            });

            backdrop.addEventListener('click', function () {
                setOpen(false);
            });

            sidebar.querySelectorAll('a, button[type="submit"]').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth <= 860) {
                        setOpen(false);
                    }
                });
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth > 860) {
                    setOpen(false);
                }
            });
        });
    </script>
</body>
</html>
