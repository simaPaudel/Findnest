<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindNest - Find Your Perfect Student Accommodation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .nav-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .step-number {
            width: 50px;
            height: 50px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-weight: bold;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand nav-brand text-primary" href="/">
                🏠 FindNest
            </a>
            
            <div class="navbar-nav ms-auto">
                @auth
                    <a class="nav-link" href="/dashboard">Dashboard</a>
                    <form method="POST" action="/logout" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-sm">Logout</button>
                    </form>
                @else
                    <a class="nav-link" href="/login">Login</a>
                    <a class="btn btn-primary" href="/register">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">
                Find Your Perfect Student Accommodation
            </h1>
            <p class="lead mb-4">
                Discover verified rooms, and ideal roommates in one trusted platform
            </p>
            @auth
                <a href="/dashboard" class="btn btn-light btn-lg">Go to Dashboard</a>
            @else
                <a href="/register" class="btn btn-light btn-lg me-2">Get Started</a>
                <a href="/login" class="btn btn-outline-light btn-lg">Login</a>
            @endauth
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col">
                    <h2>Why Choose FindNest?</h2>
                    <p class="text-muted">Everything you need for comfortable student living</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card p-4 text-center">
                        <div class="feature-icon mb-3" style="font-size: 3rem;">🏠</div>
                        <h4>Verified Properties</h4>
                        <p class="text-muted">Browse through trusted hostels and rooms with verified listings and photos</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card p-4 text-center">
                        <div class="feature-icon mb-3" style="font-size: 3rem;">👯</div>
                        <h4>Roommate Matching</h4>
                        <p class="text-muted">Find compatible roommates based on preferences, habits, and lifestyle</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card p-4 text-center">
                        <div class="feature-icon mb-3" style="font-size: 3rem;">💳</div>
                        <h4>Secure Payments</h4>
                        <p class="text-muted">Book with confidence using secure Khalti and eSewa payment integration</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col">
                    <h2>How It Works</h2>
                </div>
            </div>
            
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="step-number">1</div>
                    <h5>Sign Up</h5>
                    <p>Create your account as student or property owner</p>
                </div>
                <div class="col-md-3">
                    <div class="step-number">2</div>
                    <h5>Search & Filter</h5>
                    <p>Find properties or roommates using smart filters</p>
                </div>
                <div class="col-md-3">
                    <div class="step-number">3</div>
                    <h5>Book & Pay</h5>
                    <p>Reserve your spot with secure online payment</p>
                </div>
                <div class="col-md-3">
                    <div class="step-number">4</div>
                    <h5>Move In</h5>
                    <p>Connect with roommates and enjoy your new home</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2024 FindNest. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>