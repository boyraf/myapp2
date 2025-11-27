@extends('layouts.app')

@section('content')
    <h1>Edit Share (Admin)</h1>
    <form method="POST" action="{{ route('admin.shares.update', $share->id) }}">
        @csrf
        @method('PUT')
        <label>Member (optional)</label>
        <select name="member_id">
            <option value="">-- none --</option>
            @foreach($members as $m)
                <option value="{{ $m->id }}" {{ $share->member_id == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
            @endforeach
        </select>

        <label>Quantity</label>
        <input type="number" name="quantity" value="{{ $share->quantity }}" min="1">

        <label>Price per share</label>
        <input type="text" name="price_per_share" value="{{ $share->price_per_share }}">

        <label>Controlled by admin</label>
        <input type="checkbox" name="controlled_by_admin" value="1" {{ $share->controlled_by_admin ? 'checked' : '' }}>

        <button type="submit">Update</button>
    </form>
@endsection
