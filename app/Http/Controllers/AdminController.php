<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Loan;
use App\Models\Saving;
use App\Models\Repayment;
use App\Models\Transaction;
use DB;

class AdminController extends Controller
{
    public function index()
    {
        // Totals for cards
        $totalMembers = Member::count();
        $totalLoans = Loan::count();
        $totalLoanAmount = Loan::sum('amount');
        $totalSavings = Saving::sum('amount');
        $totalRepayments = Repayment::sum('amount_paid');
        $totalTransactions = Transaction::count();

        // Recent activity
        $recentLoans = Loan::latest()->take(5)->with('member')->get();
        $recentRepayments = Repayment::latest()->take(5)->with('loan.member')->get();
        $recentTransactions = Transaction::latest()->take(5)->with('member')->get();

        // Pie chart: Active vs Inactive members
        $activeMembers = Member::where('status', 'active')->count();
        $inactiveMembers = Member::where('status', 'inactive')->count();

        // Line chart: Loans issued per month (last 6 months)
        $sixMonthsAgo = date('Y-m-d H:i:s', strtotime('-6 months'));

        $loansPerMonth = Loan::select(
            DB::raw('MONTH(issue_date) as month'),
            DB::raw('SUM(amount) as total_amount')
        )
        ->where('issue_date', '>=', $sixMonthsAgo)
        ->groupBy(DB::raw('MONTH(issue_date)'))
        ->orderBy(DB::raw('MONTH(issue_date)'), 'asc')
        ->get();

        $months = [];
        $loanAmounts = [];

        foreach ($loansPerMonth as $loan) {
            // Convert month number to short month name (Jan, Feb, etc.)
            $months[] = date('M', mktime(0, 0, 0, $loan->month, 1));
            $loanAmounts[] = $loan->total_amount;
        }

        return view('admin.dashboard', compact(
            'totalMembers',
            'totalLoans',
            'totalLoanAmount',
            'totalSavings',
            'totalRepayments',
            'totalTransactions',
            'recentLoans',
            'recentRepayments',
            'recentTransactions',
            'activeMembers',
            'inactiveMembers',
            'months',
            'loanAmounts'
        ));
    }
}
