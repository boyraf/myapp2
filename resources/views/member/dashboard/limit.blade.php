@extends('layouts.member')

@section('content')
<div class="container-fluid">
    <h2>Loan Limit</h2>

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Your Maximum Loan Limit</h5>
                    <p>Total loan limit: <strong>{{ number_format($loanLimit ?? 0, 2) }} KES</strong></p>
                    <p>Available to borrow: <strong>{{ number_format(($loanLimit - ($loanBalance ?? 0)), 2) }} KES</strong></p>
                    <p>Last loan applied: <strong>{{ $lastLoanDate ?? 'N/A' }}</strong></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Usage Overview</h5>
                    @if($loanBalance && $loanLimit)
                        @php
                            $percentUsed = round(($loanBalance/$loanLimit)*100);
                        @endphp
                        <div class="progress mb-2">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentUsed }}%;" aria-valuenow="{{ $percentUsed }}" aria-valuemin="0" aria-valuemax="100">{{ $percentUsed }}%</div>
                        </div>
                        <p>{{ $percentUsed }}% of your loan limit is currently used.</p>
                    @else
                        <p>No loans taken yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
