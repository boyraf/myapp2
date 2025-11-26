@extends('layouts.app')

@section('title', 'Add Loan')

@section('content')
<div class="container" style="max-width:500px; margin-top:50px;">
    <h2 class="mb-3">Add Loan</h2>

    <form action="{{ route('admin.loans.store') }}" method="POST">
        @csrf
        <select name="member_id" class="form-control mb-2" required>
            <option value="">Select Member</option>
            @foreach($members as $member)
                <option value="{{ $member->id }}">{{ $member->name }}</option>
            @endforeach
        </select>
        <input type="number" name="amount" placeholder="Loan Amount" class="form-control mb-2" required>
        <input type="number" step="0.01" name="interest_rate" placeholder="Interest Rate %" class="form-control mb-2" required>
        <input type="number" name="repayment_period" placeholder="Repayment Period (months)" class="form-control mb-2" required>
        <button type="submit" class="btn btn-primary w-100">Add Loan</button>
    </form>
</div>
@endsection
