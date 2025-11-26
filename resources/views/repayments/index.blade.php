@extends('layouts.app')

@section('title', 'Repayments Overview')

@section('content')
<div class="content container-fluid">
    <h1 class="mb-4">Repayments Statistics</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-3">
                <h4>Total Repayments</h4>
                <p class="display-6">{{ $totalRepayments }}</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h4>Total Amount Repaid</h4>
                <p class="display-6">KES {{ number_format($totalRepaymentAmount, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <h4>All Repayments</h4>
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Member</th>
                        <th>Loan ID</th>
                        <th>Amount Paid</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($repayments as $repayment)
                    <tr>
                        <td>{{ $repayment->id }}</td>
                        <td>{{ $repayment->loan->member->name ?? 'N/A' }}</td>
                        <td>{{ $repayment->loan_id }}</td>
                        <td>KES {{ number_format($repayment->amount_paid, 2) }}</td>
                        <td>{{ $repayment->payment_date }}</td>
                        <td>
                            <span class="badge {{ $repayment->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($repayment->status) }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.repayments.toggleStatus', $repayment->id) }}" method="POST" onsubmit="return confirm('Change status?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $repayment->status === 'active' ? 'btn-danger' : 'btn-success' }}">
                                    {{ $repayment->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No repayments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <!-- Count per member -->
        <div class="col-md-6">
            <h4>Repayment Count Per Member</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Number of Repayments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($repaymentsPerMemberCount as $r)
                    <tr>
                        <td>{{ $r->member_name }}</td>
                        <td>{{ $r->total_repayments }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Amount per member -->
        <div class="col-md-6">
            <h4>Total Amount Paid Per Member</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($repaymentsPerMemberAmount as $r)
                    <tr>
                        <td>{{ $r->member_name }}</td>
                        <td>KES {{ number_format($r->total_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
