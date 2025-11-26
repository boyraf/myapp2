@extends('layouts.app')

@section('title', 'Add Repayment')

@section('content')
<div class="container" style="max-width:500px; margin-top:50px;">
    <h2 class="mb-3">Add Repayment</h2>

    <form action="{{ route('admin.repayments.store') }}" method="POST">
        @csrf
        <select name="loan_id" class="form-control mb-2" required>
            <option value="">Select Loan</option>
            @foreach($loans as $loan)
                <option value="{{ $loan->id }}">
                    {{ $loan->member->name }} - ${{ $loan->amount }} - Balance: ${{ $loan->balance }}
                </option>
            @endforeach
        </select>
        <input type="number" name="amount_paid" placeholder="Amount Paid" class="form-control mb-2" required>
        <input type="date" name="payment_date" class="form-control mb-2" required>
        <button type="submit" class="btn btn-primary w-100">Add Repayment</button>
    </form>
</div>
@endsection
