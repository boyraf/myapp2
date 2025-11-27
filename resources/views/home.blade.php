@extends('layouts.home')

@section('title', 'Home')

@section('content')
<div class="container">
    <div class="py-5 text-center">
        <h1 class="display-5">Welcome to MySacco</h1>
        <p class="lead">A simple cooperative management platform.</p>
        <p>
            <a href="{{ route('signup') }}" class="btn btn-primary">Get Started</a>
            <a href="{{ route('login') }}" class="btn btn-outline-secondary">Member Login</a>
        </p>
    </div>
</div>
@endsection
