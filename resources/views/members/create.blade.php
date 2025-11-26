@extends('layouts.app')

@section('title', 'Add Member')

@section('content')
<div class="container" style="max-width:500px; margin-top:50px;">
    <h2 class="mb-3">Add Member</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.members.store') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Name" class="form-control mb-2" value="{{ old('name') }}" required>
        <input type="email" name="email" placeholder="Email" class="form-control mb-2" value="{{ old('email') }}" required>
        <input type="text" name="phone" placeholder="Phone" class="form-control mb-2" value="{{ old('phone') }}" required>
        <input type="text" name="id_number" placeholder="ID Number" class="form-control mb-2" value="{{ old('id_number') }}" required>
        <input type="date" name="date_of_birth" placeholder="Date of Birth" class="form-control mb-2" value="{{ old('date_of_birth') }}">
        <input type="text" name="address" placeholder="Address" class="form-control mb-2" value="{{ old('address') }}">
        <button type="submit" class="btn btn-primary w-100">Add Member</button>
    </form>
</div>
@endsection
