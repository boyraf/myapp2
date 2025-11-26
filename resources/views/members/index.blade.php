@extends('layouts.app')

@section('title', 'Members Overview')

@section('content')
<div class="content container-fluid">
    <h1 class="mb-4">Members</h1>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.members.create') }}" class="btn btn-primary">Add Member</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Membership Date</th>
                    <th>Status</th>
                    <th>Total Loans</th>
                    <th>Total Savings</th>
                    <th>Total Repayments</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    <tr>
                        <td>{{ $member->id }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->email }}</td>
                        <td>{{ $member->phone }}</td>
                        <td>{{ $member->membership_date }}</td>
                        <td>
                            @if($member->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($member->status) }}</span>
                            @endif
                        </td>
                        <td>KES {{ number_format(optional($member->loans)->sum('amount') ?? 0, 2) }}</td>
                        <td>KES {{ number_format(optional($member->savings)->sum('amount') ?? 0, 2) }}</td>
                        <td>KES {{ number_format(optional($member->totalRepayments)->sum('amount_paid') ?? 0, 2) }}</td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('admin.members.edit', $member->id) }}" class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('admin.members.toggleStatus', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to change this member\'s status?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $member->status === 'active' ? 'btn-danger' : 'btn-success' }}">
                                    {{ $member->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No members found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
