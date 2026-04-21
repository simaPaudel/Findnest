<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin Panel'); ?> - FindNest</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/admin.css')); ?>">
</head>
<body>
    <?php
        $adminNavUser = null;

        try {
            if (auth()->check()) {
                $adminNavUser = auth()->user();
            }
        } catch (\Throwable $e) {
            $adminNavUser = null;
        }
    ?>
    <?php
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
    ?>
    <div class="admin-page">
        <nav class="admin-navbar">
            <div class="admin-navbar-container">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="admin-brand">
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
                    <span class="brand-badge">Admin</span>
                </a>

                <div class="admin-navbar-center">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="admin-nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">Dashboard</a>
                    <a href="<?php echo e(route('admin.properties.index')); ?>" class="admin-nav-link <?php echo e(request()->routeIs('admin.properties.*') ? 'active' : ''); ?>">Properties</a>
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="admin-nav-link <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">Users</a>
                    <a href="<?php echo e(route('admin.owner-applications.index')); ?>" class="admin-nav-link <?php echo e(request()->routeIs('admin.owner-applications.*') ? 'active' : ''); ?>">Host Applications</a>
                    <a href="<?php echo e(route('admin.reviews.index')); ?>" class="admin-nav-link <?php echo e(request()->routeIs('admin.reviews.*') ? 'active' : ''); ?>">Reviews</a>
                    <a href="<?php echo e(route('admin.bookings.index')); ?>" class="admin-nav-link <?php echo e(request()->routeIs('admin.bookings.*') ? 'active' : ''); ?>">Bookings</a>
                </div>

                <div class="admin-navbar-end">
                    <?php echo $__env->make('components.notification-dropdown', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <a href="<?php echo e(route('admin.reports.index')); ?>" class="admin-message-link <?php echo e(request()->routeIs('admin.reports.*') ? 'active' : ''); ?>" title="Reports" aria-label="Reports">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8m-8 4h5m7 6l-3.5-2H7a4 4 0 01-4-4V8a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-4 4h-1.5L12 20z"></path>
                        </svg>
                    </a>

                    <details class="admin-profile-menu">
                        <summary class="admin-profile-trigger <?php echo e(request()->routeIs('admin.profile.*') ? 'is-active' : ''); ?>" aria-label="Profile menu">
                            <div class="admin-avatar">
                                <?php if($adminAvatarUrl): ?>
                                    <img
                                        src="<?php echo e($adminAvatarUrl); ?>"
                                        alt="<?php echo e(data_get($adminNavUser, 'name', 'Admin')); ?>"
                                        onerror="this.style.display='none'; this.nextElementSibling.removeAttribute('hidden');"
                                    >
                                    <span class="admin-avatar-fallback" hidden><?php echo e($adminAvatarInitial); ?></span>
                                <?php else: ?>
                                    <span class="admin-avatar-fallback"><?php echo e($adminAvatarInitial); ?></span>
                                <?php endif; ?>
                            </div>
                        </summary>

                        <div class="admin-profile-panel" role="menu" aria-label="Profile menu">
                            <a href="<?php echo e(route('admin.profile.edit')); ?>" class="admin-profile-item <?php echo e(request()->routeIs('admin.profile.*') ? 'active' : ''); ?>">Profile</a>
                            <form method="POST" action="<?php echo e(route('logout')); ?>" class="admin-profile-logout-form">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="admin-profile-item admin-profile-logout-button">Logout</button>
                            </form>
                        </div>
                    </details>
                </div>
            </div>
        </nav>

        <?php ($adminPagebarHidden = trim($__env->yieldContent('hide_pagebar'))); ?>
        <?php if($adminPagebarHidden !== 'true'): ?>
            <header class="admin-pagebar">
                <div>
                    <?php ($adminPageKicker = trim($__env->yieldContent('page_kicker'))); ?>
                    <?php if($adminPageKicker !== ''): ?>
                        <p class="page-kicker"><?php echo e($adminPageKicker); ?></p>
                    <?php endif; ?>
                    <h1 class="page-title"><?php echo $__env->yieldContent('page_title', 'Admin Dashboard'); ?></h1>
                </div>

                <div class="admin-pagebar-meta">
                    <?php echo $__env->yieldContent('page_meta', now()->format('l, F j, Y')); ?>
                </div>
            </header>
        <?php endif; ?>

        <main class="admin-main">
            <section class="admin-content">
                <?php if(session('success')): ?>
                    <div class="admin-alert admin-alert-success">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="admin-alert admin-alert-error">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="admin-alert admin-alert-error">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p><?php echo e($error); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </section>
        </main>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\FindNest\resources\views/admin/layout.blade.php ENDPATH**/ ?>