@extends('layouts.app')

@section('content')
    <h1>Guarantors for Loan #{{ $loan->id }}</h1>
    <p>Loan Member: {{ $loan->member->name ?? '' }} ({{ $loan->member->email ?? '' }})</p>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Guarantor</th>
                <th>Amount Guaranteed</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guarantors as $g)
                <tr>
                    <td>{{ $g->id }}</td>
                    <td>{{ $g->member->name ?? '' }} ({{ $g->member->email ?? '' }})</td>
                    <td>{{ number_format($g->amount_guaranteed,2) }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.loans.guarantors.destroy', ['loan' => $loan->id, 'id' => $g->id]) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
