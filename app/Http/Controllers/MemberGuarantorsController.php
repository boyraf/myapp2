<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guarantor;
use App\Models\Loan;
use App\Models\Member;

class MemberGuarantorsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:member');
    }

    // Add a guarantor to a loan (member action)
    public function store(Request $request, $loanId)
    {
        $member = auth('member')->user();

        $loan = Loan::findOrFail($loanId);

        // Only allow the loan owner to add guarantors to their own loan while it's pending
        if ($loan->member_id !== $member->id) {
            return abort(403);
        }

        if ($loan->status !== 'pending') {
            return back()->withErrors(['loan' => 'Cannot add guarantors to a non-pending loan.']);
        }

        $data = $request->validate([
            'guarantor_id' => 'required|exists:members,id|not_in:'.$member->id,
            'amount_guaranteed' => 'required|numeric|min:0',
        ]);

        // Prevent the same guarantor being added multiple times for the same loan
        $exists = Guarantor::where('loan_id', $loan->id)->where('guarantor_id', $data['guarantor_id'])->exists();
        if ($exists) {
            return back()->withErrors(['guarantor_id' => 'This guarantor is already added for the loan.']);
        }

        Guarantor::create([
            'loan_id' => $loan->id,
            'guarantor_id' => $data['guarantor_id'],
            'amount_guaranteed' => $data['amount_guaranteed'],
        ]);

        return redirect()->route('member.loans.history')->with('success', 'Guarantor added.');
    }

    public function destroy($loanId, $id)
    {
        $member = auth('member')->user();
        $loan = Loan::findOrFail($loanId);
        $guarantor = Guarantor::findOrFail($id);

        if ($loan->member_id !== $member->id) {
            return abort(403);
        }

        // Only allow deletion if loan is still pending
        if ($loan->status !== 'pending') {
            return back()->withErrors(['loan' => 'Cannot remove guarantors from a non-pending loan.']);
        }

        $guarantor->delete();

        return back()->with('success', 'Guarantor removed.');
    }
}
