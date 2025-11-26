@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="content container-fluid">

    <!-- Title -->
    <div class="text-center mb-5">
        <h1 class="fw-bold">Admin Dashboard</h1>
        <p class="text-muted">Manage members, loans, savings & transactions</p>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card p-3 shadow-sm">
                <h5 class="text-center">Member Status</h5>
                <canvas id="memberPieChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 shadow-sm">
                <h5 class="text-center">Loans Issued (Last 6 Months)</h5>
                <canvas id="loanLineChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards: 5 cards with aligned buttons -->
    <div class="row g-4 mb-5">
        <!-- Members Card -->
        <div class="col-md-4">
            <div class="dashboard-card shadow-sm d-flex flex-column p-3">
                <div>
                    <div class="card-icon bg-primary text-white mb-2">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5 class="card-title mb-2 text-center">Members</h5>
                    <p class="card-value text-center">Total: {{ $totalMembers }}</p>
                </div>
                <a href="{{ route('admin.members') }}" class="btn btn-primary btn-sm mt-auto">View</a>
            </div>
        </div>

        <!-- Loans Card -->
        <div class="col-md-4">
            <div class="dashboard-card shadow-sm d-flex flex-column p-3">
                <div>
                    <div class="card-icon bg-success text-white mb-2">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <h5 class="card-title mb-2 text-center">Loans</h5>
                    <p class="card-value text-center">
                        Total: {{ $totalLoans }} <br>
                        KES {{ number_format($totalLoanAmount, 2) }}
                    </p>
                </div>
                <a href="{{ route('admin.loans') }}" class="btn btn-success btn-sm mt-auto">View</a>
            </div>
        </div>

        <!-- Savings Card -->
        <div class="col-md-4">
            <div class="dashboard-card shadow-sm d-flex flex-column p-3">
                <div>
                    <div class="card-icon bg-warning text-white mb-2">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                    <h5 class="card-title mb-2 text-center">Savings</h5>
                    <p class="card-value text-center">KES {{ number_format($totalSavings, 2) }}</p>
                </div>
                <a href="{{ route('admin.savings') }}" class="btn btn-warning btn-sm mt-auto text-white">View</a>
            </div>
        </div>

        <!-- Repayments Card -->
        <div class="col-md-4">
            <div class="dashboard-card shadow-sm d-flex flex-column p-3">
                <div>
                    <div class="card-icon bg-info text-white mb-2">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h5 class="card-title mb-2 text-center">Repayments</h5>
                    <p class="card-value text-center">KES {{ number_format($totalRepayments, 2) }}</p>
                </div>
                <a href="{{ route('admin.repayments') }}" class="btn btn-info btn-sm mt-auto text-white">View</a>
            </div>
        </div>

        <!-- Transactions Card -->
        <div class="col-md-4">
            <div class="dashboard-card shadow-sm d-flex flex-column p-3">
                <div>
                    <div class="card-icon bg-dark text-white mb-2">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h5 class="card-title mb-2 text-center">Transactions</h5>
                    <p class="card-value text-center">Total: {{ $totalTransactions }}</p>
                </div>
                <a href="{{ route('admin.transactions') }}" class="btn btn-dark btn-sm mt-auto">View</a>
            </div>
        </div>
    </div>

    <!-- Recent Activity Tables -->
    <div class="mt-5">

        <h3 class="mb-3">Recent Loans</h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Issue Date</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentLoans as $loan)
                <tr>
                    <td>{{ $loan->member->name }}</td>
                    <td>KES {{ number_format($loan->amount, 2) }}</td>
                    <td>{{ $loan->status }}</td>
                    <td>{{ $loan->issue_date }}</td>
                    <td>{{ $loan->due_date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h3 class="mt-5 mb-3">Recent Repayments</h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Loan Amount</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentRepayments as $repayment)
                <tr>
                    <td>{{ $repayment->loan->member->name }}</td>
                    <td>KES {{ number_format($repayment->loan->amount, 2) }}</td>
                    <td>KES {{ number_format($repayment->amount_paid, 2) }}</td>
                    <td>KES {{ number_format($repayment->balance_after_payment, 2) }}</td>
                    <td>{{ $repayment->payment_date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h3 class="mt-5 mb-3">Recent Transactions</h3>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentTransactions as $txn)
                <tr>
                    <td>{{ $txn->member->name }}</td>
                    <td>KES {{ number_format($txn->amount, 2) }}</td>
                    <td>{{ $txn->type }}</td>
                    <td>{{ $txn->transaction_date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pie Chart: Members
    const ctxPie = document.getElementById('memberPieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Active', 'Inactive'],
            datasets: [{
                data: [{{ $activeMembers }}, {{ $inactiveMembers }}],
                backgroundColor: ['#28a745', '#dc3545']
            }]
        },
        options: { responsive: true }
    });

    // Line Chart: Loans per month
    const ctxLine = document.getElementById('loanLineChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: @json($months),
            datasets: [{
                label: 'Loans Issued (KES)',
                data: @json($loanAmounts),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: '#36A2EB',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: { responsive: true }
    });
</script>
@endsection
