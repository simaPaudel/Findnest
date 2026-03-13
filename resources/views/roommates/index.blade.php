<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Roommates - FindNest</title>
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
    </style>
</head>
<body>
    <div class="pulse-bg absolute inset-0 overflow-hidden pointer-events-none"></div>
    
    <div class="relative z-10 text-center px-6 max-w-2xl mx-auto">
        <div class="fn-glass-card p-12">
            <!-- Logo -->
            <div class="flex items-center justify-center gap-2 text-3xl font-bold fn-text-red mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                FindNest
            </div>

            <!-- Icon -->
            <div class="flex justify-center mb-6">
                <div class="p-6 bg-red-50 rounded-full">
                    <svg class="w-16 h-16 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Heading -->
            <h1 class="text-4xl md:text-5xl font-bold fn-text-charcoal mb-4">
                Find Roommates
            </h1>
            
            <!-- Subheading -->
            <p class="text-lg fn-text-gray mb-2">
                Coming Soon!
            </p>
            
            <p class="text-base fn-text-gray mb-8 max-w-md mx-auto">
                Our intelligent roommate matching system is under development. Get ready to find your perfect match!
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
