<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FindNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        /* FindNest Airbnb-Style Theme */
        :root {
            --fn-red: #FF385C;
            --fn-red-hover: #E11D48;
            --fn-white: #FFFFFF;
            --fn-gray: #F7F7F7;
            --fn-charcoal: #1F2937;
            --fn-gray-light: #E5E7EB;
            --fn-gray-dark: #6B7280;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #FFF5F7 0%, #FFFFFF 50%, #FFF1F3 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Pattern */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 56, 92, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 56, 92, 0.06) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 10s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(5deg); }
        }

        /* Glassmorphism Card */
        .fn-glass-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(229, 231, 235, 0.8);
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Input Fields */
        .fn-input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--fn-gray-light);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--fn-white);
            color: var(--fn-charcoal);
        }

        .fn-input:focus {
            outline: none;
            border-color: var(--fn-red);
            box-shadow: 0 0 0 4px rgba(255, 56, 92, 0.1);
            transform: translateY(-2px);
        }

        .fn-input::placeholder {
            color: var(--fn-gray-dark);
        }

        /* Primary Button */
        .fn-btn-primary {
            background: linear-gradient(135deg, var(--fn-red) 0%, #ff1744 100%);
            color: var(--fn-white);
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(255, 56, 92, 0.3);
            width: 100%;
            font-size: 16px;
            position: relative;
            overflow: hidden;
        }

        .fn-btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .fn-btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }

        .fn-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 32px rgba(255, 56, 92, 0.4);
        }

        .fn-btn-primary:active {
            transform: translateY(-1px);
        }

        /* Links */
        .fn-link {
            color: var(--fn-red);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .fn-link:hover {
            color: var(--fn-red-hover);
            text-decoration: underline;
        }

        .fn-link-subtle {
            color: var(--fn-gray-dark);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .fn-link-subtle:hover {
            color: var(--fn-charcoal);
        }

        /* Alert Styles */
        .fn-alert-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #166534;
            padding: 12px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            animation: slideDown 0.5s ease;
        }

        .fn-alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #991b1b;
            padding: 12px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Label */
        .fn-label {
            display: block;
            color: var(--fn-charcoal);
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="relative z-10 w-full max-w-md px-6">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2 text-3xl font-bold" style="color: var(--fn-red);">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                FindNest
            </a>
            <p class="mt-2" style="color: var(--fn-gray-dark); font-size: 15px;">Welcome back! Please login to your account.</p>
        </div>

        <!-- Login Card -->
        <div class="fn-glass-card p-8">
            <h2 class="text-2xl font-bold text-center mb-6" style="color: var(--fn-charcoal);">Sign In</h2>

            <!-- Success Message -->
            @if (session('success'))
            <div class="fn-alert-success">
                {{ session('success') }}
            </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="fn-alert-danger">
                @foreach($errors->all() as $error)
                <p class="mb-1 last:mb-0">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Field -->
                <div class="mb-5">
                    <label for="email" class="fn-label">Email Address</label>
                    <input type="email" 
                           class="fn-input" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="your.email@example.com"
                           required 
                           autofocus>
                </div>

                <!-- Password Field -->
                <div class="mb-2">
                    <label for="password" class="fn-label">Password</label>
                    <input type="password" 
                           class="fn-input" 
                           id="password" 
                           name="password" 
                           placeholder="Enter your password"
                           required>
                </div>

                <!-- Forgot Password Link -->
                <div class="mb-6 text-right">
                    <a href="{{ route('password.request') }}" class="fn-link" style="font-size: 13px;">
                        Forgot Password?
                    </a>
                </div>

                <!-- Login Button -->
                <button type="submit" class="fn-btn-primary">
                    Sign In
                </button>
            </form>

            <!-- Footer Links -->
            <div class="mt-6 text-center space-y-2">
                <p style="color: var(--fn-gray-dark); font-size: 14px;">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="fn-link">Create Account</a>
                </p>
                <p>
                    <a href="{{ url('/') }}" class="fn-link-subtle" style="font-size: 14px;">
                        ← Back to Home
                    </a>
                </p>
            </div>
        </div>

        <!-- Trust Badge -->
        <div class="text-center mt-6" style="color: var(--fn-gray-dark); font-size: 13px;">
            <svg class="w-4 h-4 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
            Secure Login • Your data is protected
        </div>
    </div>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    {!! Toastr::message() !!}
</body>

</html>