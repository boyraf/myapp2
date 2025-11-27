@extends('layouts.member')

@section('content')
    <h1>Sell Shares</h1>
    <form method="POST" action="{{ route('member.shares.sell.store') }}">
        @csrf
        <label>Quantity to sell</label>
        <input type="number" name="quantity" min="1" value="1">

        <label>Price per share</label>
        <input type="text" name="price_per_share" value="0.00">

        <button type="submit">Sell</button>
    </form>
    <p>Note: Selling will record a sale and deduct from your shares balance.</p>
@endsection
