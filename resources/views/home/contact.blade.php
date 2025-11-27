@extends('layouts.home')
@section('title', 'Contact Us')

@section('content')

<div class="container py-5">
    <h2 class="fw-bold text-center mb-4">Contact Us</h2>

    <div class="row">
        <div class="col-md-6">
            <h5>Address</h5>
            <p>Nairobi, Kenya</p>

            <h5>Email</h5>
            <p>support@mysacco.com</p>

            <h5>Phone</h5>
            <p>+254 700 000 000</p>
        </div>

        <div class="col-md-6">
            <form class="p-4 shadow-sm bg-white rounded">
                <h5 class="fw-bold mb-3">Send Us a Message</h5>

                <input type="text" class="form-control mb-3" placeholder="Your Name">
                <input type="email" class="form-control mb-3" placeholder="Email Address">
                <textarea class="form-control mb-3" rows="4" placeholder="Message"></textarea>

                <button class="btn btn-primary w-100">Send</button>
            </form>
        </div>
    </div>

</div>

@endsection
