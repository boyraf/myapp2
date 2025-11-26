@extends('layouts.app')

@section('title', 'Edit Loan')

@section('content')
<div class="container" style="max-width:500px; margin-top:50px;">
    <h2 class="mb-3">Edit Loan</h2>

    <form action="{{ route('admin.loans.update', $loan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <select name="member_id" class="form-control mb-2" required>
            @foreach($members as $member)
                <option value="{{ $member->id }}" {{ $loan->member_id == $member->id ? 'selected' : '' }}>
                    {{ $member->name }}
                </option>
            @endforeach
        </select>
        <input type="number" name="amount" placeholder="Loan Amount" class="form-control mb-2" value="{{ $loan->amount }}" required>
        <input type="number" step="0.01" name="interest_rate" placeholder="Interest Rate %" class="form-control mb-2" value="{{ $loan->interest_rate }}" required>
        <input type="number" name="repayment_period" placeholder="Repayment Period (months)" class="form-control mb-2" value="{{ $loan->repayment_period }}" required>
        <select name="status" class="form-control mb-2" required>
            <option value="active" {{ $loan->status == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ $loan->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="btn btn-primary w-100">Update Loan</button>
    </form>
</div>
@endsection
