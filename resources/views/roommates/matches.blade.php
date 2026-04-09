<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roommate Matches - FindNest</title>
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

        body {
            font-family: 'Inter', sans-serif;
            background: var(--fn-white);
            color: var(--fn-charcoal);
        }

        .fn-text-red {
            color: var(--fn-red);
        }

        .fn-text-charcoal {
            color: var(--fn-charcoal);
        }

        .fn-text-gray {
            color: var(--fn-gray-dark);
        }

        .fn-bg-gray {
            background-color: var(--fn-gray);
        }

        .fn-navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--fn-gray-light);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.04);
        }

        .fn-nav-link {
            color: var(--fn-charcoal);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .fn-nav-link:hover {
            background: rgba(255, 56, 92, 0.08);
            color: var(--fn-red);
        }

        .fn-btn-primary {
            background: linear-gradient(135deg, var(--fn-red) 0%, #ff1744 100%);
            color: var(--fn-white);
            padding: 14px 32px;
            border-radius: 16px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 4px 20px rgba(255, 56, 92, 0.25);
            display: inline-block;
            text-decoration: none;
        }

        .fn-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 32px rgba(255, 56, 92, 0.35);
        }

        .fn-glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(229, 231, 235, 0.8);
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            padding: 24px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="fn-navbar">
        <div class="px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between max-w-7xl mx-auto">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-2xl font-bold fn-text-red">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    FindNest
                </a>

                <div class="flex items-center gap-4">
                    <a href="{{ route('listings.index') }}" class="fn-nav-link">Browse Listings</a>
                    <a href="{{ route('user.dashboard') }}" class="fn-nav-link">Dashboard</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-20">
        <div class="max-w-4xl mx-auto px-6">
            <div class="fn-glass-card text-center">
                <svg class="w-24 h-24 mx-auto mb-6 fn-text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <h1 class="text-4xl font-bold fn-text-charcoal mb-4">Roommate Matches</h1>
                <p class="text-xl fn-text-gray mb-8">Coming Soon!</p>
                <p class="fn-text-gray mb-8">
                    We're building an intelligent matching algorithm to connect you with compatible roommates based on your preferences, lifestyle, and budget.
                </p>
                <a href="{{ route('roommates.profile') }}" class="fn-btn-primary">
                    Update My Preferences
                </a>
            </div>
        </div>
    </main>
</body>

</html>