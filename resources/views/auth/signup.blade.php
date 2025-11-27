@extends('layouts.login')

@section('title', 'Member Signup')

@section('content')
<div class="container" style="max-width: 500px;">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('signup') }}" method="POST" class="mt-3">
        @csrf
        <div class="mb-2">
            <input type="text" name="name" placeholder="Name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-2">
            <input type="email" name="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-2">
            <input type="password" name="password" placeholder="Password (min 6 chars)" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-2">
            <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-2">
            <input type="text" name="phone" placeholder="Phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
            @error('phone')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-2">
            <input type="text" name="id_number" placeholder="ID Number" class="form-control @error('id_number') is-invalid @enderror" value="{{ old('id_number') }}" required>
            @error('id_number')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-2">
            <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}">
            @error('date_of_birth')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-2">
            <input type="text" name="address" placeholder="Address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}">
            @error('address')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-2">Signup</button>
    </form>

    <p class="mt-3 text-center">
        Already have an account? <a href="{{ route('login') }}">Login here</a>
    </p>
</div>
@endsection
