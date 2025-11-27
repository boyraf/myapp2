@extends('layouts.app')

@section('content')
    <h1>Admin - Shares</h1>
    <a href="{{ route('admin.shares.create') }}">Create Share</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Member</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Controlled By Admin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shares as $s)
                <tr>
                    <td>{{ $s->id }}</td>
                    <td>{{ optional($s->member)->name }}</td>
                    <td>{{ $s->quantity }}</td>
                    <td>{{ number_format($s->price_per_share,2) }}</td>
                    <td>{{ number_format($s->total_value,2) }}</td>
                    <td>{{ $s->controlled_by_admin ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('admin.shares.edit', $s->id) }}">Edit</a>
                        <form action="{{ route('admin.shares.destroy', $s->id) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $shares->links() }}
@endsection
