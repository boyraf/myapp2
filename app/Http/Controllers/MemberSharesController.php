<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Share;
use App\Models\Member;

class MemberSharesController extends Controller
{
    public function index()
    {
        $member = auth('member')->user();
        $shares = Share::where('member_id', $member->id)->latest()->get();
        return view('member.shares.index', compact('shares','member'));
    }

    public function buy()
    {
        $member = auth('member')->user();
        return view('member.shares.buy', compact('member'));
    }

    public function storeBuy(Request $request)
    {
        $member = auth('member')->user();
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'price_per_share' => 'required|numeric|min:0',
        ]);

        $data['member_id'] = $member->id;
        $data['total_value'] = $data['quantity'] * $data['price_per_share'];
        $data['acquired_at'] = now();
        $data['status'] = 'active';
        $data['controlled_by_admin'] = false;

        $share = Share::create($data);

        // Update member shares count
        $member->shares = ($member->shares ?? 0) + $share->quantity;
        $member->save();

        return redirect()->route('member.shares.index')->with('success','Share purchase recorded');
    }

    public function sell()
    {
        $member = auth('member')->user();
        return view('member.shares.sell', compact('member'));
    }

    public function storeSell(Request $request)
    {
        $member = auth('member')->user();
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'price_per_share' => 'required|numeric|min:0',
        ]);

        $quantity = abs($data['quantity']);

        // Prevent selling more shares than owned
        if (($member->shares ?? 0) < $quantity) {
            return back()->withErrors(['quantity' => 'You do not have enough shares to sell'])->withInput();
        }

        // Record sale as a negative quantity entry for history
        $sale = Share::create([
            'member_id' => $member->id,
            'quantity' => -1 * $quantity,
            'price_per_share' => $data['price_per_share'],
            'total_value' => -1 * $quantity * $data['price_per_share'],
            'acquired_at' => now(),
            'status' => 'sold',
            'controlled_by_admin' => false,
        ]);

        // Deduct from member's shares count
        $member->shares = max(0, ($member->shares ?? 0) - $quantity);
        $member->save();

        return redirect()->route('member.shares.index')->with('success','Share sale recorded');
    }
}
