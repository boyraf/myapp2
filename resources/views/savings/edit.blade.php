@extends('layouts.app')

@section('title', 'Edit Saving')

@section('content')
<div class="container" style="max-width:500px; margin-top:50px;">
    <h2 class="mb-3">Edit Saving</h2>

    <form action="{{ route('admin.savings.update', $saving->id) }}" method="POST">
        @csrf
        @method('PUT')
        <select name="member_id" class="form-control mb-2" disabled>
            @foreach($members as $member)
                <option value="{{ $member->id }}" {{ $saving->member_id == $member->id ? 'selected' : '' }}>
                    {{ $member->name }}
                </option>
            @endforeach
        </select>
        <input type="number" name="amount" placeholder="Amount" class="form-control mb-2" value="{{ $saving->amount }}" required>
        <input type="text" name="type" placeholder="Type" class="form-control mb-2" value="{{ $saving->type }}" required>
        <button type="submit" class="btn btn-primary w-100">Update Saving</button>
    </form>
</div>
@endsection
