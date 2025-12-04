<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Share;
use App\Models\Member;
// Import Transaction model to record share buy/sell transactions
use App\Models\Transaction;

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
        // Get available pool shares (controlled_by_admin = true)
        $poolShares = Share::where('controlled_by_admin', true)
            ->whereNull('member_id')
            ->where('quantity', '>', 0)
            ->orderBy('price_per_share')
            ->get();
        $totalAvailable = $poolShares->sum('quantity');
        return view('member.shares.buy', compact('member', 'poolShares', 'totalAvailable'));
    }

    public function storeBuy(Request $request)
    {
        $member = auth('member')->user();
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Find available pool shares
        $poolShares = Share::where('controlled_by_admin', true)
            ->whereNull('member_id')
            ->where('quantity', '>', 0)
            ->orderBy('price_per_share')
            ->get();
        $totalAvailable = $poolShares->sum('quantity');

        if ($data['quantity'] > $totalAvailable) {
            return back()->withErrors(['quantity' => 'Not enough shares available in the pool'])->withInput();
        }

        $toBuy = $data['quantity'];
        $bought = 0;
        $totalCost = 0;
        foreach ($poolShares as $poolShare) {
            if ($toBuy <= 0) break;
            $take = min($poolShare->quantity, $toBuy);
            // Transfer shares: update pool share or split
            if ($take == $poolShare->quantity) {
                // Assign whole share record to member
                $poolShare->member_id = $member->id;
                $poolShare->controlled_by_admin = false;
                $poolShare->acquired_at = now();
                $poolShare->save();
            } else {
                // Split: reduce pool, create new for member
                $poolShare->quantity -= $take;
                $poolShare->save();
                Share::create([
                    'member_id' => $member->id,
                    'quantity' => $take,
                    'price_per_share' => $poolShare->price_per_share,
                    'total_value' => $take * $poolShare->price_per_share,
                    'acquired_at' => now(),
                    'status' => 'active',
                    'controlled_by_admin' => false,
                ]);
            }
            $bought += $take;
            $totalCost += $take * $poolShare->price_per_share;
            $toBuy -= $take;
        }

        // Update member shares count
        $member->shares = ($member->shares ?? 0) + $bought;
        $member->save();

        // Record share purchase transaction for audit trail and accounting
        Transaction::create([
            'member_id' => $member->id,
            'type' => 'share_purchase',
            'amount' => $totalCost,
            'balance_after' => $member->shares,
            'description' => "Purchased $bought shares at cost $totalCost",
        ]);

        return redirect()->route('member.shares.index')->with('success', "Bought $bought shares for total cost $totalCost");
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

        // Record share sale transaction for audit trail and accounting
        $saleAmount = $quantity * $data['price_per_share'];
        Transaction::create([
            'member_id' => $member->id,
            'type' => 'share_sale',
            'amount' => $saleAmount,
            'balance_after' => $member->shares,
            'description' => "Sold $quantity shares at amount $saleAmount",
        ]);

        return redirect()->route('member.shares.index')->with('success','Share sale recorded');
    }
}
