@extends('layouts.app')

@section('title', 'Add Saving')

@section('content')
<div class="container" style="max-width:500px; margin-top:50px;">
    <h2 class="mb-3">Add Saving</h2>

    <form action="{{ route('admin.savings.store') }}" method="POST">
        @csrf
        <select name="member_id" class="form-control mb-2" required>
            <option value="">Select Member</option>
            @foreach($members as $member)
                <option value="{{ $member->id }}">{{ $member->name }}</option>
            @endforeach
        </select>
        <input type="number" name="amount" placeholder="Amount" class="form-control mb-2" required>
        <input type="text" name="type" placeholder="Type (e.g., Cash, Bank Transfer)" class="form-control mb-2" required>
        <button type="submit" class="btn btn-primary w-100">Add Saving</button>
    </form>
</div>
@endsection
