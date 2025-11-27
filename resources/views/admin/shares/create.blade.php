@extends('layouts.app')

@section('content')
    <h1>Create Share (Admin)</h1>
    <form method="POST" action="{{ route('admin.shares.store') }}">
        @csrf
        <label>Member (optional)</label>
        <select name="member_id">
            <option value="">-- none --</option>
            @foreach($members as $m)
                <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->email }})</option>
            @endforeach
        </select>

        <label>Quantity</label>
        <input type="number" name="quantity" value="1" min="1">

        <label>Price per share</label>
        <input type="text" name="price_per_share" value="0.00">

        <label>Controlled by admin</label>
        <input type="checkbox" name="controlled_by_admin" value="1">

        <button type="submit">Create</button>
    </form>
@endsection
