<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FindNest')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    

    <style>
        html {
            width: 100%;
            overflow-x: hidden;
            -webkit-text-size-adjust: 100%;
        }

        body {
            min-height: 100vh;
            background: #f5f5f5;
            overflow-x: hidden;
        }

        img,
        video,
        canvas,
        svg {
            max-width: 100%;
        }

        img,
        video {
            height: auto;
        }

        input,
        select,
        textarea,
        button {
            max-width: 100%;
            font: inherit;
        }

        .table-responsive,
        [class*="table-wrap"] {
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .container-center {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 24px 16px;
        }

        .card-center {
            width: 100%;
            max-width: 450px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            background: white;
        }

        .auth-logo {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 20px;
        }

        @media (max-width: 576px) {
            .container-center {
                align-items: flex-start;
                padding: 18px 10px;
            }

            .card-center {
                max-width: 100%;
                padding: 20px 16px;
                border-radius: 14px;
            }

            .auth-logo {
                font-size: 1.55rem;
                margin-bottom: 16px;
            }
        }

        @media (max-width: 380px) {
            .container-center {
                padding-left: 8px;
                padding-right: 8px;
            }

            .card-center {
                padding-left: 12px;
                padding-right: 12px;
            }
        }
    </style>
</head>

<body>

    <div class="container-center">
        <div class="card-center">
            <div class="auth-logo">
                <x-findnest-logo variant="stacked" size="lg" />
            </div>

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {!! Toastr::message() !!}
</body>

</html>
