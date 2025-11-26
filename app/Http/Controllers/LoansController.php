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
            'status' => 'active',
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

}
