@extends('layouts.app')

@section('content')
    <h1>Create Pool Shares (Admin)</h1>
    <form method="POST" action="{{ route('admin.shares.store') }}">
        @csrf
        <label>Quantity</label>
        <input type="number" name="quantity" value="1" min="1">

        <label>Price per share</label>
        <input type="text" name="price_per_share" value="0.00">

        <button type="submit">Create Pool Shares</button>
    </form>
@endsection
