@extends('layouts.member')

@section('content')
<div class="container-fluid">
    <h2>Total Savings</h2>

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Current Savings</h5>
                    <p class="card-text">Your total savings amount is: <strong>{{ number_format($totalSavings ?? 0, 2) }} KES</strong></p>
                    <p class="card-text">Number of deposits: <strong>{{ $depositCount ?? 0 }}</strong></p>
                    <p class="card-text">Last deposit: <strong>{{ $lastDepositDate ?? 'N/A' }}</strong></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Recent Transactions</h5>
                    @if(!empty($recentDeposits))
                        <ul class="list-group list-group-flush">
                            @foreach($recentDeposits as $deposit)
                                <li class="list-group-item">
                                    {{ $deposit->date }} - {{ number_format($deposit->amount, 2) }} KES
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No recent deposits.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
