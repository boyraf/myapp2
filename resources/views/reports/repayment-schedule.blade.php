@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Repayment Schedule Report</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.reports.repayments.csv', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Repayments</h6>
                    <h2>{{ $totalRepayments }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Amount Paid</h6>
                    <h2>{{ number_format($totalAmountPaid, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Average Repayment</h6>
                    <h2>{{ number_format($avgRepayment, 2) }}</h2>
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
            <form method="GET" action="{{ route('admin.reports.repayments') }}" class="row g-3">
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
                <div class="col-md-3">
                    <label class="form-label">Loan</label>
                    <select name="loan_id" class="form-select">
                        <option value="">All Loans</option>
                        @foreach($loans as $loan)
                            <option value="{{ $loan->id }}" @selected(($filters['loan_id'] ?? null) == $loan->id)>
                                Loan #{{ $loan->id }} ({{ $loan->member->name ?? 'N/A' }})
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

    <!-- Repayments Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Repayment ID</th>
                        <th>Loan ID</th>
                        <th>Member</th>
                        <th>Amount Paid</th>
                        <th>Balance After</th>
                        <th>Payment Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($repayments as $repayment)
                        <tr>
                            <td>#{{ $repayment->id }}</td>
                            <td>#{{ $repayment->loan_id }}</td>
                            <td>{{ $repayment->loan->member->name ?? 'N/A' }}</td>
                            <td>{{ number_format($repayment->amount_paid, 2) }}</td>
                            <td>{{ number_format($repayment->balance_after_payment, 2) }}</td>
                            <td>{{ $repayment->payment_date->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No repayments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $repayments->appends(request()->query())->links() }}
    </div>
</div>
@endsection
