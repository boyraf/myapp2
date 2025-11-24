<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SACCO Admin Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            display: flex;
            flex: 1;
        }

        /* Sidebar */
        #sidebar {
            width: 220px;
            min-height: 100vh;
            background-color: #343a40;
            color: #fff;
            position: sticky;
            top: 0;
        }

        #sidebar .nav-link {
            color: #fff;
        }

        #sidebar .nav-link:hover {
            background-color: #495057;
            color: #fff;
        }

        /* Content */
        #main-content {
            flex: 1;
            padding: 2rem;
        }

        /* Footer */
        footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 0.75rem 0;
            font-size: 0.9rem;
        }

        .card-btn {
            width: 100%;
        }
    </style>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">SACCO Admin Portal</a>
  </div>
</nav>

<div class="content-wrapper">
    <!-- Sidebar -->
    <div id="sidebar" class="d-flex flex-column p-3">
        <h5 class="text-center mb-4">Menu</h5>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="nav-link">Dashboard</a>
            </li>
            <li>
                <a href="#" class="nav-link">Members</a>
            </li>
            <li>
                <a href="#" class="nav-link">Savings</a>
            </li>
            <li>
                <a href="#" class="nav-link">Loans</a>
            </li>
            <li>
                <a href="#" class="nav-link">Repayments</a>
            </li>
            <li>
                <a href="#" class="nav-link">Transactions</a>
            </li>
            <li>
                <a href="#" class="nav-link">Reports</a>
            </li>
            <li>
                <a href="#" class="nav-link">Settings</a>
            </li>
            <li>
                <a href="#" class="nav-link text-danger">Logout</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div id="main-content">
        <div class="text-center mb-5">
            <h1 class="fw-bold">Welcome to the SACCO Admin Portal</h1>
            <p class="text-muted">Manage members, loans, savings, and reports from one place.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Members</h5>
                        <p class="card-text">View and manage all SACCO members.</p>
                        <a href="#" class="btn btn-primary card-btn">Manage Members</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Savings</h5>
                        <p class="card-text">Track member savings deposits and withdrawals.</p>
                        <a href="#" class="btn btn-success card-btn">Manage Savings</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Loans</h5>
                        <p class="card-text">View, approve, and track member loans.</p>
                        <a href="#" class="btn btn-warning card-btn">Manage Loans</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Repayments</h5>
                        <p class="card-text">Monitor loan repayments and balances.</p>
                        <a href="#" class="btn btn-info card-btn">Manage Repayments</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Transactions</h5>
                        <p class="card-text">View all financial transactions.</p>
                        <a href="#" class="btn btn-secondary card-btn">View Transactions</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">Reports</h5>
                        <p class="card-text">Generate activity, loan, and financial reports.</p>
                        <a href="#" class="btn btn-dark card-btn">Generate Reports</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    &copy; 2025 SACCO Admin Portal. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
