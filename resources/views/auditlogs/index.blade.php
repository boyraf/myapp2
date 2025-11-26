@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="container-fluid">

    <div class="text-center mb-4">
        <h1 class="fw-bold">Audit Logs</h1>
        <p class="text-muted">Track all actions by admins and members</p>
    </div>

    <!-- Filter Form -->
    <form method="GET" class="row mb-4 g-2">
    <div class="col-md-6">
        <select name="admin_id" class="form-control">
            <option value="">-- Filter by Admin --</option>
            @foreach($admins as $admin)
                <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                    {{ $admin->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <select name="member_id" class="form-control">
            <option value="">-- Filter by Member --</option>
            @foreach($members as $member)
                <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
                    {{ $member->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-12 mt-2">
        <button class="btn btn-primary w-100">Filter</button>
    </div>
</form>


    <!-- Audit Logs Table -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Admin</th>
                    <th>Member</th>
                    <th>Action</th>
                    <th>Table</th>
                    <th>Record ID</th>
                    <th>Details</th>
                    <th>Old Values</th>
                    <th>New Values</th>
                    <th>IP</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($auditLogs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->admin->name ?? '-' }}</td>
                        <td>{{ $log->member->name ?? '-' }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->table_name }}</td>
                        <td>{{ $log->record_id }}</td>
                        <td>{{ $log->details ?? '-' }}</td>
                        <td><pre>{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre></td>
                        <td><pre>{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre></td>
                        <td>{{ $log->ip_address }}</td>
                        <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted">No audit logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $auditLogs->links() }}

</div>
@endsection
