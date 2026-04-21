<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FindNest</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        :root {
            --fn-red: #ff385c;
            --fn-red-hover: #e11d48;
            --fn-ink: #0f172a;
            --fn-muted: #64748b;
            --fn-border: #dbe3ed;
            --fn-bg: #f6f7f9;
            --fn-surface: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: var(--fn-bg);
            color: var(--fn-ink);
        }

        a {
            color: inherit;
        }

        .auth-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .auth-page {
            width: min(400px, 100%);
            display: grid;
            gap: 16px;
        }

        .auth-hero {
            display: grid;
            justify-items: center;
            gap: 10px;
        }

        .auth-logo {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .auth-logo > span {
            width: 92px !important;
        }

        .auth-subtitle {
            margin: 0;
            max-width: 360px;
            color: var(--fn-muted);
            font-size: 0.92rem;
            line-height: 1.6;
            text-align: center;
        }

        .auth-card {
            background: var(--fn-surface);
            border: 1px solid var(--fn-border);
            border-radius: 18px;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.05);
            padding: 22px 22px 20px;
        }

        .auth-title {
            margin: 0 0 18px;
            font-size: clamp(1.4rem, 3vw, 1.7rem);
            line-height: 1.15;
            letter-spacing: -0.04em;
            font-weight: 800;
            color: var(--fn-ink);
            text-align: center;
        }

        .auth-note {
            margin: 2px 0 0;
            color: var(--fn-muted);
            font-size: 0.9rem;
            line-height: 1.6;
            text-align: center;
        }

        .auth-alert-success,
        .auth-alert-danger {
            padding: 11px 13px;
            border-radius: 12px;
            font-size: 0.92rem;
            line-height: 1.65;
            margin-bottom: 16px;
        }

        .auth-alert-success {
            background: rgba(15, 23, 42, 0.03);
            border: 1px solid rgba(148, 163, 184, 0.35);
            color: #334155;
        }

        .auth-alert-danger {
            background: rgba(239, 68, 68, 0.06);
            border: 1px solid rgba(239, 68, 68, 0.18);
            color: #991b1b;
        }

        .auth-alert-danger p {
            margin: 0;
        }

        .auth-form {
            display: grid;
            gap: 15px;
        }

        .fn-label {
            display: block;
            margin-bottom: 8px;
            color: var(--fn-ink);
            font-size: 0.88rem;
            font-weight: 600;
        }

        .fn-input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--fn-border);
            border-radius: 12px;
            background: #ffffff;
            color: var(--fn-ink);
            font: inherit;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .fn-input::placeholder {
            color: #94a3b8;
        }

        .fn-input:focus {
            outline: none;
            border-color: var(--fn-red);
            box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.08);
        }

        .auth-row {
            display: flex;
            justify-content: flex-end;
        }

        .auth-link {
            color: var(--fn-red);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.18s ease;
        }

        .auth-link:hover {
            color: var(--fn-red-hover);
        }

        .auth-link-muted {
            color: var(--fn-muted);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.18s ease;
        }

        .auth-link-muted:hover {
            color: var(--fn-ink);
        }

        .auth-button {
            width: 100%;
            padding: 10px 16px;
            border: 0;
            border-radius: 12px;
            background: var(--fn-red);
            color: #ffffff;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 8px 14px rgba(255, 56, 92, 0.12);
            transition: background 0.18s ease, transform 0.18s ease, box-shadow 0.18s ease;
        }

        .auth-button:hover {
            background: var(--fn-red-hover);
            transform: translateY(-1px);
            box-shadow: 0 10px 18px rgba(255, 56, 92, 0.15);
        }

        .auth-footer {
            margin-top: 14px;
            display: grid;
            gap: 8px;
            text-align: center;
        }

        .auth-footer p {
            margin: 0;
            color: var(--fn-muted);
            font-size: 0.92rem;
            line-height: 1.65;
        }

        @media (max-width: 640px) {
            .auth-shell {
                padding: 18px 10px 20px;
            }

            .auth-page {
                width: 100%;
                gap: 12px;
            }

            .auth-logo > span {
                width: 84px !important;
            }

            .auth-card {
                padding: 20px 16px 18px;
                border-radius: 16px;
            }

            .auth-footer {
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <main class="auth-shell">
        <div class="auth-page">
            <header class="auth-hero">
                <a href="<?php echo e(route('home')); ?>" class="auth-logo" aria-label="FindNest home">
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
                <p class="auth-subtitle">Welcome back! Please login to your account.</p>
            </header>

            <section class="auth-card">
                <h1 class="auth-title">Sign In</h1>

                <?php if(session('success')): ?>
                    <div class="auth-alert-success">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="auth-alert-danger">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php elseif($errors->any()): ?>
                    <div class="auth-alert-danger">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p><?php echo e($error); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('login')); ?>" class="auth-form">
                    <?php echo csrf_field(); ?>

                    <div>
                        <label for="email" class="fn-label">Email Address</label>
                        <input
                            type="email"
                            class="fn-input"
                            id="email"
                            name="email"
                            value="<?php echo e(old('email')); ?>"
                            placeholder="your.email@example.com"
                            required
                            autofocus
                        >
                    </div>

                    <div>
                        <label for="password" class="fn-label">Password</label>
                        <input
                            type="password"
                            class="fn-input"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            required
                        >
                    </div>

                    <div class="auth-row">
                        <a href="<?php echo e(route('password.request')); ?>" class="auth-link" style="font-size: 0.9rem;">Forgot Password?</a>
                    </div>

                    <button type="submit" class="auth-button">Sign In</button>
                </form>

                <div class="auth-footer">
                    <p>
                        Don't have an account?
                        <a href="<?php echo e(route('register')); ?>" class="auth-link">Create Account</a>
                    </p>
                    <p>
                        <a href="<?php echo e(route('home')); ?>" class="auth-link-muted">Back to Home</a>
                    </p>
                </div>
            </section>

            <p class="auth-note">Secure Login &bull; Your data is protected</p>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <?php echo Toastr::message(); ?>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\FindNest\resources\views/auth/login.blade.php ENDPATH**/ ?>