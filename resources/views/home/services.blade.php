@extends('layouts.home')
@section('title', 'Our Services')

@section('content')

<div class="container py-5">
    <h2 class="fw-bold text-center mb-5">Our Services</h2>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="card shadow-sm h-100 p-3 text-center">
                <img src="/images/loan.jpg" class="card-img-top rounded" alt="">
                <h4 class="mt-3">Loans</h4>
                <p>Affordable, flexible loans designed to uplift our members.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 p-3 text-center">
                <img src="/images/savings.jpg" class="card-img-top rounded" alt="">
                <h4 class="mt-3">Savings</h4>
                <p>Grow your money securely with our savings programs.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 p-3 text-center">
                <img src="/images/invest.jpg" class="card-img-top rounded" alt="">
                <h4 class="mt-3">Investments</h4>
                <p>Long-term investment opportunities for wealth building.</p>
            </div>
        </div>

    </div>

</div>

@endsection
