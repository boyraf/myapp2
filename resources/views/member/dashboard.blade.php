@extends('layouts.member')

@section('content')
<div class="row g-4">
    <!-- Total Savings Card -->
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3 dashboard-card">
            <i class="fas fa-piggy-bank fa-2x text-primary mb-2"></i>
            <h5>Total Savings</h5>
            <!-- Display total savings from database -->
            <p>Ksh {{ number_format($totalSavings ?? 0) }}</p>
        </div>
    </div>

    <!-- Loan Balance Card -->
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3 dashboard-card">
            <i class="fas fa-hand-holding-usd fa-2x text-success mb-2"></i>
            <h5>Loan Balance</h5>
            <!-- Display current loan balance from active loans -->
            <p>Ksh {{ number_format($loanBalance ?? 0) }}</p>
        </div>
    </div>

    <!-- Loan Limit Card -->
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3 dashboard-card">
            <i class="fas fa-wallet fa-2x text-warning mb-2"></i>
            <h5>Loan Limit</h5>
            <!-- Display loan limit (3x savings per SACCO rules) -->
            <p>Ksh {{ number_format($loanLimit ?? 0) }}</p>
        </div>
    </div>

    <!-- Pending Loan Card -->
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3 dashboard-card">
            <i class="fas fa-hourglass-half fa-2x text-danger mb-2"></i>
            <h5>Pending Loans</h5>
            <!-- Display count of loans awaiting approval -->
            <p>{{ $pendingLoans ?? 0 }}</p>
        </div>
    </div>
</div>

<!-- Shares & Recent Activity Section -->
<div class="row g-4 mt-3">
    <!-- Shares Card -->
    <div class="col-md-6">
        <div class="card shadow-sm p-3">
            <h5 class="mb-3">
                <i class="fas fa-certificate text-success me-2"></i> My Shares
            </h5>
            <!-- Display share count and provide link to shares management -->
            <p class="fs-5">You own <strong>{{ $shareCount ?? 0 }}</strong> shares</p>
            <a href="{{ route('member.shares.index') }}" class="btn btn-sm btn-success">Manage Shares</a>
        </div>
    </div>

    <!-- Recent Transactions Card -->
    <div class="col-md-6">
        <div class="card shadow-sm p-3">
            <h5 class="mb-3">
                <i class="fas fa-exchange-alt text-info me-2"></i> Recent Activity
            </h5>
            <!-- Display last 5 transactions for quick reference -->
            @if($recentTransactions->count() > 0)
                <ul class="list-unstyled">
                    @foreach($recentTransactions as $txn)
                        <li class="mb-2">
                            <small class="text-muted">{{ optional($txn->created_at)->diffForHumans() }}</small><br>
                            <strong>{{ ucfirst(str_replace('_', ' ', $txn->type)) }}</strong> - Ksh {{ number_format($txn->amount) }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No transactions yet</p>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-5">
    <div class="card shadow-sm p-3">
        <h5 class="mb-3">Quick Actions</h5>
        <div class="d-flex flex-wrap gap-3">
            <a href="{{ route('member.loans.apply') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Apply for Loan
            </a>
            <a href="{{ route('member.savings.statement') }}" class="btn btn-outline-primary">
                <i class="fas fa-file-invoice-dollar me-1"></i> View Savings Statement
            </a>
            <a href="{{ route('member.shares.buy') }}" class="btn btn-outline-success">
                <i class="fas fa-certificate me-1"></i> Buy Shares
            </a>
            <a href="{{ route('member.profile.update') }}" class="btn btn-outline-secondary">
                <i class="fas fa-user-edit me-1"></i> Update Profile
            </a>
        </div>
    </div>
</div>
@endsection