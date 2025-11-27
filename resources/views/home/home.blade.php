@extends('layouts.home')
@section('title', 'Welcome to MySacco')

@section('content')

<!-- HERO SECTION -->
<section class="py-5 bg-light" style="background:url('/images/hero.jpg'); background-size:cover;">
    <div class="container text-center text-white" style="backdrop-filter: blur(4px); padding:60px 20px;">
        <h1 class="fw-bold display-5">Welcome to MySacco</h1>
        <p class="lead mt-3">Empowering members through financial freedom.</p>
        <a href="/services" class="btn btn-primary btn-lg mt-3">Explore Our Services</a>
    </div>
</section>

<!-- ABOUT SUMMARY -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-4">Who We Are</h2>
        <p class="fs-5 text-center">
            MySacco is a trusted cooperative dedicated to improving the financial lives of our members.
        </p>
    </div>
</section>

@endsection
