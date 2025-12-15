@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Dividend Statement Report</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.reports.dividends.csv', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Distributions</h6>
                    <h2>{{ $totalDividends }}</h2>
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
                    <h6 class="text-muted">Average Dividend</h6>
                    <h2>{{ number_format($avgDividend, 2) }}</h2>
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
            <form method="GET" action="{{ route('admin.reports.dividends') }}" class="row g-3">
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

    <!-- Summary by Member -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Dividends by Member</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Member</th>
                        <th>Number of Distributions</th>
                        <th>Total Amount</th>
                        <th>Average per Distribution</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dividendsByMember as $dividend)
                        <tr>
                            <td>{{ $dividend->member->name ?? 'N/A' }}</td>
                            <td>{{ $dividend->count }}</td>
                            <td>{{ number_format($dividend->total, 2) }}</td>
                            <td>{{ $dividend->count > 0 ? number_format($dividend->total / $dividend->count, 2) : 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-3 text-muted">No dividend distributions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- All Dividends Table -->
    <div class="card">
        <div class="card-header">
            <h5>Dividend Transactions</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Transaction ID</th>
                        <th>Member</th>
                        <th>Amount</th>
                        <th>Description</th>
                        <th>Distribution Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dividends as $dividend)
                        <tr>
                            <td>#{{ $dividend->id }}</td>
                            <td>{{ $dividend->member->name ?? 'N/A' }}</td>
                            <td>{{ number_format($dividend->amount, 2) }}</td>
                            <td>{{ $dividend->description }}</td>
                            <td>{{ $dividend->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No dividends found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $dividends->appends(request()->query())->links() }}
    </div>
</div>
@endsection
