@extends('layouts.member')

@section('content')
<h1 class="mb-3">Loan Status & Details</h1>

@if($loans->isEmpty())
	<div class="alert alert-info">You have no approved loans.</div>
@else
	@foreach($loans as $loan)
		<div class="card mb-4">
			<div class="card-header bg-info text-white">
				<strong>Loan #{{ $loan->id }}</strong> — Status: {{ ucfirst($loan->status) }}
			</div>
			<div class="card-body">
				<div class="row mb-3">
					<div class="col-md-6">
						<p><strong>Original Amount:</strong> {{ number_format($loan->amount, 2) }}</p>
						<p><strong>Current Balance:</strong> {{ number_format($loan->balance, 2) }}</p>
						<p><strong>Interest Rate:</strong> {{ $loan->interest_rate }}% per annum</p>
					</div>
					<div class="col-md-6">
						<p><strong>Repayment Period:</strong> {{ $loan->repayment_period }} months</p>
						<p><strong>Issue Date:</strong> {{ \Carbon\Carbon::parse($loan->issue_date)->toDateString() }}</p>
						<p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($loan->due_date)->toDateString() }}</p>
					</div>
				</div>
				<hr>
				<p class="mb-0"><strong>Monthly Interest (on current balance):</strong> {{ number_format($loan->monthlyInterest(), 2) }}</p>
			</div>
		</div>
	@endforeach
@endif

@endsection