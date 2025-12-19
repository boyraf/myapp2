@extends('layouts.member')

@section('content')
<h2>My Loan History</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Issued</th>
            <th>Due</th>
        </tr>
    </thead>
    <tbody>
        @php
            // Start numbering from 1, latest loan first
            $counter = 1;
        @endphp
        @foreach($loans as $loan)
        <tr>
            <td>{{ $counter++ }}</td>
            <td>{{ number_format($loan->amount, 2) }}</td>
            <td>{{ ucfirst($loan->status) }}</td>
            <td>{{ $loan->issue_date ? $loan->issue_date->format('Y-m-d') : '—' }}</td>
            <td>{{ $loan->due_date ? $loan->due_date->format('Y-m-d') : '—' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $loans->links() }}
@endsection
