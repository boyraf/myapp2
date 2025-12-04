<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;

class MembersController extends Controller
{
    public function index()
    {
        $members = Member::with(['savings', 'loans', 'totalRepayments'])->get();
        return view('members.index', compact('members'));
    }

    /**
     * Member dashboard view.
     * Loads all member financial data for dashboard display.
     */
    public function dashboard()
    {
        $member = auth('member')->user();
        
        // Calculate total savings from all saving records
        $totalSavings = $member->savings()->sum('amount');
        
        // Get active loans and calculate balance
        $activeLoans = $member->loans()->where('status', 'approved')->get();
        $loanBalance = $activeLoans->sum('balance');
        
        // Calculate loan limit based on savings (3x multiplier per SACCO rules)
        $loanLimit = $totalSavings * 3;
        
        // Get pending loans awaiting approval
        $pendingLoans = $member->loans()->where('status', 'pending')->count();
        
        // Get share count from member record
        $shareCount = $member->shares ?? 0;
        
        // Get recent transactions for activity feed
        $recentTransactions = $member->transactions()->latest()->take(5)->get();
        
        // Compact all data and return view
        return view('member.dashboard', compact(
            'member',
            'totalSavings',
            'loanBalance',
            'loanLimit',
            'pendingLoans',
            'shareCount',
            'recentTransactions'
        ));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'phone' => 'required|string',
            'id_number' => 'required|string',
        ]);

        Member::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'id_number' => $request->id_number,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'membership_date' => now(),
            'status' => 'active',
        ]);

        return redirect()->route('admin.members')->with('success', 'Member added successfully.');
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'required|string',
        ]);

        $member->update($request->all());

        return redirect()->route('admin.members')->with('success', 'Member updated successfully.');
    }

    // Toggle status (active/inactive)
    public function toggleStatus($id)
    {
        $member = Member::findOrFail($id);
        $member->status = $member->status === 'active' ? 'inactive' : 'active';
        $member->save();

        return redirect()->route('admin.members')->with('success', 'Member status updated successfully.');
    }

    // ============ MEMBER PORTAL ROUTES ============

    // Dashboard sub-views
    public function dashboardSavings()
    {
        $member = auth('member')->user();
        return view('member.dashboard.savings', compact('member'));
    }

    public function dashboardLoans()
    {
        $member = auth('member')->user();
        return view('member.dashboard.loans', compact('member'));
    }

    public function dashboardLimit()
    {
        $member = auth('member')->user();
        return view('member.dashboard.limit', compact('member'));
    }

    public function dashboardPending()
    {
        $member = auth('member')->user();
        return view('member.dashboard.pending', compact('member'));
    }

    // Profile views
    public function profileView()
    {
        $member = auth('member')->user();
        return view('member.profile.view', compact('member'));
    }

    public function profileUpdate()
    {
        $member = auth('member')->user();
        return view('member.profile.update', compact('member'));
    }

    public function profileStore(Request $request)
    {
        $member = auth('member')->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'required|string',
            'address' => 'nullable|string',
        ]);

        $member->update($request->only(['name', 'email', 'phone', 'address']));

        return redirect()->route('member.profile.view')->with('success', 'Profile updated successfully.');
    }

    public function passwordForm()
    {
        return view('member.profile.password');
    }

    public function passwordStore(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $member = auth('member')->user();
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $member->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $member->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);

        return redirect()->route('member.profile.password')->with('success', 'Password changed successfully.');
    }

    // Support views
    public function contactForm()
    {
        return view('member.support.contact');
    }

    public function contactStore(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // TODO: Save support ticket or send email
        return back()->with('success', 'Your message has been sent. We will contact you soon.');
    }

    public function faq()
    {
        return view('member.support.faq');
    }
}
