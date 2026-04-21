<!-- Reusable Navbar Component -->
<?php
    $navbarUser = null;
    $navbarHomeRoute = route('home');
    $navbarRole = 'guest';
    $navbarCenterLinks = [];
    $navbarSecondaryLink = null;
    $navbarProfileRoute = route('login');
    $navbarAvatarUrl = null;
    $navbarProfileFallbackInitial = 'A';
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
        $navbarProfileFallbackInitial = method_exists($navbarUser, 'avatarInitial')
            ? $navbarUser->avatarInitial()
            : strtoupper(substr(data_get($navbarUser, 'name', 'A'), 0, 1));

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
?>
<nav class="fn-navbar">
    <div class="fn-navbar-container">
        <!-- Logo - LEFT CORNER -->
        <a href="<?php echo e($navbarHomeRoute); ?>" class="fn-navbar-brand">
            <?php if (isset($component)) { $__componentOriginal343e84183e8c00ed9639e7134ef5492a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal343e84183e8c00ed9639e7134ef5492a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.findnest-logo','data' => ['variant' => 'inline','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('findnest-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'inline','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal343e84183e8c00ed9639e7134ef5492a)): ?>
<?php $attributes = $__attributesOriginal343e84183e8c00ed9639e7134ef5492a; ?>
<?php unset($__attributesOriginal343e84183e8c00ed9639e7134ef5492a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal343e84183e8c00ed9639e7134ef5492a)): ?>
<?php $component = $__componentOriginal343e84183e8c00ed9639e7134ef5492a; ?>
<?php unset($__componentOriginal343e84183e8c00ed9639e7134ef5492a); ?>
<?php endif; ?>
        </a>

        <!-- Center Navigation -->
        <div class="fn-navbar-center">
            <?php $__currentLoopData = $navbarCenterLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $navLink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($navLink['url']); ?>" class="fn-nav-link <?php echo e($navLink['active'] ? 'active' : ''); ?>">
                    <?php echo e($navLink['label']); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Right Section - RIGHT CORNER -->
        <div class="fn-navbar-end">
            <?php if($navbarUser): ?>
                <?php if($navbarShowNotifications): ?>
                    <?php echo $__env->make('components.notification-dropdown', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endif; ?>

                <?php if($navbarSecondaryLink): ?>
                    <a href="<?php echo e($navbarSecondaryLink['url']); ?>" class="fn-message-link <?php echo e($navbarSecondaryLink['active'] ? 'active' : ''); ?>" title="<?php echo e($navbarSecondaryLink['title']); ?>" aria-label="<?php echo e($navbarSecondaryLink['title']); ?>">
                        <?php if($navbarSecondaryLink['icon'] === 'reports'): ?>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8m-8 4h5m7 6l-3.5-2H7a4 4 0 01-4-4V8a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-4 4h-1.5L12 20z"></path>
                            </svg>
                        <?php else: ?>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8m-8 4h5m7 6l-3.5-2H7a4 4 0 01-4-4V8a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-4 4h-1.5L12 20z"></path>
                            </svg>
                        <?php endif; ?>
                        <?php if($navbarRole === 'user'): ?>
                            <span class="fn-message-badge" id="fn-user-unread-badge" hidden>0</span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>

                <details class="fn-profile-menu">
                    <summary class="fn-profile-avatar" title="Profile" aria-label="Profile menu">
                        <?php if($navbarAvatarUrl): ?>
                            <img
                                src="<?php echo e($navbarAvatarUrl); ?>"
                                alt="<?php echo e($navbarUser->name); ?>"
                                onerror="this.style.display='none'; this.nextElementSibling.removeAttribute('hidden');"
                            />
                            <span class="fn-profile-avatar-fallback" hidden><?php echo e($navbarProfileFallbackInitial); ?></span>
                        <?php else: ?>
                            <span class="fn-profile-avatar-fallback"><?php echo e($navbarProfileFallbackInitial); ?></span>
                        <?php endif; ?>
                    </summary>

                    <div class="fn-profile-panel" role="menu" aria-label="Profile menu">
                        <a href="<?php echo e($navbarProfileRoute); ?>" class="fn-profile-item">Profile</a>

                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="fn-profile-logout-form">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="fn-profile-item fn-profile-logout-button">Logout</button>
                        </form>
                    </div>
                </details>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="fn-nav-link">Login</a>
                <a href="<?php echo e(route('register')); ?>" class="fn-btn-primary">Get Started</a>
            <?php endif; ?>
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

    .fn-nav-link:hover,
    .fn-nav-link.active {
        color: var(--fn-red, #ff385c);
        background: rgba(255, 56, 92, 0.05);
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

        .fn-profile-avatar-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(180deg, #fff5f7 0%, #ffe9ee 100%);
            color: var(--fn-red, #ff385c);
            font-size: 0.82rem;
            font-weight: 700;
            line-height: 1;
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
            background: rgba(255, 56, 92, 0.06);
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
</style>

<?php if($navbarUser): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const idleTimeoutMs = <?php echo e(max(1, (int) config('session.lifetime', 120)) * 60 * 1000); ?>;
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

                window.location.href = '<?php echo e(route('login')); ?>';
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
    <?php if($navbarUser && $navbarUser->isUser()): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const unreadBadge = document.getElementById('fn-user-unread-badge');
                if (!unreadBadge) {
                    return;
                }

                const refreshUnread = () => {
                    fetch('<?php echo e(route('user.conversations.unread-count')); ?>', {
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
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\FindNest\resources\views/components/navbar.blade.php ENDPATH**/ ?>