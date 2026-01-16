<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FindNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">🏠 FindNest</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">Welcome, {{ Auth::user()->name }}!</span>
                <form method="POST" action="/logout" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <h1>Welcome to Your Dashboard! 🎉</h1>
                <p class="lead">You're successfully logged in as {{ Auth::user()->role }}.</p>
                
                <div class="row mt-5">
                    @if(Auth::user()->role === 'student')
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Find Properties</h5>
                                <p class="card-text">Browse available hostels and rooms</p>
                                <a href="/properties" class="btn btn-primary">Browse</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">My Bookings</h5>
                                <p class="card-text">View your current bookings</p>
                                <a href="/bookings" class="btn btn-outline-primary">View</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Roommate Finder</h5>
                                <p class="card-text">Find compatible roommates</p>
                                <a href="/roommate-finder" class="btn btn-outline-primary">Find</a>
                            </div>
                        </div>
                    </div>
                    @elseif(Auth::user()->role === 'owner')
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">My Properties</h5>
                                <p class="card-text">Manage your property listings</p>
                                <a href="/properties" class="btn btn-primary">Manage</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Add Property</h5>
                                <p class="card-text">List a new property</p>
                                <a href="/properties/create" class="btn btn-success">Add New</a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="mt-4">
                    <a href="/" class="btn btn-primary">Go to Home</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>