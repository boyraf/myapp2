@extends('layouts.app')

@section('title', 'Savings Overview')

@section('content')
<div class="content container-fluid">
    <h1 class="mb-4">Savings Statistics</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-3">
                <h4>Total Sacco Savings</h4>
                <p class="display-6">Ksh {{ number_format($totalSavings) }}</p>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <h4>All Savings</h4>
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Member</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($savings as $saving)
                    <tr>
                        <td>{{ $saving->id }}</td>
                        <td>{{ $saving->member->name ?? 'N/A' }}</td>
                        <td>KES {{ number_format($saving->amount, 2) }}</td>
                        <td>{{ $saving->created_at }}</td>
                        <td>
                            <span class="badge {{ $saving->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($saving->status) }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('admin.savings.toggleStatus', $saving->id) }}" method="POST" onsubmit="return confirm('Change status?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $saving->status === 'active' ? 'btn-danger' : 'btn-success' }}">
                                    {{ $saving->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No savings records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <!-- Savings Count Per Member -->
        <div class="col-md-6">
            <h4>Total Savings Per Member (Count)</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Number of Savings Records</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($savingsCountPerMember as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->savings_count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Savings Amount Per Member -->
        <div class="col-md-6">
            <h4>Total Savings Per Member (Amount)</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Total Amount Saved</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($savingsAmountPerMember as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>Ksh {{ number_format($item->savings_sum_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
