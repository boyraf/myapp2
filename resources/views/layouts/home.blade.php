<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MySacco')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/">
            <img src="/logo.png" alt="Logo" height="40" class="me-2"> MySacco
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto me-4">
                <li class="nav-item"><a href="/" class="nav-link fw-medium">Home</a></li>
                <li class="nav-item"><a href="/about" class="nav-link fw-medium">About</a></li>
                <li class="nav-item"><a href="/services" class="nav-link fw-medium">Services</a></li>
                <li class="nav-item"><a href="/contact" class="nav-link fw-medium">Contact</a></li>
            </ul>

            <!-- Login Dropdown -->
            <div class="dropdown me-2">
                <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    Login as
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('login') }}">Member</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.login') }}">Admin</a></li>
                </ul>
            </div>

            <a href="{{ route('signup') }}" class="btn btn-primary">Sign Up</a>
        </div>
    </div>
</nav>

<!-- PAGE CONTENT -->
<div class="py-4">
    @yield('content')
</div>

<!-- FOOTER -->
<footer class="py-3 bg-dark text-white text-center mt-5">
    <p class="mb-0">&copy; {{ date('Y') }} MySacco. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
