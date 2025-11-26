@extends('layouts.app')

@section('title', 'Edit Repayment')

@section('content')
<div class="container" style="max-width:500px; margin-top:50px;">
    <h2 class="mb-3">Edit Repayment</h2>

    <form action="{{ route('admin.repayments.update', $repayment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <select name="loan_id" class="form-control mb-2" disabled>
            <option value="{{ $repayment->loan->id }}">
                {{ $repayment->loan->member->name }} - ${{ $repayment->loan->amount }}
            </option>
        </select>
        <input type="number" name="amount_paid" placeholder="Amount Paid" class="form-control mb-2" value="{{ $repayment->amount_paid }}" required>
        <input type="date" name="payment_date" class="form-control mb-2" value="{{ $repayment->payment_date }}" required>
        <button type="submit" class="btn btn-primary w-100">Update Repayment</button>
    </form>
</div>
@endsection
