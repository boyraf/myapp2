<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Member;
use DB;

class LoansController extends Controller
{
    public function index()
    {
        // Totals and stats
        $totalLoans = Loan::count();
        $totalLoanAmount = Loan::sum('amount');

        $loansPerMemberCount = Loan::select('member_id', DB::raw('count(*) as total_loans'))
                                    ->groupBy('member_id')
                                    ->with('member')
                                    ->get();

        $loansPerMemberAmount = Loan::select('member_id', DB::raw('sum(amount) as total_amount'))
                                     ->groupBy('member_id')
                                     ->with('member')
                                     ->get();

        // All loans for CRUD table
        $loans = Loan::with('member')->paginate(10);

        return view('loans.index', compact('totalLoans', 'totalLoanAmount', 'loansPerMemberCount', 'loansPerMemberAmount', 'loans'));
    }

    public function create()
    {
        $members = Member::where('status', 'active')->get();
        return view('loans.create', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric',
            'interest_rate' => 'required|numeric',
            'repayment_period' => 'required|integer',
        ]);

        Loan::create([
            'member_id' => $request->member_id,
            'amount' => $request->amount,
            'interest_rate' => $request->interest_rate,
            'repayment_period' => $request->repayment_period,
            'balance' => $request->amount,
            // Set loan status to 'approved' (matches enum in migrations: pending/approved/paid/overdue)
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths($request->repayment_period),
        ]);

        return redirect()->route('admin.loans')->with('success', 'Loan created successfully.');
    }

    public function edit(Loan $loan)
    {
        $members = Member::where('status', 'active')->get();
        return view('loans.edit', compact('loan', 'members'));
    }

    public function update(Request $request, Loan $loan)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric',
            'interest_rate' => 'required|numeric',
            'repayment_period' => 'required|integer',
            'status' => 'required|string',
        ]);

        $loan->update($request->all());

        return redirect()->route('admin.loans')->with('success', 'Loan updated successfully.');
    }

    public function deactivate($id)
{
    $loan = Loan::findOrFail($id);
    $loan->status = 'inactive';
    $loan->save();

    return redirect()->back()->with('success', 'Loan deactivated successfully.');
}

public function toggleStatus($id)
{
    $loan = Loan::findOrFail($id);

    // Simple toggle between 'pending' and 'approved' for demonstration
    if($loan->status === 'pending') {
        $loan->status = 'approved';
    } elseif($loan->status === 'approved') {
        $loan->status = 'pending';
    }
    // Note: 'paid' and 'overdue' statuses should be updated via repayment logic
    $loan->save();

    return redirect()->route('admin.loans')->with('success', 'Loan status updated successfully.');
}

// ============ MEMBER PORTAL ROUTES ============

public function apply()
{
    return view('member.loans.apply');
}

public function storeApply(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:100',
        'repayment_period' => 'required|integer|min:1|max:60',
    ]);

    $member = auth('member')->user();
    $loan = Loan::create([
        'member_id' => $member->id,
        'amount' => $request->amount,
        'interest_rate' => 10, // Default interest rate
        'repayment_period' => $request->repayment_period,
        'balance' => $request->amount,
        'status' => 'pending',
        'issue_date' => now(),
        'due_date' => now()->addMonths($request->repayment_period),
    ]);

    // Optional: accept guarantors array in the request. Expected format:
    // guarantors => [ ['guarantor_id' => 2, 'amount_guaranteed' => 1000], ... ]
    if ($request->has('guarantors') && is_array($request->guarantors)) {
        foreach ($request->guarantors as $g) {
            if (isset($g['guarantor_id']) && isset($g['amount_guaranteed'])) {
                // basic validation
                $guarantorMember = Member::find($g['guarantor_id']);
                if ($guarantorMember && $guarantorMember->id !== $member->id) {
                    $loan->guarantors()->create([
                        'guarantor_id' => $g['guarantor_id'],
                        'amount_guaranteed' => $g['amount_guaranteed'],
                    ]);
                }
            }
        }
    }

    return redirect()->route('member.loans.history')->with('success', 'Loan application submitted. Add guarantors to your pending loan if needed.');
}

public function history()
{
    $member = auth('member')->user();
    $loans = $member->loans()->paginate(10);
    return view('member.loans.history', compact('loans'));
}

public function schedule()
{
    $member = auth('member')->user();
    // Get member's approved loans that are still active (not yet paid)
    $loans = $member->loans()->where('status', 'approved')->with('totalRepayments')->get();
    return view('member.loans.schedule', compact('loans'));
}

}
