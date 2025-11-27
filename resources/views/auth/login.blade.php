@extends('layouts.login')

@section('title', 'Member Login')

@section('content')
<div class="container" style="max-width: 400px;">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="mt-3">
        @csrf
        <div class="mb-2">
            <input type="email" name="email" placeholder="Email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-2">
            <input type="password" name="password" placeholder="Password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-2">Login</button>
    </form>

    <p class="mt-3 text-center">
        Don't have an account? <a href="{{ route('signup') }}">Signup here</a>
    </p>
</div>
@endsection
