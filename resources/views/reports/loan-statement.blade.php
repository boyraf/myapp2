@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Loan Statement Report</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.reports.loans.csv', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Loans</h6>
                    <h2>{{ $totalLoans }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Amount</h6>
                    <h2>{{ number_format($totalAmount, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Outstanding Balance</h6>
                    <h2>{{ number_format($totalBalance, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Avg Loan Amount</h6>
                    <h2>{{ $totalLoans > 0 ? number_format($totalAmount / $totalLoans, 2) : 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Filters</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.loans') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" @selected(($filters['status'] ?? null) == 'pending')>Pending</option>
                        <option value="approved" @selected(($filters['status'] ?? null) == 'approved')>Approved</option>
                        <option value="paid" @selected(($filters['status'] ?? null) == 'paid')>Paid</option>
                        <option value="overdue" @selected(($filters['status'] ?? null) == 'overdue')>Overdue</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Member</label>
                    <select name="member_id" class="form-select">
                        <option value="">All Members</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" @selected(($filters['member_id'] ?? null) == $member->id)>
                                {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ $filters['from_date'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ $filters['to_date'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Loan ID</th>
                        <th>Member</th>
                        <th>Amount</th>
                        <th>Balance</th>
                        <th>Interest Rate</th>
                        <th>Status</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr>
                            <td>#{{ $loan->id }}</td>
                            <td>{{ $loan->member->name ?? 'N/A' }}</td>
                            <td>{{ number_format($loan->amount, 2) }}</td>
                            <td>{{ number_format($loan->balance, 2) }}</td>
                            <td>{{ $loan->interest_rate }}%</td>
                            <td>
                                <span class="badge bg-{{ $loan->status === 'paid' ? 'success' : ($loan->status === 'pending' ? 'warning' : 'info') }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td>{{ $loan->issue_date->format('Y-m-d') }}</td>
                            <td>{{ $loan->due_date?->format('Y-m-d') ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No loans found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $loans->appends(request()->query())->links() }}
    </div>
</div>
@endsection
