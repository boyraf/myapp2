@extends('layouts.app')

@section('title', 'Member Login')

@section('content')
<div class="container" style="max-width:400px; margin-top:50px;">
    <h2 class="mb-3">Member Login</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <input type="email" name="email" placeholder="Email" class="form-control mb-2" value="{{ old('email') }}" required>
        <input type="password" name="password" placeholder="Password" class="form-control mb-2" required>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="mt-3 text-center">
        Don't have an account? <a href="{{ route('signup') }}">Signup here</a>
    </p>
</div>
@endsection
