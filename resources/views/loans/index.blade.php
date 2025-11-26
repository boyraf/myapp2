@extends('layouts.app')

@section('title', 'Loans Overview')

@section('content')
<div class="content container-fluid ">
    <h1 class="mb-4">Loan Statistics</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-3">
                <h4>Total Sacco Loans</h4>
                <p class="display-6">{{ $totalLoans }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-4">
            <h4>All Loans</h4>
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Member</th>
                        <th>Amount</th>
                        <th>Issue Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                    <tr>
                        <td>{{ $loan->id }}</td>
                        <td>{{ $loan->member->name ?? 'N/A' }}</td>
                        <td>KES {{ number_format($loan->amount, 2) }}</td>
                        <td>{{ $loan->issue_date }}</td>
                        <td>
                            <span class="badge
                                @if($loan->status === 'approved') bg-success
                                @elseif($loan->status === 'pending') bg-warning
                                @elseif($loan->status === 'paid') bg-primary
                                @elseif($loan->status === 'overdue') bg-danger
                                @else bg-secondary
                                @endif">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('admin.loans.edit', $loan->id) }}" class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('admin.loans.toggleStatus', $loan->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to change this loan status?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm
                                    @if($loan->status === 'pending') btn-success
                                    @elseif($loan->status === 'approved') btn-warning
                                    @else btn-secondary
                                    @endif">
                                    @if($loan->status === 'pending')
                                        Approve
                                    @elseif($loan->status === 'approved')
                                        Set Pending
                                    @else
                                        N/A
                                    @endif
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No loans found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h4>Total Loans Per Member (Count)</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Number of Loans</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loansPerMemberCount as $loan)
                    <tr>
                        <td>{{ $loan->member->name ?? 'N/A' }}</td>
                        <td>{{ $loan->total_loans }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <h4>Total Loans Per Member (Amount)</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loansPerMemberAmount as $loan)
                    <tr>
                        <td>{{ $loan->member->name ?? 'N/A' }}</td>
                        <td>{{ number_format($loan->total_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
