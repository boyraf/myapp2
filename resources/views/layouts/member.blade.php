<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sacco Member Portal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fc;
        }

        /* NAVBAR */
        .navbar {
            background: #0d6efd;
        }

        .navbar-nav .nav-link {
            color: #fff;
            transition: 0.2s ease;
        }

        .navbar-nav .nav-link:hover {
            background: rgba(255,255,255,0.2);
            border-radius: 6px;
            color: #fff;
        }

        /* Dropdown */
        .dropdown-menu {
            min-width: 220px;
        }

        .dropdown-item {
            cursor: pointer;
        }

        .dropdown-item:hover {
            background: #e9ecef;
        }

        /* CONTENT */
        .content {
            margin-top: 70px;
            padding: 30px;
            min-height: 80vh;
        }

        /* FOOTER */
        footer {
            padding: 15px 0;
            text-align: center;
            background: #e5e7eb;
            border-top: 1px solid #ddd;
        }

        @media(max-width: 768px) {
            .content {
                margin-top: 100px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('member.dashboard') }}">MySacco</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-2">

                    <!-- Dashboard -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dashboardDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Dashboard
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dashboardDropdown">
                            <li><a class="dropdown-item" href="{{ route('member.dashboard.savings') }}">Total Savings</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.dashboard.loans') }}">Loan Balance</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.dashboard.limit') }}">Loan Limit</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.dashboard.pending') }}">Pending Loan</a></li>
                        </ul>
                    </li>

                    <!-- My Savings -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="savingsDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            My Savings
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="savingsDropdown">
                            <li><a class="dropdown-item" href="{{ route('member.savings.statement') }}">View Savings Statement</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.savings.deposit') }}">Deposit Funds</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.savings.withdraw') }}">Request Withdrawal</a></li>
                        </ul>
                    </li>

                    <!-- My Loans -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="loansDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            My Loans
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="loansDropdown">
                            <li><a class="dropdown-item" href="{{ route('member.loans.apply') }}">Apply for Loan</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.loans.history') }}">Loan History</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.loans.schedule') }}">Repayment Schedule</a></li>
                        </ul>
                    </li>

                    <!-- Transactions -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="transactionsDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Transactions
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="transactionsDropdown">
                            <li><a class="dropdown-item" href="{{ route('member.transactions.recent') }}">Recent Transactions</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.transactions.download') }}">Download Statement</a></li>
                        </ul>
                    </li>

                    <!-- My Repayments -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="repaymentsDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            My Repayments
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="repaymentsDropdown">
                            <li><a class="dropdown-item" href="{{ route('member.repayments.history') }}">Repayment History</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.repayments.schedule') }}">Upcoming Schedule</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.repayments.make') }}">Make a Payment</a></li>
                        </ul>
                    </li>

                    <!-- My Shares -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="sharesDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            My Shares
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="sharesDropdown">
                            <li><a class="dropdown-item" href="{{ route('member.shares.index') }}">View My Shares</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.shares.buy') }}">Buy Shares</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.shares.sell') }}">Sell Shares</a></li>
                        </ul>
                    </li>

                    <!-- Profile -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Profile
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="{{ route('member.profile.view') }}">View Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.profile.update') }}">Update Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.profile.password') }}">Change Password</a></li>
                        </ul>
                    </li>

                    <!-- Support -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="supportDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Support
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="supportDropdown">
                            <li><a class="dropdown-item" href="{{ route('member.support.contact') }}">Contact Us</a></li>
                            <li><a class="dropdown-item" href="{{ route('member.support.faq') }}">FAQs</a></li>
                        </ul>
                    </li>

                    <!-- Logout -->
                    <li class="nav-item">
                        <form action="{{ route('member.logout') }}" method="POST" class="m-0 d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link p-0" style="text-decoration: none;">Logout</button>
                        </form>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="container content">
        @yield('content')
    </div>

    <!-- FOOTER -->
    <footer>
        SACCO Member Portal • {{ date('Y') }}
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
