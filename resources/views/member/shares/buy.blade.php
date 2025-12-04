@extends('layouts.member')

@section('content')
    <h1>Buy Shares</h1>
    <p>Available shares in pool: <strong>{{ $totalAvailable }}</strong></p>
    <form method="POST" action="{{ route('member.shares.buy.store') }}">
        @csrf
        <label>Quantity</label>
        <input type="number" name="quantity" min="1" max="{{ $totalAvailable }}" value="1">

        <button type="submit">Buy</button>
    </form>

    <h3>Available Pool Shares</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Quantity</th>
                <th>Price per Share</th>
            </tr>
        </thead>
        <tbody>
            @foreach($poolShares as $ps)
                <tr>
                    <td>{{ $ps->id }}</td>
                    <td>{{ $ps->quantity }}</td>
                    <td>{{ number_format($ps->price_per_share,2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
