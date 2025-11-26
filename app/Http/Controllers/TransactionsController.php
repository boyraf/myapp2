<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Member;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        // Get selected type from query string
        $type = $request->query('type', 'all');

        // Filter transactions based on type
        if ($type !== 'all') {
            $transactions = Transaction::where('type', $type)->with('member')->get();
        } else {
            $transactions = Transaction::with('member')->get();
        }

        // Calculate totals
        $totalAmount = $transactions->sum('amount');
        $totalCount = $transactions->count();

        // Get unique members for this type
        $members = $transactions->pluck('member')->unique('id');

        // Get all available transaction types dynamically
        $transactionTypes = Transaction::select('type')->distinct()->pluck('type');

        return view('transactions.index', compact(
            'transactions',
            'totalAmount',
            'totalCount',
            'members',
            'transactionTypes',
            'type'
        ));
    }
}
