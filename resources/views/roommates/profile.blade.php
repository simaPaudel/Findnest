<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Roommate Profile - FindNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
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
            background: linear-gradient(135deg, #FFF5F7 0%, var(--fn-white) 100%);
            color: var(--fn-charcoal);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }

        .fn-text-red { color: var(--fn-red); }
        .fn-text-charcoal { color: var(--fn-charcoal); }
        .fn-text-gray { color: var(--fn-gray-dark); }

        .fn-btn-primary {
            background: linear-gradient(135deg, var(--fn-red) 0%, #ff1744 100%);
            color: var(--fn-white);
            padding: 14px 32px;
            border-radius: 16px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(255, 56, 92, 0.25);
            display: inline-block;
            text-decoration: none;
        }

        .fn-btn-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 32px rgba(255, 56, 92, 0.35);
        }

        .fn-glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(229, 231, 235, 0.8);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.6; }
            50% { transform: scale(1.15); opacity: 1; }
        }

        .pulse-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 56, 92, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse 8s ease-in-out infinite;
        }

        @media (max-width: 640px) {
            body {
                align-items: flex-start;
                justify-content: flex-start;
                padding: 24px 16px 32px;
            }

            .fn-glass-card {
                border-radius: 18px;
                padding: 1.5rem;
            }

            .relative.z-10.text-center.px-6.max-w-2xl.mx-auto {
                max-width: 100%;
                padding-left: 0;
                padding-right: 0;
            }

            .pulse-bg::before {
                width: 320px;
                height: 320px;
            }
        }
    </style>
</head>
<body>
    <div class="pulse-bg absolute inset-0 overflow-hidden pointer-events-none"></div>
    
    <div class="relative z-10 text-center px-6 max-w-2xl mx-auto">
        <div class="fn-glass-card p-12">
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <x-findnest-logo variant="stacked" size="lg" />
            </div>

            <!-- Icon -->
            <div class="flex justify-center mb-6">
                <div class="p-6 bg-red-50 rounded-full">
                    <svg class="w-16 h-16 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Heading -->
            <h1 class="text-4xl md:text-5xl font-bold fn-text-charcoal mb-4">
                My Roommate Profile
            </h1>
            
            <!-- Subheading -->
            <p class="text-lg fn-text-gray mb-2">
                Coming Soon!
            </p>
            
            <p class="text-base fn-text-gray mb-8 max-w-md mx-auto">
                Your personalized roommate profile and matching dashboard is being built. Stay tuned!
            </p>

            <!-- Back Button -->
            <a href="{{ url('/') }}" class="fn-btn-primary inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Home
            </a>
        </div>
    </div>
</body>
</html>
