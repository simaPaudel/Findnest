<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FindNest</title>
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

        html {
            width: 100%;
            overflow-x: hidden;
            -webkit-text-size-adjust: 100%;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: var(--fn-bg);
            color: var(--fn-ink);
            overflow-x: hidden;
        }

        img,
        svg {
            max-width: 100%;
        }

        input,
        select,
        button,
        a {
            max-width: 100%;
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
            width: min(560px, 100%);
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
            max-width: 420px;
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

        .auth-alert-danger {
            padding: 11px 13px;
            border-radius: 12px;
            background: rgba(239, 68, 68, 0.06);
            border: 1px solid rgba(239, 68, 68, 0.18);
            color: #991b1b;
            font-size: 0.92rem;
            line-height: 1.65;
            margin-bottom: 16px;
        }

        .auth-alert-danger p {
            margin: 0;
        }

        .auth-form {
            display: grid;
            gap: 15px;
        }

        .oauth-stack {
            display: grid;
            gap: 15px;
            margin-bottom: 16px;
        }

        .oauth-button {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            min-height: 46px;
            padding: 11px 16px;
            border: 1px solid #d7dee8;
            border-radius: 12px;
            background: linear-gradient(180deg, #ffffff 0%, #fbfcfe 100%);
            color: var(--fn-ink);
            font: inherit;
            font-weight: 700;
            font-size: 0.94rem;
            line-height: 1.2;
            text-decoration: none;
            box-shadow: 0 1px 0 rgba(255, 255, 255, 0.9), 0 8px 18px rgba(15, 23, 42, 0.04);
            transition: border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease, background 0.18s ease;
        }

        .oauth-button:hover {
            border-color: rgba(255, 56, 92, 0.34);
            background: #ffffff;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08), 0 0 0 3px rgba(255, 56, 92, 0.06);
            transform: translateY(-1px);
        }

        .oauth-button:focus-visible {
            outline: none;
            border-color: var(--fn-red);
            box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.12);
        }

        .oauth-icon {
            width: 20px;
            height: 20px;
            flex: 0 0 20px;
            display: block;
        }

        .oauth-icon-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #edf1f6;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.05);
        }

        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--fn-muted);
            font-size: 0.82rem;
            font-weight: 600;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: "";
            height: 1px;
            flex: 1;
            background: var(--fn-border);
        }

        .fn-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .fn-label {
            display: block;
            margin-bottom: 8px;
            color: var(--fn-ink);
            font-size: 0.88rem;
            font-weight: 600;
        }

        .fn-input,
        .fn-select {
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

        .fn-input:focus,
        .fn-select:focus {
            outline: none;
            border-color: var(--fn-red);
            box-shadow: 0 0 0 3px rgba(255, 56, 92, 0.08);
        }

        .fn-select {
            appearance: auto;
        }

        .fn-checkbox-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .fn-checkbox {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            border: 1px solid var(--fn-border);
            border-radius: 6px;
            accent-color: var(--fn-red);
            flex: 0 0 auto;
        }

        .fn-checkbox-label {
            margin: 0;
            color: var(--fn-muted);
            font-size: 0.94rem;
            line-height: 1.7;
        }

        .fn-checkbox-label a {
            color: var(--fn-red);
            text-decoration: none;
            font-weight: 600;
        }

        .fn-checkbox-label a:hover {
            color: var(--fn-red-hover);
        }

        .password-field {
            position: relative;
        }

        .password-field .fn-input {
            padding-right: 48px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: var(--fn-muted);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: color 0.18s ease, background 0.18s ease;
        }

        .password-toggle:hover,
        .password-toggle:focus-visible {
            color: var(--fn-red);
            background: rgba(255, 56, 92, 0.06);
            outline: none;
        }

        .password-toggle svg {
            width: 18px;
            height: 18px;
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

            .fn-grid-2 {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 380px) {
            .auth-shell {
                padding-left: 8px;
                padding-right: 8px;
            }

            .auth-card {
                padding-left: 12px;
                padding-right: 12px;
            }

            .auth-title {
                font-size: 1.35rem;
            }
        }
    </style>
</head>
<body>
    <main class="auth-shell">
        <div class="auth-page">
            <header class="auth-hero">
                <a href="{{ route('home') }}" class="auth-logo" aria-label="FindNest home">
                    <x-findnest-logo variant="inline" size="sm" />
                </a>
                <p class="auth-subtitle">Create your account to book stays and find roommates.</p>
            </header>

            <section class="auth-card">
                <h1 class="auth-title">Create Account</h1>

                @if($errors->any())
                    <div class="auth-alert-danger">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="oauth-stack">
                    <a href="{{ route('auth.google.redirect') }}" class="oauth-button">
                        <span class="oauth-icon-wrap" aria-hidden="true">
                            <svg class="oauth-icon" viewBox="0 0 24 24" focusable="false">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.1c-.22-.66-.35-1.36-.35-2.1s.13-1.44.35-2.1V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.84z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06L5.84 9.9C6.71 7.31 9.14 5.38 12 5.38z"/>
                            </svg>
                        </span>
                        <span>Continue with Google</span>
                    </a>
                    <div class="auth-divider">or create account with email</div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf

                    <div class="fn-grid-2">
                        <div>
                            <label for="first_name" class="fn-label">First Name</label>
                            <input
                                type="text"
                                class="fn-input"
                                id="first_name"
                                name="first_name"
                                value="{{ old('first_name') }}"
                                placeholder="John"
                                required
                            >
                        </div>

                        <div>
                            <label for="last_name" class="fn-label">Last Name</label>
                            <input
                                type="text"
                                class="fn-input"
                                id="last_name"
                                name="last_name"
                                value="{{ old('last_name') }}"
                                placeholder="Doe"
                                required
                            >
                        </div>
                    </div>

                    <div class="fn-grid-2">
                        <div>
                            <label for="email" class="fn-label">Email Address</label>
                            <input
                                type="email"
                                class="fn-input"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="your.email@example.com"
                                required
                            >
                        </div>

                        <div>
                            <label for="phone" class="fn-label">Phone <span style="color: var(--fn-muted); font-weight: 400;">(Optional)</span></label>
                            <input
                                type="tel"
                                class="fn-input"
                                id="phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="+1 234 567 8900"
                            >
                        </div>
                    </div>

                    <div class="fn-grid-2">
                        <div>
                            <label for="password" class="fn-label">Password</label>
                            <div class="password-field">
                                <input
                                    type="password"
                                    class="fn-input"
                                    id="password"
                                    name="password"
                                    placeholder="8+ chars, upper/lowercase, number, symbol"
                                    required
                                >
                                <button type="button" class="password-toggle" data-password-toggle="password" aria-label="Show password" aria-pressed="false">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15.25A3.25 3.25 0 1 0 12 8.75a3.25 3.25 0 0 0 0 6.5Z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="password_confirmation" class="fn-label">Confirm Password</label>
                            <div class="password-field">
                                <input
                                    type="password"
                                    class="fn-input"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Re-enter password"
                                    required
                                >
                                <button type="button" class="password-toggle" data-password-toggle="password_confirmation" aria-label="Show password confirmation" aria-pressed="false">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15.25A3.25 3.25 0 1 0 12 8.75a3.25 3.25 0 0 0 0 6.5Z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
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

                    <input type="hidden" name="role" value="user">

                    <div class="fn-checkbox-row">
                        <input
                            type="checkbox"
                            class="fn-checkbox"
                            id="terms"
                            required
                        >
                        <label for="terms" class="fn-checkbox-label">
                            I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>
                        </label>
                    </div>

                    <button type="submit" class="auth-button">Create Account</button>
                </form>

                <div class="auth-footer">
                    <p>
                        Already have an account?
                        <a href="{{ route('login') }}" class="auth-link">Sign In</a>
                    </p>
                    <p>
                        <a href="{{ route('home') }}" class="auth-link-muted">Back to Home</a>
                    </p>
                </div>
            </section>

            <p class="auth-note">Join FindNest &bull; Secure sign up and booking access</p>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.passwordToggle);
                if (!input) {
                    return;
                }

                const shouldShow = input.type === 'password';
                input.type = shouldShow ? 'text' : 'password';
                button.setAttribute('aria-pressed', shouldShow ? 'true' : 'false');
                button.setAttribute('aria-label', shouldShow ? 'Hide password' : 'Show password');
            });
        });
    </script>
    {!! Toastr::message() !!}
</body>
</html>
