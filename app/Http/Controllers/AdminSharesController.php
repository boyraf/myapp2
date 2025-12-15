<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Share;
use App\Models\Member;

class AdminSharesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $shares = Share::with('member')->latest()->paginate(25);
        return view('admin.shares.index', compact('shares'));
    }

    public function create()
    {
        // Pool shares: no member assignment at creation
        return view('admin.shares.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'price_per_share' => 'required|numeric|min:0',
        ]);

        $data['total_value'] = $data['quantity'] * $data['price_per_share'];
        $data['acquired_at'] = now();
        $data['status'] = 'active';
        $data['controlled_by_admin'] = true;
        $data['member_id'] = null;

        Share::create($data);

        return redirect()->route('admin.shares')->with('success', 'Pool share created');
    }

    public function edit($id)
    {
        $share = Share::findOrFail($id);
        $members = Member::orderBy('name')->get();
        return view('admin.shares.edit', compact('share','members'));
    }

    public function update(Request $request, $id)
    {
        $share = Share::findOrFail($id);
        $data = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'quantity' => 'required|integer|min:1',
            'price_per_share' => 'required|numeric|min:0',
            'controlled_by_admin' => 'sometimes|boolean',
        ]);

        $data['total_value'] = $data['quantity'] * $data['price_per_share'];
        $data['controlled_by_admin'] = $request->has('controlled_by_admin');

        $share->update($data);

        return redirect()->route('admin.shares')->with('success', 'Share updated');
    }

    public function destroy($id)
    {
        $share = Share::findOrFail($id);
        $share->delete();
        return redirect()->route('admin.shares')->with('success', 'Share deleted');
    }

    // Placeholder: distribution endpoint
    public function distributeDividends(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        $totalAmount = (float) $data['amount'];

        // Total shares across all members
        $totalShares = Member::sum('shares');

        if ($totalShares <= 0) {
            return back()->with('error', 'No shares found to distribute dividends to.');
        }

        // Loop members with shares and allocate proportionally
        $members = Member::where('shares', '>', 0)->get();

        foreach ($members as $member) {
            $ratio = $member->shares / $totalShares;
            $memberAmount = round($totalAmount * $ratio, 2);

            if ($memberAmount <= 0) {
                continue;
            }

            // Record transaction for dividend
            \App\Models\Transaction::create([
                'member_id' => $member->id,
                'type' => 'dividend',
                'amount' => $memberAmount,
                'balance_after' => $memberAmount,
                'description' => $data['description'] ?? 'Dividend distribution',
            ]);
        }

        return back()->with('success', 'Dividends distributed successfully.');
    }
}
