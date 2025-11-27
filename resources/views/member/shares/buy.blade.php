@extends('layouts.member')

@section('content')
    <h1>Buy Shares</h1>
    <form method="POST" action="{{ route('member.shares.buy.store') }}">
        @csrf
        <label>Quantity</label>
        <input type="number" name="quantity" min="1" value="1">

        <label>Price per share</label>
        <input type="text" name="price_per_share" value="0.00">

        <button type="submit">Buy</button>
    </form>
@endsection
