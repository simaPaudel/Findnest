<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - FindNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f8;
            min-height: 100vh;
        }

        .dashboard-card {
            max-width: 600px;
            margin: 80px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .btn-find-roommates {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="dashboard-card">
        <div class="container text-center mt-5">
            <h1>Welcome, {{ auth()->user()->name }}!</h1>
            <p>This is your simple user dashboard.</p>
        </div>

        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</body>

</html>