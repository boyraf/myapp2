@extends('layouts.app')

@section('title', 'Pending Loans')

@section('content')
<div class="container-fluid mt-4">
    <h1 class="h3 mb-4 text-gray-800">Pending Loans</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($loans->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Member</th>
                    <th>Amount</th>
                    <th>Term (months)</th>
                    <th>Applied On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loans as $loan)
                <tr>
                    <td>{{ $loan->member->name ?? 'N/A' }}</td>
                    <td>{{ number_format($loan->amount, 2) }}</td>
                    <td>{{ $loan->repayment_period }}</td>
                    <td>{{ $loan->created_at->format('d M, Y') }}</td>
                    <td class="d-flex gap-2">
                        <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                        </form>

                        <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p class="text-muted">No pending loans at the moment.</p>
    @endif
</div>
@endsection
