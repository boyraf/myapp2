@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Share Holdings Report</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.reports.shares.csv', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Shares</h6>
                    <h2>{{ $totalShares }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Total Value</h6>
                    <h2>{{ number_format($totalValue, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted">Avg Share Price</h6>
                    <h2>{{ number_format($avgSharePrice, 2) }}</h2>
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
            <form method="GET" action="{{ route('admin.reports.shares') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Member</label>
                    <select name="member_id" class="form-select">
                        <option value="">All Members & Pool</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" @selected(($filters['member_id'] ?? null) == $member->id)>
                                {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
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
            <h5>Holdings by Member</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Member</th>
                        <th>Total Quantity</th>
                        <th>Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sharesByMember as $holding)
                        <tr>
                            <td>{{ $holding->member->name ?? 'N/A' }}</td>
                            <td>{{ $holding->total_quantity }}</td>
                            <td>{{ number_format($holding->total_value, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-3 text-muted">No shares held by members</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- All Shares Table -->
    <div class="card">
        <div class="card-header">
            <h5>All Shares</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Share ID</th>
                        <th>Member</th>
                        <th>Quantity</th>
                        <th>Price per Share</th>
                        <th>Total Value</th>
                        <th>Status</th>
                        <th>Acquired Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shares as $share)
                        <tr>
                            <td>#{{ $share->id }}</td>
                            <td>{{ $share->member->name ?? 'Pool (Unassigned)' }}</td>
                            <td>{{ $share->quantity }}</td>
                            <td>{{ number_format($share->price_per_share, 2) }}</td>
                            <td>{{ number_format($share->total_value, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $share->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($share->status ?? 'active') }}
                                </span>
                            </td>
                            <td>{{ $share->acquired_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No shares found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $shares->appends(request()->query())->links() }}
    </div>
</div>
@endsection
