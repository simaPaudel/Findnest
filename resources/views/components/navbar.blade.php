<!-- Reusable Navbar Component -->
@php
    $navbarUser = null;
    $navbarHomeRoute = route('home');
    $navbarRole = 'guest';
    $navbarCenterLinks = [];
    $navbarSecondaryLink = null;
    $navbarProfileRoute = route('login');
    $navbarAvatarUrl = null;
    $navbarShowNotifications = false;

    try {
        if (auth()->check()) {
            $navbarUser = auth()->user();
        }
    } catch (\Throwable $e) {
        $navbarUser = null;
    }

    $resolveAvatarUrl = function (?string $path): ?string {
        if (! $path) {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        if (\Illuminate\Support\Str::startsWith($path, 'storage/')) {
            return asset($path);
        }

        if (\Illuminate\Support\Str::startsWith($path, 'profiles/')) {
            return asset('storage/' . ltrim($path, '/'));
        }

        return asset('storage/' . ltrim($path, '/'));
    };

    if ($navbarUser) {
        $navbarAvatarUrl = method_exists($navbarUser, 'profilePhotoUrl')
            ? $navbarUser->profilePhotoUrl()
            : $resolveAvatarUrl(data_get($navbarUser, 'profile_photo'));
        $navbarShowNotifications = true;
        if ($navbarUser->isUser()) {
            $navbarRole = 'user';
            $navbarHomeRoute = route('user.dashboard');
            $navbarProfileRoute = route('user.profile.edit');
            $navbarCenterLinks = [
                ['label' => 'Find Listings', 'url' => route('listings.index'), 'active' => request()->routeIs('listings.index')],
                ['label' => 'Find Roommates', 'url' => route('roommates.index'), 'active' => request()->routeIs('roommates.*', 'user.roommate*')],
                ['label' => 'Saved', 'url' => route('user.saved-listings.index'), 'active' => request()->routeIs('user.saved*')],
                ['label' => 'My Bookings', 'url' => route('user.bookings.index'), 'active' => request()->routeIs('user.bookings*')],
            ];
            $navbarSecondaryLink = [
                'title' => 'Messages',
                'url' => route('user.messages.index'),
                'active' => request()->routeIs('user.messages.*'),
                'icon' => 'message',
            ];
        } elseif ($navbarUser->isOwner()) {
            $navbarRole = 'owner';
            $navbarHomeRoute = route('owner.dashboard');
            $navbarProfileRoute = route('owner.profile.edit');
            $navbarCenterLinks = [
                ['label' => 'Dashboard', 'url' => route('owner.dashboard'), 'active' => request()->routeIs('owner.dashboard')],
                ['label' => 'Properties', 'url' => route('owner.listings.index'), 'active' => request()->routeIs('owner.listings.index')],
                ['label' => 'Add Property', 'url' => route('owner.listings.create'), 'active' => request()->routeIs('owner.listings.create')],
                ['label' => 'Booking Requests', 'url' => route('owner.bookings.index'), 'active' => request()->routeIs('owner.bookings.*')],
                ['label' => 'Reviews', 'url' => route('owner.reviews.index'), 'active' => request()->routeIs('owner.reviews.*')],
            ];
            $navbarSecondaryLink = [
                'title' => 'Messages',
                'url' => route('owner.messages.index'),
                'active' => request()->routeIs('owner.messages.*', 'owner.conversations.*'),
                'icon' => 'message',
            ];
        } elseif ($navbarUser->isAdmin()) {
            $navbarRole = 'admin';
            $navbarHomeRoute = route('admin.dashboard');
            $navbarProfileRoute = route('admin.profile.edit');
            $navbarCenterLinks = [
                ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')],
                ['label' => 'Properties', 'url' => route('admin.properties.index'), 'active' => request()->routeIs('admin.properties.*')],
                ['label' => 'Users', 'url' => route('admin.users.index'), 'active' => request()->routeIs('admin.users.*')],
                ['label' => 'Host Applications', 'url' => route('admin.owner-applications.index'), 'active' => request()->routeIs('admin.owner-applications.*')],
                ['label' => 'Reviews', 'url' => route('admin.reviews.index'), 'active' => request()->routeIs('admin.reviews.*')],
                ['label' => 'Bookings', 'url' => route('admin.bookings.index'), 'active' => request()->routeIs('admin.bookings.*')],
            ];
            $navbarSecondaryLink = [
                'title' => 'Reports',
                'url' => route('admin.reports.index'),
                'active' => request()->routeIs('admin.reports.*'),
                'icon' => 'reports',
            ];
        }
    } else {
        $navbarCenterLinks = [
            ['label' => 'Browse Listings', 'url' => route('home') . '#featured', 'active' => false],
            ['label' => 'How It Works', 'url' => route('home') . '#how-it-works', 'active' => false],
            ['label' => 'Find Roommates', 'url' => route('home') . '#roommates', 'active' => false],
        ];
    }
@endphp
<nav class="fn-navbar">
    <div class="fn-navbar-container">
        <!-- Logo - LEFT CORNER -->
        <a href="{{ $navbarHomeRoute }}" class="fn-navbar-brand">
            <x-findnest-logo variant="inline" size="sm" />
        </a>

        <!-- Center Navigation -->
        <div class="fn-navbar-center">
            @foreach($navbarCenterLinks as $navLink)
                <a href="{{ $navLink['url'] }}" class="fn-nav-link {{ $navLink['active'] ? 'active' : '' }}">
                    {{ $navLink['label'] }}
                </a>
            @endforeach
        </div>

        <!-- Right Section - RIGHT CORNER -->
        <div class="fn-navbar-end">
            @if($navbarUser)
                @if($navbarShowNotifications)
                    @include('components.notification-dropdown')
                @endif

                @if($navbarSecondaryLink)
                    <a href="{{ $navbarSecondaryLink['url'] }}" class="fn-message-link {{ $navbarSecondaryLink['active'] ? 'active' : '' }}" title="{{ $navbarSecondaryLink['title'] }}" aria-label="{{ $navbarSecondaryLink['title'] }}">
                        @if($navbarSecondaryLink['icon'] === 'reports')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8m-8 4h5m7 6l-3.5-2H7a4 4 0 01-4-4V8a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-4 4h-1.5L12 20z"></path>
                            </svg>
                        @else
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8m-8 4h5m7 6l-3.5-2H7a4 4 0 01-4-4V8a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-4 4h-1.5L12 20z"></path>
                            </svg>
                        @endif
                        @if($navbarRole === 'user')
                            <span class="fn-message-badge" id="fn-user-unread-badge" hidden>0</span>
                        @endif
                    </a>
                @endif

                <details class="fn-profile-menu">
                    <summary class="fn-profile-avatar" title="Profile" aria-label="Profile menu">
                        @if($navbarAvatarUrl)
                            <img
                                src="{{ $navbarAvatarUrl }}"
                                alt="{{ $navbarUser->name }}"
                                onerror="this.style.display='none';"
                            />
                        @endif
                    </summary>

                    <div class="fn-profile-panel" role="menu" aria-label="Profile menu">
                        <a href="{{ $navbarProfileRoute }}" class="fn-profile-item">Profile</a>

                        <form method="POST" action="{{ route('logout') }}" class="fn-profile-logout-form">
                            @csrf
                            <button type="submit" class="fn-profile-item fn-profile-logout-button">Logout</button>
                        </form>
                    </div>
                </details>
            @else
                <a href="{{ route('login') }}" class="fn-nav-link">Login</a>
                <a href="{{ route('register') }}" class="fn-btn-primary">Get Started</a>
            @endif
        </div>
    </div>
</nav>

<style>
    html {
        width: 100%;
        overflow-x: hidden;
        -webkit-text-size-adjust: 100%;
    }

    body {
        width: 100%;
        overflow-x: hidden;
    }

    img,
    video,
    canvas,
    svg {
        max-width: 100%;
    }

    img,
    video {
        height: auto;
    }

    input,
    select,
    textarea,
    button {
        max-width: 100%;
        font: inherit;
    }

    table {
        max-width: 100%;
    }

    [class*="table-wrap"],
    [class*="table-responsive"] {
        max-width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .line-clamp-1,
    .line-clamp-2 {
        overflow-wrap: anywhere;
    }

    /* Navbar Styling */
    .fn-navbar {
        background: var(--fn-white, #ffffff);
        border-bottom: 1px solid var(--fn-gray-border, #e5e7eb);
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .fn-navbar-container {
        display: flex;
        align-items: center;
        width: 100%;
        max-width: 1520px;
        margin: 0 auto;
        padding: 0.95rem 1.9rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .fn-navbar-brand {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--fn-red, #ff385c);
        text-decoration: none;
        flex-shrink: 0;
        margin-left: 0;
        transition: all 0.2s ease;
    }

    .fn-navbar-brand:hover {
        opacity: 0.8;
    }

    .fn-navbar-brand svg {
        width: 2rem;
        height: 2rem;
    }

    .fn-navbar-center {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex: 1;
        justify-content: center;
        min-width: 0;
    }

    .fn-nav-link {
        color: var(--fn-charcoal, #1f2937);
        text-decoration: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        border: none;
        background: none;
        cursor: pointer;
    }

    .fn-nav-link:hover {
        color: var(--fn-red, #ff385c);
        background: transparent;
    }

    .fn-nav-link.active {
        color: var(--fn-red, #ff385c);
        background: transparent;
    }

    .fn-navbar-end {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-shrink: 0;
        margin-left: auto;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .fn-message-link {
        position: relative;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 1px solid var(--fn-gray-border, #e5e7eb);
        background: #fff;
        color: var(--fn-charcoal, #1f2937);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .fn-message-link svg {
        width: 1.05rem;
        height: 1.05rem;
    }

    .fn-message-link:hover,
    .fn-message-link.active {
        border-color: var(--fn-gray-border, #e5e7eb);
        color: var(--fn-red, #ff385c);
        background: #fff;
    }

    .fn-message-badge {
        position: absolute;
        top: -5px;
        right: -4px;
        min-width: 17px;
        height: 17px;
        border-radius: 999px;
        background: var(--fn-red, #ff385c);
        color: #fff;
        font-size: 0.66rem;
        font-weight: 700;
        line-height: 17px;
        text-align: center;
        padding: 0 4px;
        border: 2px solid #fff;
    }

        .fn-profile-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--fn-gray-light, #f3f4f6);
            border: 1px solid var(--fn-gray-border, #e5e7eb);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--fn-charcoal, #1f2937);
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

    .fn-profile-avatar:hover {
        border-color: var(--fn-red, #ff385c);
        color: var(--fn-red, #ff385c);
    }

    .fn-profile-avatar svg {
        width: 1.05rem;
        height: 1.05rem;
    }

        .fn-profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .fn-profile-menu {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .fn-profile-menu > summary {
            list-style: none;
        }

        .fn-profile-menu > summary::-webkit-details-marker {
            display: none;
        }

        .fn-profile-menu[open] .fn-profile-avatar,
        .fn-profile-menu .fn-profile-avatar:hover {
            border-color: var(--fn-red, #ff385c);
            color: var(--fn-red, #ff385c);
        }

        .fn-profile-panel {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 170px;
            padding: 8px;
            border: 1px solid var(--fn-gray-border, #e5e7eb);
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
            z-index: 1200;
        }

        .fn-profile-logout-form {
            margin: 0;
        }

        .fn-profile-item {
            display: flex;
            width: 100%;
            padding: 10px 12px;
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: var(--fn-charcoal, #1f2937);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            text-align: left;
            cursor: pointer;
            transition: background 0.18s ease, color 0.18s ease;
        }

        .fn-profile-item:hover {
            background: transparent;
            color: var(--fn-red, #ff385c);
        }

        .fn-profile-logout-button {
            color: #be123c;
        }

    .fn-btn-primary {
        background: var(--fn-red, #ff385c);
        color: var(--fn-white, #ffffff);
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .fn-btn-primary:hover {
        background: var(--fn-red-hover, #e11d48);
        box-shadow: 0 4px 12px rgba(255, 56, 92, 0.3);
    }

    @media (max-width: 768px) {
        .fn-navbar-container {
            padding: 0.85rem 1rem;
            gap: 0.7rem;
        }

        .fn-navbar-brand {
            font-size: 1.2rem;
            gap: 0.4rem;
            margin-left: 0;
        }

        .fn-navbar-brand svg {
            width: 1.75rem;
            height: 1.75rem;
        }

        .fn-navbar-center {
            order: 3;
            flex-basis: 100%;
            justify-content: flex-start;
            gap: 0.35rem;
            padding-top: 0.25rem;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .fn-navbar-center::-webkit-scrollbar {
            display: none;
        }

        .fn-navbar-end {
            margin-left: auto;
            gap: 0.35rem;
            max-width: calc(100vw - 120px);
        }

        .fn-nav-link {
            white-space: nowrap;
            padding: 0.45rem 0.75rem;
            font-size: 0.88rem;
        }

        .fn-btn-primary {
            padding: 0.55rem 0.95rem;
        }

        .fn-message-link,
        .fn-profile-avatar {
            width: 34px;
            height: 34px;
        }

        .fn-profile-panel {
            right: -4px;
            width: min(220px, calc(100vw - 24px));
        }
    }

    @media (max-width: 420px) {
        .fn-navbar-container {
            padding-inline: 0.75rem;
        }

        .fn-navbar-brand > span {
            max-width: 118px;
        }

        .fn-navbar-end {
            max-width: calc(100vw - 104px);
        }

        .fn-navbar-end > .fn-nav-link {
            padding-inline: 0.55rem;
        }

        .fn-navbar-end > .fn-btn-primary {
            padding-inline: 0.7rem;
            font-size: 0.84rem;
        }

        .fn-navbar-center {
            margin-inline: -0.75rem;
            padding-inline: 0.75rem;
        }
    }
</style>

@if($navbarUser)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const idleTimeoutMs = {{ max(1, (int) config('session.lifetime', 120)) * 60 * 1000 }};
            const activityEvents = ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click'];
            let timeoutId = null;
            let hasTimedOut = false;

            const logoutForms = [
                '.fn-profile-logout-form',
                '.owner-profile-logout-form',
                '.admin-profile-logout-form',
            ];

            const findLogoutForm = () => {
                for (const selector of logoutForms) {
                    const form = document.querySelector(selector);
                    if (form) {
                        return form;
                    }
                }

                return null;
            };

            const triggerLogout = () => {
                if (hasTimedOut) {
                    return;
                }

                hasTimedOut = true;

                const form = findLogoutForm();

                if (form) {
                    let reasonInput = form.querySelector('input[name="logout_reason"]');

                    if (!reasonInput) {
                        reasonInput = document.createElement('input');
                        reasonInput.type = 'hidden';
                        reasonInput.name = 'logout_reason';
                        form.appendChild(reasonInput);
                    }

                    reasonInput.value = 'session_expired';
                    form.submit();
                    return;
                }

                window.location.href = '{{ route('login') }}';
            };

            const resetTimer = () => {
                if (hasTimedOut) {
                    return;
                }

                if (timeoutId) {
                    clearTimeout(timeoutId);
                }

                timeoutId = window.setTimeout(triggerLogout, idleTimeoutMs);
            };

            activityEvents.forEach((eventName) => {
                window.addEventListener(eventName, resetTimer, { passive: true });
            });

            resetTimer();
        });
    </script>
    @if($navbarUser && $navbarUser->isUser())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const unreadBadge = document.getElementById('fn-user-unread-badge');
                if (!unreadBadge) {
                    return;
                }

                const refreshUnread = () => {
                    fetch('{{ route('user.conversations.unread-count') }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error('Unable to load unread count');
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
                        // keep silent; this should not block nav rendering
                    });
                };

                refreshUnread();
                window.setInterval(refreshUnread, 30000);
            });
        </script>
    @endif
@endif
