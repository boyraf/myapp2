@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
<div class="content container-fluid">

    <div class="text-center mb-5">
        <h1 class="fw-bold">Transactions</h1>
        <p class="text-muted">Scan transactions by type and view details</p>
    </div>

    <!-- Filter by Type -->
    <div class="mb-4">
        <form method="GET" action="{{ route('admin.transactions') }}">
            <label for="type" class="form-label">Select Transaction Type:</label>
            <select name="type" id="type" class="form-select w-25 d-inline-block">
                <option value="all" {{ $type == 'all' ? 'selected' : '' }}>All</option>
                @foreach($transactionTypes as $t)
                    <option value="{{ $t }}" {{ $type == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary ms-2">Filter</button>
        </form>
    </div>

    <!-- Totals -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card p-3 shadow-sm">
                <h5>Total Transactions Count</h5>
                <h3>{{ $totalCount }}</h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 shadow-sm">
                <h5>Total Transactions Amount (KES)</h5>
                <h3>{{ number_format($totalAmount, 2) }}</h3>
            </div>
        </div>
    </div>

    <!-- Members who made transactions -->
    <div class="card p-3 shadow-sm mb-4">
        <h5>Members who did "{{ $type }}" transactions:</h5>
        <ul class="list-group list-group-flush mt-2">
            @forelse($members as $member)
                <li class="list-group-item">{{ $member->name }} ({{ $member->email }})</li>
            @empty
                <li class="list-group-item text-muted">No members found.</li>
            @endforelse
        </ul>
    </div>

    <!-- Transaction Details Table -->
    <div class="card p-3 shadow-sm">
        <h5>Transaction Details</h5>
        <div class="table-responsive mt-3">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Type</th>
                        <th>Amount (KES)</th>
                        <th>Balance After</th>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                        <tr>
                            <td>{{ $tx->member->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($tx->type) }}</td>
                            <td>{{ number_format($tx->amount, 2) }}</td>
                            <td>{{ number_format($tx->balance_after, 2) }}</td>
                            <td>{{ $tx->description }}</td>
                            <td>{{ $tx->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
