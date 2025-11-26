<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repayment;
use App\Models\Loan;

class RepaymentsController extends Controller
{
    public function index()
    {
        $totalRepayments = Repayment::count();
        $totalRepaymentAmount = Repayment::sum('amount_paid');

        $repayments = Repayment::with('loan.member')->paginate(10);

        $repaymentsPerMemberCount = $repayments
            ->filter(fn($r) => $r->loan && $r->loan->member)
            ->groupBy(fn($r) => $r->loan->member->id)
            ->map(fn($group) => (object)[
                'member_name' => $group->first()->loan->member->name,
                'total_repayments' => $group->count()
            ])
            ->values();

        $repaymentsPerMemberAmount = $repayments
            ->filter(fn($r) => $r->loan && $r->loan->member)
            ->groupBy(fn($r) => $r->loan->member->id)
            ->map(fn($group) => (object)[
                'member_name' => $group->first()->loan->member->name,
                'total_amount' => $group->sum('amount_paid')
            ])
            ->values();

        return view('repayments.index', compact(
            'totalRepayments',
            'totalRepaymentAmount',
            'repaymentsPerMemberCount',
            'repaymentsPerMemberAmount',
            'repayments'
        ));
    }

    public function create()
    {
        $loans = Loan::where('status', 'active')->get();
        return view('repayments.create', compact('loans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'amount_paid' => 'required|numeric',
            'payment_date' => 'required|date',
        ]);

        $loan = Loan::find($request->loan_id);
        $balance_after = $loan->balance - $request->amount_paid;

        $repayment = Repayment::create([
            'loan_id' => $loan->id,
            'amount_paid' => $request->amount_paid,
            'balance_after_payment' => $balance_after,
            'payment_date' => $request->payment_date,
        ]);

        $loan->update(['balance' => $balance_after]);

        return redirect()->route('admin.repayments')->with('success', 'Repayment recorded successfully.');
    }

    public function edit(Repayment $repayment)
    {
        $loans = Loan::where('status', 'active')->get();
        return view('repayments.edit', compact('repayment', 'loans'));
    }

    public function update(Request $request, Repayment $repayment)
    {
        $request->validate([
            'amount_paid' => 'required|numeric',
            'payment_date' => 'required|date',
        ]);

        $loan = $repayment->loan;
        $loan->balance += $repayment->amount_paid; // revert old amount
        $loan->balance -= $request->amount_paid; // apply new amount
        $loan->save();

        $repayment->update([
            'amount_paid' => $request->amount_paid,
            'balance_after_payment' => $loan->balance,
            'payment_date' => $request->payment_date,
        ]);

        return redirect()->route('admin.repayments')->with('success', 'Repayment updated successfully.');
    }

    public function deactivate($id)
{
    $repayment = Repayment::findOrFail($id);
    $repayment->status = 'inactive';
    $repayment->save();

    return redirect()->back()->with('success', 'Repayment deactivated successfully.');
}

public function toggleStatus($id)
{
    $item = Repayment::findOrFail($id);
    $item->status = $item->status === 'active' ? 'inactive' : 'active';
    $item->save();

    return redirect()->back()->with('success', 'Status updated successfully.');
}

}
