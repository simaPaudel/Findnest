<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Owner Dashboard'); ?> - FindNest</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?php echo e(asset('css/owner.css')); ?>?v=<?php echo e(filemtime(public_path('css/owner.css'))); ?>" rel="stylesheet">
</head>

<body>
    <?php
        $ownerLayoutUser = null;

        try {
            if (auth()->check()) {
                $ownerLayoutUser = auth()->user();
            }
        } catch (\Throwable $e) {
            $ownerLayoutUser = null;
        }
    ?>
    <?php
        $ownerAvatarUrl = $ownerLayoutUser && method_exists($ownerLayoutUser, 'profilePhotoUrl')
            ? $ownerLayoutUser->profilePhotoUrl()
            : null;
    ?>
    <nav class="owner-navbar">
        <div class="owner-navbar-container">
            <a href="<?php echo e(route('owner.dashboard')); ?>" class="owner-brand">
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
                <span class="owner-brand-badge">Owner</span>
            </a>

            <div class="owner-navbar-center">
                <a href="<?php echo e(route('owner.dashboard')); ?>" class="owner-nav-link <?php echo e(request()->routeIs('owner.dashboard') ? 'active' : ''); ?>">Dashboard</a>
                <a href="<?php echo e(route('owner.listings.index')); ?>" class="owner-nav-link <?php echo e(request()->routeIs('owner.listings.index') ? 'active' : ''); ?>">Properties</a>
                <a href="<?php echo e(route('owner.listings.create')); ?>" class="owner-nav-link <?php echo e(request()->routeIs('owner.listings.create') ? 'active' : ''); ?>">Add Property</a>
                <a href="<?php echo e(route('owner.bookings.index')); ?>" class="owner-nav-link <?php echo e(request()->routeIs('owner.bookings.*') ? 'active' : ''); ?>">Booking Requests</a>
                <a href="<?php echo e(route('owner.reviews.index')); ?>" class="owner-nav-link <?php echo e(request()->routeIs('owner.reviews.*') ? 'active' : ''); ?>">Reviews</a>
            </div>

            <div class="owner-navbar-end">
                <?php echo $__env->make('components.notification-dropdown', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <a href="<?php echo e(route('owner.messages.index')); ?>" class="owner-message-link <?php echo e(request()->routeIs('owner.messages.*') || request()->routeIs('owner.conversations.*') ? 'active' : ''); ?>" aria-label="Messages">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8m-8 4h5m7 6l-3.5-2H7a4 4 0 01-4-4V8a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-4 4h-1.5L12 20z"></path>
                    </svg>
                    <span class="owner-message-badge" id="owner-unread-badge" hidden>0</span>
                </a>

                <details class="owner-profile-menu">
                    <summary class="owner-profile-trigger" aria-label="Profile menu">
                        <?php if($ownerAvatarUrl): ?>
                            <img src="<?php echo e($ownerAvatarUrl); ?>" alt="Profile" class="profile-photo">
                        <?php else: ?>
                            <svg class="owner-profile-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        <?php endif; ?>
                    </summary>

                    <div class="owner-profile-panel" role="menu" aria-label="Profile menu">
                        <a href="<?php echo e(route('owner.profile.edit')); ?>" class="owner-profile-item">Profile</a>

                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="owner-profile-logout-form">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="owner-profile-item owner-profile-logout-button">Logout</button>
                        </form>
                    </div>
                </details>
            </div>
        </div>
    </nav>

    <main class="owner-main">
        <div class="owner-content">
            <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-error">
                    <div>
                        <strong>There were some errors with your submission:</strong>
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const unreadBadge = document.getElementById('owner-unread-badge');
            if (!unreadBadge) {
                return;
            }

            const refreshUnread = () => {
                fetch('<?php echo e(route('owner.conversations.unread-count')); ?>', {
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
<?php /**PATH C:\xampp\htdocs\FindNest\resources\views/owner/layout.blade.php ENDPATH**/ ?>