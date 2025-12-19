@extends('layouts.member')

@section('content')
<h2>Repayment Schedule</h2>

@if($loans->isEmpty())
    <p>You have no active loans.</p>
@else
    @foreach($loans as $loan)
    <div class="card mb-3">
        <div class="card-header">
            Loan #{{ $loan->id }} — {{ number_format($loan->amount, 2) }} (Due: {{ $loan->due_date->format('Y-m-d') }})
        </div>
        <div class="card-body">
            <p>Status: <strong>{{ ucfirst($loan->status) }}</strong></p>
            <p>Balance: <strong>{{ number_format($loan->balance, 2) }}</strong></p>

            @if($loan->totalRepayments->isNotEmpty())
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Remaining Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loan->totalRepayments as $repayment)
                    <tr>
                        <td>{{ $repayment->created_at->format('Y-m-d') }}</td>
                        <td>{{ number_format($repayment->amount, 2) }}</td>
                        <td>{{ number_format($repayment->balance_after, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <p>No repayments recorded yet.</p>
            @endif
        </div>
    </div>
    @endforeach
@endif
@endsection

