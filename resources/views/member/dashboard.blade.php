@extends('layouts.member') <!-- Assuming the layout is saved as layouts/member.blade.php -->

@section('content')
<div class="row g-4">
    <!-- Total Savings Card -->
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3 dashboard-card">
            <i class="fas fa-piggy-bank fa-2x text-primary mb-2"></i>
            <h5>Total Savings</h5>
            <p>Ksh {{ number_format($totalSavings ?? 0) }}</p>
        </div>
    </div>

    <!-- Loan Balance Card -->
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3 dashboard-card">
            <i class="fas fa-hand-holding-usd fa-2x text-success mb-2"></i>
            <h5>Loan Balance</h5>
            <p>Ksh {{ number_format($loanBalance ?? 0) }}</p>
        </div>
    </div>

    <!-- Loan Limit Card -->
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3 dashboard-card">
            <i class="fas fa-wallet fa-2x text-warning mb-2"></i>
            <h5>Loan Limit</h5>
            <p>Ksh {{ number_format($loanLimit ?? 0) }}</p>
        </div>
    </div>

    <!-- Pending Loan Card -->
    <div class="col-md-3">
        <div class="card shadow-sm text-center p-3 dashboard-card">
            <i class="fas fa-hourglass-half fa-2x text-danger mb-2"></i>
            <h5>Pending Loan</h5>
            <p>{{ $pendingLoanStatus ?? 'None' }}</p>
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
            <a href="{{ route('member.profile.update') }}" class="btn btn-outline-secondary">
                <i class="fas fa-user-edit me-1"></i> Update Profile
            </a>
        </div>
    </div>
</div>
@endsection
