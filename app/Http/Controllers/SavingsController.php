<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Saving;
use App\Models\Member;

class SavingsController extends Controller
{
    public function index()
    {
        $totalSavings = Saving::sum('amount');
        $savingsCountPerMember = Member::withCount('savings')->get();
        $savingsAmountPerMember = Member::withSum('savings', 'amount')->get();

        $savings = Saving::with('member')->paginate(10);

        return view('savings.index', compact('totalSavings', 'savingsCountPerMember', 'savingsAmountPerMember', 'savings'));
    }

    public function create()
    {
        $members = Member::where('status', 'active')->get();
        return view('savings.create', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric',
            'type' => 'required|string',
        ]);

        $member = Member::find($request->member_id);
        $balance_after = ($member->savings()->sum('amount') + $request->amount);

        Saving::create([
            'member_id' => $request->member_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'balance_after' => $balance_after,
            'date' => now(),
        ]);

        return redirect()->route('admin.savings')->with('success', 'Saving added successfully.');
    }

    public function edit(Saving $saving)
    {
        $members = Member::where('status', 'active')->get();
        return view('savings.edit', compact('saving', 'members'));
    }

    public function update(Request $request, Saving $saving)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|string',
        ]);

        $saving->update($request->all());

        return redirect()->route('admin.savings')->with('success', 'Saving updated successfully.');
    }

    public function deactivate($id)
{
    $saving = Saving::findOrFail($id);
    $saving->status = 'inactive';
    $saving->save();

    return redirect()->back()->with('success', 'Saving deactivated successfully.');
}

public function toggleStatus($id)
{
    $item = Saving::findOrFail($id);
    $item->status = $item->status === 'active' ? 'inactive' : 'active';
    $item->save();

    return redirect()->back()->with('success', 'Status updated successfully.');
}

// ============ MEMBER PORTAL ROUTES ============

public function statement()
{
    $member = auth('member')->user();
    $savings = $member->savings()->paginate(10);
    return view('member.savings.statement', compact('savings'));
}

public function deposit()
{
    return view('member.savings.deposit');
}

public function storeDeposit(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:0.01',
    ]);

    $member = auth('member')->user();
    $balance_after = $member->savings()->sum('amount') + $request->amount;

    Saving::create([
        'member_id' => $member->id,
        'amount' => $request->amount,
        'type' => 'deposit',
        'balance_after' => $balance_after,
        'date' => now(),
    ]);

    return redirect()->route('member.savings.statement')->with('success', 'Deposit successful.');
}

public function withdraw()
{
    return view('member.savings.withdraw');
}

public function storeWithdraw(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:0.01',
    ]);

    $member = auth('member')->user();
    $currentBalance = $member->savings()->sum('amount');

    if ($request->amount > $currentBalance) {
        return back()->withErrors(['amount' => 'Insufficient savings balance.']);
    }

    $balance_after = $currentBalance - $request->amount;

    Saving::create([
        'member_id' => $member->id,
        'amount' => -$request->amount,
        'type' => 'withdrawal',
        'balance_after' => $balance_after,
        'date' => now(),
    ]);

    return redirect()->route('member.savings.statement')->with('success', 'Withdrawal request submitted.');
}

}
