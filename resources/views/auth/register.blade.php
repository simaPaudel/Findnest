<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FindNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

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
            padding: 40px 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Pattern */
        body::before {
            content: '';
            position: absolute;
            top: -20%;
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
            bottom: -20%;
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
        .fn-input, .fn-select {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--fn-gray-light);
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--fn-white);
            color: var(--fn-charcoal);
        }

        .fn-input:focus, .fn-select:focus {
            outline: none;
            border-color: var(--fn-red);
            box-shadow: 0 0 0 4px rgba(255, 56, 92, 0.1);
            transform: translateY(-2px);
        }

        .fn-input::placeholder {
            color: var(--fn-gray-dark);
        }

        /* Checkbox */
        .fn-checkbox {
            width: 18px;
            height: 18px;
            border: 2px solid var(--fn-gray-light);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            accent-color: var(--fn-red);
        }

        .fn-checkbox:checked {
            background: var(--fn-red);
            border-color: var(--fn-red);
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

        /* Grid Layout */
        .fn-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 640px) {
            .fn-grid-2 {
                grid-template-columns: 1fr;
            }

            body {
                align-items: flex-start;
                justify-content: flex-start;
                padding: 24px 16px 32px;
            }

            body::before,
            body::after {
                width: 360px;
                height: 360px;
            }

            .fn-glass-card {
                border-radius: 20px;
            }

            .relative.z-10.w-full.max-w-2xl.px-6 {
                max-width: 100%;
                padding-left: 0;
                padding-right: 0;
            }
        }
    </style>
</head>

<body>
    <div class="relative z-10 w-full max-w-2xl px-6">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex justify-center" aria-label="FindNest home">
                <x-findnest-logo variant="stacked" size="lg" />
            </a>
            <p class="mt-2" style="color: var(--fn-gray-dark); font-size: 15px;">Create your account to get started.</p>
        </div>

        <!-- Registration Card -->
        <div class="fn-glass-card p-8">
            <h2 class="text-2xl font-bold text-center mb-6" style="color: var(--fn-charcoal);">Create Account</h2>

            <!-- Error Messages -->
            @if($errors->any())
            <div class="fn-alert-danger">
                @foreach($errors->all() as $error)
                <p class="mb-1 last:mb-0">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <!-- Registration Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- First Name & Last Name -->
                <div class="fn-grid-2 mb-5">
                    <div>
                        <label for="first_name" class="fn-label">First Name</label>
                        <input type="text" 
                               class="fn-input" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name') }}" 
                               placeholder="John"
                               required>
                    </div>
                    <div>
                        <label for="last_name" class="fn-label">Last Name</label>
                        <input type="text" 
                               class="fn-input" 
                               id="last_name" 
                               name="last_name" 
                               value="{{ old('last_name') }}" 
                               placeholder="Doe"
                               required>
                    </div>
                </div>

                <!-- Email & Phone -->
                <div class="fn-grid-2 mb-5">
                    <div>
                        <label for="email" class="fn-label">Email Address</label>
                        <input type="email" 
                               class="fn-input" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="your.email@example.com"
                               required>
                    </div>
                    <div>
                        <label for="phone" class="fn-label">Phone <span style="color: var(--fn-gray-dark); font-weight: 400;">(Optional)</span></label>
                        <input type="tel" 
                               class="fn-input" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               placeholder="+1 234 567 8900">
                    </div>
                </div>

                <!-- Password & Confirm Password -->
                <div class="fn-grid-2 mb-5">
                    <div>
                        <label for="password" class="fn-label">Password</label>
                        <input type="password" 
                               class="fn-input" 
                               id="password" 
                               name="password" 
                               placeholder="Min. 8 characters"
                               required>
                    </div>
                    <div>
                        <label for="password_confirmation" class="fn-label">Confirm Password</label>
                        <input type="password" 
                               class="fn-input" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Re-enter password"
                               required>
                    </div>
                </div>

                <!-- Account Type & Gender -->
                <div class="fn-grid-2 mb-5">
                    <div>
                        <label class="fn-label">Account Type</label>
                        <div class="fn-select" style="display: flex; align-items: center; justify-content: space-between; gap: 12px; background: #f9fafb; font-weight: 600;">
                            <span>Regular User</span>
                            <span style="font-size: 12px; color: var(--fn-gray-dark); text-transform: uppercase; letter-spacing: 0.08em;">Hosts apply later</span>
                        </div>
                        <input type="hidden" name="role" value="user">
                    </div>
                    <div>
                        <label for="gender" class="fn-label">Gender</label>
                        <select class="fn-select" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="mb-6 flex items-start gap-3">
                    <input type="checkbox" 
                           class="fn-checkbox mt-1" 
                           id="terms" 
                           required>
                    <label for="terms" class="fn-label mb-0 cursor-pointer" style="margin-top: 2px;">
                        I agree to the <a href="#" class="fn-link">Terms & Conditions</a> and <a href="#" class="fn-link">Privacy Policy</a>
                    </label>
                </div>

                <!-- Register Button -->
                <button type="submit" class="fn-btn-primary">
                    Create Account
                </button>
            </form>

            <!-- Footer Links -->
            <div class="mt-6 text-center space-y-2">
                <p style="color: var(--fn-gray-dark); font-size: 14px;">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="fn-link">Sign In</a>
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
                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            Safe & Secure Registration
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Toastr messages from session -->
    {!! Toastr::message() !!}

</body>

</html>
