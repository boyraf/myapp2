@extends('layouts.app')

@section('title', 'Member Signup')

@section('content')
<div class="container" style="max-width:500px; margin-top:50px;">
    <h2 class="mb-3">Member Signup</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('signup') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Name" class="form-control mb-2" value="{{ old('name') }}" required>
        <input type="email" name="email" placeholder="Email" class="form-control mb-2" value="{{ old('email') }}" required>
        <input type="password" name="password" placeholder="Password" class="form-control mb-2" required>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control mb-2" required>
        <input type="text" name="phone" placeholder="Phone" class="form-control mb-2" value="{{ old('phone') }}" required>
        <input type="text" name="id_number" placeholder="ID Number" class="form-control mb-2" value="{{ old('id_number') }}" required>
        <input type="date" name="date_of_birth" placeholder="Date of Birth" class="form-control mb-2" value="{{ old('date_of_birth') }}">
        <input type="text" name="address" placeholder="Address" class="form-control mb-2" value="{{ old('address') }}">
        <button type="submit" class="btn btn-primary w-100">Signup</button>
    </form>

    <p class="mt-3 text-center">
        Already have an account? <a href="{{ route('login') }}">Login here</a>
    </p>
</div>
@endsection
