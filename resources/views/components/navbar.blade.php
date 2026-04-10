<!-- Reusable Navbar Component -->
<nav class="fn-navbar">
    <div class="fn-navbar-container">
        <!-- Logo - LEFT CORNER -->
        <a href="@auth{{ route('user.dashboard') }}@else{{ route('home') }}@endauth" class="fn-navbar-brand">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            FindNest
        </a>

        <!-- Center Navigation -->
        <div class="fn-navbar-center">
            @auth
                <a href="{{ route('listings.index') }}" class="fn-nav-link {{ request()->routeIs('listings.index') ? 'active' : '' }}">Find Listings</a>
                <a href="{{ route('user.roommate-preferences.edit') }}" class="fn-nav-link {{ request()->routeIs('user.roommate*') ? 'active' : '' }}">Find Roommates</a>
                <a href="{{ route('user.saved-listings.index') }}" class="fn-nav-link {{ request()->routeIs('user.saved*') ? 'active' : '' }}">Saved</a>
                <a href="{{ route('user.bookings.index') }}" class="fn-nav-link {{ request()->routeIs('user.bookings*') ? 'active' : '' }}">My Bookings</a>
            @else
                <a href="{{ route('home') }}#featured" class="fn-nav-link">Browse Listings</a>
                <a href="{{ route('home') }}#how-it-works" class="fn-nav-link">How It Works</a>
                <a href="{{ route('home') }}#roommates" class="fn-nav-link">Find Roommates</a>
            @endauth
        </div>

        <!-- Right Section - RIGHT CORNER -->
        <div class="fn-navbar-end">
            @auth
                @include('components.notification-dropdown')

                @if(auth()->user()->isUser())
                    <a href="{{ route('user.messages.index') }}" class="fn-message-link {{ request()->routeIs('user.messages.*') ? 'active' : '' }}" title="Messages" aria-label="Messages">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8m-8 4h5m7 6l-3.5-2H7a4 4 0 01-4-4V8a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-4 4h-1.5L12 20z"></path>
                        </svg>
                        <span class="fn-message-badge" id="fn-user-unread-badge" hidden>0</span>
                    </a>
                @endif

                <a href="{{ route('user.profile.edit') }}" class="fn-profile-avatar" title="Profile">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset(auth()->user()->profile_photo) }}" alt="{{ auth()->user()->name }}" />
                    @else
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    @endif
                </a>
            @else
                <a href="{{ route('login') }}" class="fn-nav-link">Login</a>
                <a href="{{ route('register') }}" class="fn-btn-primary">Get Started</a>
            @endauth
        </div>
    </div>
</nav>

<style>
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
        padding: 1rem 1.5rem;
        max-width: 100%;
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
        gap: 0.5rem;
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

    .fn-nav-link:hover,
    .fn-nav-link.active {
        color: var(--fn-red, #ff385c);
        background: rgba(255, 56, 92, 0.05);
    }

    .fn-navbar-end {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
        margin-left: auto;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .fn-message-link {
        position: relative;
        width: 36px;
        height: 36px;
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
        width: 1.15rem;
        height: 1.15rem;
    }

    .fn-message-link:hover,
    .fn-message-link.active {
        border-color: rgba(255, 56, 92, 0.25);
        color: var(--fn-red, #ff385c);
        background: rgba(255, 56, 92, 0.06);
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
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--fn-gray-light, #f3f4f6);
        border: 1px solid var(--fn-gray-border, #e5e7eb);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--fn-charcoal, #1f2937);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .fn-profile-avatar:hover {
        border-color: var(--fn-red, #ff385c);
        color: var(--fn-red, #ff385c);
    }

    .fn-profile-avatar svg {
        width: 1.25rem;
        height: 1.25rem;
    }

    .fn-profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
        }

        .fn-navbar-brand {
            font-size: 1.2rem;
            gap: 0.4rem;
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
    }
</style>

@auth
    @if(auth()->user()->isUser())
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
@endauth
