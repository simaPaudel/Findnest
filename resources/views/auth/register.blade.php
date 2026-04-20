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
                            <input
                                type="password"
                                class="fn-input"
                                id="password"
                                name="password"
                                placeholder="Min. 8 characters"
                                required
                            >
                        </div>

                        <div>
                            <label for="password_confirmation" class="fn-label">Confirm Password</label>
                            <input
                                type="password"
                                class="fn-input"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Re-enter password"
                                required
                            >
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
    {!! Toastr::message() !!}
</body>
</html>
