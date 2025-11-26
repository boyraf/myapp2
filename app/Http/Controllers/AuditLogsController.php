<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Models\Admin;
use App\Models\Member;

class AuditLogsController extends Controller
{
    /**
     * Display a listing of audit logs with filtering by admin or member.
     */
    public function index(Request $request)
    {
        // Fetch all admins and members for dropdowns
        $admins = Admin::orderBy('name')->get();
        $members = Member::orderBy('name')->get();

        // Base query with relationships
        $query = AuditLog::with(['admin', 'member'])->orderBy('created_at', 'desc');

        // Filter by admin_id
        if ($request->filled('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        // Filter by member_id
        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        // Paginate results
        $auditLogs = $query->paginate(20)->withQueryString();

        // Pass to view
        return view('auditlogs.index', compact('auditLogs', 'admins', 'members'));
    }
}
