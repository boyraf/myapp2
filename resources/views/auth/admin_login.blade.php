@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<div class="main-content" style="margin-left: 250px; transition: margin-left 0.3s;">
    <div class="container mt-5" style="max-width: 500px;">
        <h2 class="mb-4">Admin Login</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/admin/login') }}">
            @csrf
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</div>

<style>
    /* Optional: make it responsive for small screens */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0 !important;
        }
    }
</style>
@endsection
