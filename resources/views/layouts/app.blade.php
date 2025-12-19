<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sacco Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        /* LAYOUT BASE */
        body {
    background: #f8f9fc !important; /* very light white-blue */
}

.content {
    background: transparent !important;
}

.dashboard-card {
    height: 190px;
    background: #ffffff !important;
}


        /* NAVBAR FIXED */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 2000;
            width: 100%;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 56px;
            left: 0;
            width: 240px;
            height: calc(100vh - 56px);
            background: #111827;
            color: #fff;
            padding-top: 10px;
            transition: width 0.3s ease;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        /* Sidebar links */
        .sidebar a {
            color: #cbd5e1;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: 0.2s ease;
            white-space: nowrap;
        }

        .sidebar a:hover {
            background: #374151;
            color: #fff;
        }

        /* Hide text when collapsed */
        .sidebar.collapsed span {
            display: none;
        }

        /* CONTENT */
        /* CONTENT */
.content {
    margin-left: 50px; /* keeps it next to sidebar */
    padding: 50px 30px; /* reduced top padding */
    transition: margin-left 0.3s ease;
}

.content.content-collapsed {
    margin-left: 70px;
}

        /* FOOTER */
        footer {
            position: fixed;
            bottom: 0;
            left: 240px;
            width: calc(100% - 240px);
            background: #e5e7eb;
            padding: 10px;
            text-align: center;
            border-top: 1px solid #ddd;
            transition: left 0.3s ease, width 0.3s ease;
        }

        footer.collapsed {
            left: 70px;
            width: calc(100% - 70px);
        }

        @media(max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                top: 0;
            }

            .sidebar.collapsed {
                width: 100%;
            }

            /*.content,
            .content.content-collapsed {
                margin-left: 0;
                padding-top: 120px;
            }*/

            footer,
            footer.collapsed {
                left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <button id="toggleSidebar" class="btn btn-outline-light me-3">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="/admin">Sacco Admin</a>
        </div>
    </nav>

{{-- SIDEBAR --}}
<div class="sidebar" id="sidebar">
    <a href="/admin"><i class="fas fa-home"></i> <span>Dashboard</span></a>
    <a href="/admin/members"><i class="fas fa-users"></i> <span>Members</span></a>

    <!-- Savings -->
    <a data-bs-toggle="collapse" href="#savingsMenu" role="button" aria-expanded="false" aria-controls="savingsMenu">
        <i class="fa-solid fa-piggy-bank"></i> <span>Savings</span>
        <i class="fas fa-chevron-down ms-auto"></i>
    </a>
    <div class="collapse ps-4" id="savingsMenu">
        <a href="/admin/savings" class="d-block py-1">Overview</a>
        <a href="/admin/savings/action" class="d-block py-1">Action</a>
    </div>

    <!-- Loans -->
    <a data-bs-toggle="collapse" href="#loansMenu" role="button" aria-expanded="false" aria-controls="loansMenu">
        <i class="fas fa-hand-holding-usd"></i> <span>Loans</span>
        <i class="fas fa-chevron-down ms-auto"></i>
    </a>
    <div class="collapse ps-4" id="loansMenu">
        <a href="/admin/loans" class="d-block py-1">Overview</a>
        <a href="/admin/loans/pending" class="d-block py-1">Action</a>
    </div>

    <!-- Repayments -->
    <a data-bs-toggle="collapse" href="#repaymentsMenu" role="button" aria-expanded="false" aria-controls="repaymentsMenu">
        <i class="fas fa-money-bill-transfer"></i> <span>Repayments</span>
        <i class="fas fa-chevron-down ms-auto"></i>
    </a>
    <div class="collapse ps-4" id="repaymentsMenu">
        <a href="/admin/repayments" class="d-block py-1">Overview</a>
        <a href="/admin/repayments/action" class="d-block py-1">Action</a>
    </div>

    <a href="/admin/transactions"><i class="fas fa-receipt"></i> <span>Transactions</span></a>
    <a href="/admin/auditlogs"><i class="fas fa-clipboard-check"></i> <span>Audit Logs</span></a>
    
    <form action="{{ route('admin.logout') }}" method="POST" class="m-0 p-0">
        @csrf
        <button type="submit" class="btn btn-link text-start text-decoration-none w-100 d-flex align-items-center gap-2" style="color: #cbd5e1; padding: 12px 18px;">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
        </button>
    </form>
</div>


    {{-- MAIN CONTENT --}}
    <div class="content" id="content" style="position: fixed; top: 10; left: 160px; width: calc(100% - 250px); height: 100%; padding: 20px 30px; overflow-y: auto; background-color: #f9f9f9; box-sizing: border-box;">
    @yield('content')
</div>


    {{-- FOOTER --}}
    <footer id="footer">
        SACCO Admin Panel • {{ date('Y') }}
    </footer>

    <script>
        // Sidebar toggle logic
        const sidebar = document.getElementById("sidebar");
        const content = document.getElementById("content");
        const footer = document.getElementById("footer");
        const toggleBtn = document.getElementById("toggleSidebar");

        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("collapsed");
            content.classList.toggle("content-collapsed");
            footer.classList.toggle("collapsed");
        });
    </script>

</body>
</html>
