<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Member;
use App\Models\Transaction;
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
    
public function pending()
{
    $loans = Loan::where('status', 'pending')
            ->with('member')
             ->get();
    return view('loans.pending', compact('loans'));
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
            'status' => 'approved', // Admin-created loans are directly approved
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

    // ============ ADMIN ACTIONS ============

    /**
     * Approve a pending loan if guarantor requirements are met.
     */
    public function approve($id)
    {
        $loan = Loan::with('guarantors')->findOrFail($id);

        if ($loan->status !== 'pending') {
            return redirect()->route('admin.loans')->with('error', 'Loan is not pending approval.');
        }

        // Sum of guarantor coverage
        $coverage = $loan->guarantors->sum('amount_guaranteed');
        $requiredCoverage = $loan->amount * 0.5;

        if ($coverage < $requiredCoverage) {
            return redirect()->route('admin.loans')->with('error', 'Insufficient guarantor coverage to approve this loan.');
        }

        // Approve loan
        $loan->status = 'approved';
        if (empty($loan->issue_date)) {
            $loan->issue_date = now();
        }
        $loan->save();

        // Record transaction
        Transaction::create([
            'member_id' => $loan->member_id,
            'type' => 'loan_disbursement',
            'amount' => $loan->amount,
            'balance_after' => $loan->balance,
            'description' => "Loan #{$loan->id} disbursed",
        ]);

        return redirect()->route('admin.loans')->with('success', 'Loan approved and disbursed.');
    }

    /**
     * Reject a pending loan.
     */
    public function reject($id)
    {
        $loan = Loan::findOrFail($id);

        if ($loan->status !== 'pending') {
            return redirect()->route('admin.loans')->with('error', 'Loan is not pending approval.');
        }

        $loan->status = 'declined';
        $loan->save();

        return redirect()->route('admin.loans')->with('success', 'Loan rejected successfully.');
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
        $maxLoan = $member->totalSavings() * 3;

        if ($request->amount > $maxLoan) {
            return redirect()->back()->withErrors([
                'amount' => "You can only borrow up to 3x your savings. Your current limit is {$maxLoan}."
            ]);
        }

        $loan = Loan::create([
            'member_id' => $member->id,
            'amount' => $request->amount,
            'interest_rate' => 10,
            'repayment_period' => (int)$request->repayment_period,
            'balance' => $request->amount,
            'status' => 'pending',
            'issue_date' => now(),
            'due_date' => now()->addMonths((int)$request->repayment_period),
        ]);

        // Save guarantors if provided
        if ($request->has('guarantors') && is_array($request->guarantors)) {
            foreach ($request->guarantors as $g) {
                if (isset($g['guarantor_id']) && isset($g['amount_guaranteed'])) {
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

        return redirect()->route('member.loans.history')->with('success', 'Loan application submitted.');
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
        $loans = $member->loans()->where('status', 'approved')->with('totalRepayments')->get();
        return view('member.loans.schedule', compact('loans'));
    }
}
