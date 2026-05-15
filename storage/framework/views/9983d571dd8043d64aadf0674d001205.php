<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'User Dashboard'); ?> - FindNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* FindNest Theme - CSS Variables */
        :root {
            --fn-red: #FF385C;
            --fn-red-hover: #E11D48;
            --fn-white: #FFFFFF;
            --fn-gray-light: #F3F4F6;
            --fn-charcoal: #1F2937;
            --fn-gray-border: #E5E7EB;
            --fn-gray-dark: #6B7280;
            --fn-text-secondary: #9CA3AF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            width: 100%;
            overflow-x: hidden;
            -webkit-text-size-adjust: 100%;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--fn-white);
            color: var(--fn-charcoal);
            min-height: 100vh;
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

        .max-w-7xl {
            min-width: 0;
        }

        .overflow-x-auto,
        [class*="table-wrap"],
        [class*="table-responsive"] {
            -webkit-overflow-scrolling: touch;
        }

        /* Utility Classes */
        .fn-bg-red {
            background-color: var(--fn-red);
        }

        .fn-bg-white {
            background-color: var(--fn-white);
        }

        .fn-bg-gray {
            background-color: var(--fn-gray-light);
        }

        .fn-text-red {
            color: var(--fn-red);
        }

        .fn-text-charcoal {
            color: var(--fn-charcoal);
        }

        .fn-text-white {
            color: var(--fn-white);
        }

        .fn-text-gray {
            color: var(--fn-gray-dark);
        }

        .fn-border-red {
            border-color: var(--fn-red);
        }

        .fn-border-gray {
            border-color: var(--fn-gray-border);
        }

        /* Navbar */
        .fn-navbar {
            background: var(--fn-white);
            border-bottom: 1px solid var(--fn-gray-border);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .fn-navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
        }

        .fn-navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--fn-red);
            text-decoration: none;
            flex-shrink: 0;
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
        }

        .fn-nav-link {
            color: var(--fn-charcoal);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .fn-nav-link:hover {
            color: var(--fn-red);
            background: transparent;
        }

        .fn-nav-link.active {
            color: var(--fn-red);
            background: transparent;
        }

        .fn-navbar-end {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .fn-profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--fn-gray-light);
            border: 1px solid var(--fn-gray-border);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--fn-charcoal);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .fn-profile-avatar:hover {
            border-color: var(--fn-red);
            color: var(--fn-red);
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

        /* Card */
        .fn-card {
            background: var(--fn-white);
            border: 1px solid var(--fn-gray-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .fn-card:hover {
            border-color: var(--fn-red);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* Buttons */
        .fn-btn-primary {
            background: var(--fn-red);
            color: var(--fn-white);
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .fn-btn-primary:hover {
            background: var(--fn-red-hover);
        }

        .fn-btn-secondary {
            background: var(--fn-white);
            color: var(--fn-charcoal);
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 500;
            border: 1px solid var(--fn-gray-border);
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .fn-btn-secondary:hover {
            border-color: var(--fn-red);
            color: var(--fn-red);
            background: rgba(255, 56, 92, 0.05);
        }

        /* Badge */
        .fn-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .fn-badge-red {
            background: rgba(255, 56, 92, 0.1);
            color: var(--fn-red);
        }

        .fn-badge-green {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .fn-badge-yellow {
            background: rgba(245, 158, 11, 0.1);
            color: #F59E0B;
        }

        .fn-badge-gray {
            background: var(--fn-gray-light);
            color: var(--fn-gray-dark);
        }

        /* Alert */
        .fn-alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .fn-alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .fn-alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        @media (max-width: 768px) {
            main.py-8 {
                padding-top: 1.25rem;
                padding-bottom: 1.5rem;
            }

            main .px-6 {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            h1.text-3xl {
                font-size: 1.55rem;
                line-height: 1.25;
            }

            .fn-card {
                border-radius: 10px;
            }

            .fn-btn-primary,
            .fn-btn-secondary {
                justify-content: center;
                text-align: center;
                white-space: normal;
            }
        }

        @media (max-width: 420px) {
            main .px-6 {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .fn-alert {
                align-items: flex-start;
                padding: 10px 12px;
            }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body class="fn-bg-white">
    <!-- Navbar -->
    <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <main class="py-8 bg-fn-white">
        <div class="px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <!-- Flash Messages -->
                <?php if(session('success')): ?>
                <div class="fn-alert fn-alert-success mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span><?php echo e(session('success')); ?></span>
                </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                <div class="fn-alert fn-alert-error mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span><?php echo e(session('error')); ?></span>
                </div>
                <?php endif; ?>

                <!-- Page Title -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold fn-text-charcoal"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h1>
                    <?php if(request()->routeIs('user.dashboard')): ?>
                    <p class="text-sm fn-text-gray mt-1">Welcome back, <?php echo e(auth()->user()->name); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Content -->
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </main>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\FindNest\resources\views/user/layout.blade.php ENDPATH**/ ?>