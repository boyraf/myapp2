@extends('layouts.member')

@section('content')
<div class="container-fluid">
    <h2>Pending Loans</h2>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Pending Loan Applications</h5>
                    @if(!empty($pendingLoans) && count($pendingLoans) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($pendingLoans as $loan)
                                <li class="list-group-item">
                                    Loan ID: {{ $loan->id }} | Amount: {{ number_format($loan->amount, 2) }} KES | Applied on: {{ $loan->applied_at }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>You have no pending loans.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
