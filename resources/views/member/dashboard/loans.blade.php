@extends('layouts.member')

@section('content')
<div class="container-fluid">
    <h2>Loan Balance</h2>

    <div class="row mt-3">
        <div class="col-md-12">
            @if($loans->isNotEmpty())
                @foreach($loans as $loan)
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Loan #{{ $loan->id }}</h5>
                            <p>Amount: <strong>{{ number_format($loan->amount, 2) }} KES</strong></p>
                            <p>Balance: <strong>{{ number_format($loan->balance, 2) }} KES</strong></p>
                            <p>Interest Rate: <strong>{{ $loan->interest_rate }}%</strong></p>
                            <p>Issue Date: {{ $loan->issue_date }}</p>
                            <p>Due Date: {{ $loan->due_date }}</p>

                            @if($loan->repayments->isNotEmpty())
                                <p>Repayments Made: {{ $loan->repayments->count() }}</p>
                                <ul>
                                    @foreach($loan->repayments as $repayment)
                                        <li>{{ $repayment->paid_on ?? $repayment->created_at }} - {{ number_format($repayment->amount, 2) }} KES</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No repayments made yet.</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <p>You have no active loans.</p>
            @endif
        </div>
    </div>
</div>
@endsection
