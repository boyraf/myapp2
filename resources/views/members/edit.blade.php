@extends('layouts.app')

@section('title', 'Edit Member')

@section('content')
<div class="container" style="max-width:500px; margin-top:50px;">
    <h2 class="mb-3">Edit Member</h2>

    <form action="{{ route('admin.members.update', $member->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="name" placeholder="Name" class="form-control mb-2" value="{{ old('name', $member->name) }}" required>
        <input type="email" name="email" placeholder="Email" class="form-control mb-2" value="{{ old('email', $member->email) }}" required>
        <input type="text" name="phone" placeholder="Phone" class="form-control mb-2" value="{{ old('phone', $member->phone) }}" required>
        <input type="text" name="id_number" placeholder="ID Number" class="form-control mb-2" value="{{ old('id_number', $member->id_number) }}" required>
        <input type="date" name="date_of_birth" placeholder="Date of Birth" class="form-control mb-2" value="{{ old('date_of_birth', $member->date_of_birth) }}">
        <input type="text" name="address" placeholder="Address" class="form-control mb-2" value="{{ old('address', $member->address) }}">
        <button type="submit" class="btn btn-primary w-100">Update Member</button>
    </form>
</div>
@endsection
