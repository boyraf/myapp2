@extends('layouts.home')
@section('title', 'About Us')

@section('content')

<div class="container py-5">
    <h2 class="fw-bold mb-4">About Our SACCO</h2>

    <div class="row align-items-center">
        <div class="col-md-6">
            <p class="fs-5">
                MySacco is a community-based cooperative that supports members in achieving financial stability.
                We offer savings, loans, and investment opportunities at affordable rates.
            </p>
            <p class="fs-5">
                With transparency and trust at the core of our mission, we empower individuals and groups to
                grow financially through responsible financial solutions.
            </p>
        </div>

        <div class="col-md-6">
            <img src="/images/about.jpg" class="img-fluid rounded shadow" alt="">
        </div>
    </div>
</div>

@endsection
