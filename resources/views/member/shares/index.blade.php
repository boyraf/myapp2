@extends('layouts.member')

@section('content')
    <h1>My Shares</h1>
    <p>Shares owned: {{ number_format($member->shares ?? 0) }}</p>

    <a href="{{ route('member.shares.buy') }}">Buy Shares</a> | 
    <a href="{{ route('member.shares.sell') }}">Sell Shares</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Status</th>
                <th>Acquired</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shares as $s)
                <tr>
                    <td>{{ $s->id }}</td>
                    <td>{{ $s->quantity }}</td>
                    <td>{{ number_format($s->price_per_share,2) }}</td>
                    <td>{{ number_format($s->total_value,2) }}</td>
                    <td>{{ $s->status }}</td>
                    <td>{{ optional($s->acquired_at)->toDayDateTimeString() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
