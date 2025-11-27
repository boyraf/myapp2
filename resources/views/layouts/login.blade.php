<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa; /* light gray background */
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .login-container {
            max-width: 400px;
            margin: auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        footer {
            text-align: center;
            margin-top: auto;
            padding: 1rem 0;
            color: #6c757d;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2 class="text-center mb-4">@yield('title')</h2>

        @yield('content')
    </div>

    <footer>
        &copy; {{ date('Y') }} MySacco. All rights reserved.
    </footer>

    <!-- Bootstrap JS (optional for components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
