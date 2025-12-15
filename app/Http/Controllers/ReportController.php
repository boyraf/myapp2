<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Repayment;
use App\Models\Share;
use App\Models\Transaction;
use App\Models\Member;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Loan Statement Report
     * Shows all loans with optional filtering by status, member, and date range
     */
    public function loanStatement(Request $request)
    {
        $filters = $request->validate([
            'status' => 'nullable|string|in:pending,approved,paid,overdue',
            'member_id' => 'nullable|integer|exists:members,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
        ]);

        $query = Loan::with('member');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('issue_date', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('issue_date', '<=', $filters['to_date']);
        }

        // Summary stats (before pagination)
        $totalLoans = $query->count();
        $totalAmount = $query->sum('amount');
        $totalBalance = $query->sum('balance');

        // Paginate after getting stats
        $loans = $query->orderBy('created_at', 'desc')->paginate(25);

        $members = Member::orderBy('name')->get();

        return view('reports.loan-statement', compact(
            'loans',
            'filters',
            'totalLoans',
            'totalAmount',
            'totalBalance',
            'members'
        ));
    }

    /**
     * Repayment Schedule Report
     * Shows repayment history with optional filtering
     */
    public function repaymentSchedule(Request $request)
    {
        $filters = $request->validate([
            'loan_id' => 'nullable|integer|exists:loans,id',
            'member_id' => 'nullable|integer|exists:members,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
        ]);

        $query = Repayment::with('loan.member');

        if (!empty($filters['loan_id'])) {
            $query->where('loan_id', $filters['loan_id']);
        }

        if (!empty($filters['member_id'])) {
            $query->whereHas('loan', function ($q) {
                $q->where('member_id', request('member_id'));
            });
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('payment_date', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('payment_date', '<=', $filters['to_date']);
        }

        // Summary stats (before pagination)
        $totalRepayments = $query->count();
        $totalAmountPaid = $query->sum('amount_paid');
        $avgRepayment = $totalRepayments > 0 ? round($totalAmountPaid / $totalRepayments, 2) : 0;

        // Paginate after getting stats
        $repayments = $query->orderBy('payment_date', 'desc')->paginate(25);

        $members = Member::orderBy('name')->get();
        $loans = Loan::with('member')->orderBy('created_at', 'desc')->get();

        return view('reports.repayment-schedule', compact(
            'repayments',
            'filters',
            'totalRepayments',
            'totalAmountPaid',
            'avgRepayment',
            'members',
            'loans'
        ));
    }

    /**
     * Share Holdings Statement
     * Shows share holdings per member
     */
    public function shareHoldings(Request $request)
    {
        $filters = $request->validate([
            'member_id' => 'nullable|integer|exists:members,id',
        ]);

        $query = Share::with('member');

        if (!empty($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }

        $shares = $query->orderBy('created_at', 'desc')->paginate(25);

        // Summary stats
        $totalShares = $query->sum('quantity');
        $totalValue = $query->sum('total_value');
        $avgSharePrice = $totalShares > 0 ? round($totalValue / $totalShares, 2) : 0;

        // Holdings per member
        $sharesByMember = Share::with('member')
            ->whereNotNull('member_id')
            ->selectRaw('member_id, SUM(quantity) as total_quantity, SUM(total_value) as total_value')
            ->groupBy('member_id')
            ->orderByDesc('total_quantity')
            ->get();

        $members = Member::orderBy('name')->get();

        return view('reports.share-holdings', compact(
            'shares',
            'filters',
            'totalShares',
            'totalValue',
            'avgSharePrice',
            'sharesByMember',
            'members'
        ));
    }

    /**
     * Dividend Statement
     * Shows dividend distributions to members
     */
    public function dividendStatement(Request $request)
    {
        $filters = $request->validate([
            'member_id' => 'nullable|integer|exists:members,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
        ]);

        $query = Transaction::where('type', 'dividend')->with('member');

        if (!empty($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        $dividends = $query->orderBy('created_at', 'desc')->paginate(25);

        // Summary stats
        $totalDividends = $query->count();
        $totalAmount = $query->sum('amount');
        $avgDividend = $totalDividends > 0 ? round($totalAmount / $totalDividends, 2) : 0;

        // Dividends per member
        $dividendsByMember = Transaction::where('type', 'dividend')
            ->selectRaw('member_id, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('member_id')
            ->with('member')
            ->orderByDesc('total')
            ->get();

        $members = Member::orderBy('name')->get();

        return view('reports.dividend-statement', compact(
            'dividends',
            'filters',
            'totalDividends',
            'totalAmount',
            'avgDividend',
            'dividendsByMember',
            'members'
        ));
    }

    /**
     * Export Loan Statement to CSV
     */
    public function exportLoanCSV(Request $request)
    {
        $filters = $request->validate([
            'status' => 'nullable|string',
            'member_id' => 'nullable|integer',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
        ]);

        $query = Loan::with('member');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (!empty($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }
        if (!empty($filters['from_date'])) {
            $query->whereDate('issue_date', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate('issue_date', '<=', $filters['to_date']);
        }

        $loans = $query->orderBy('created_at', 'desc')->get();

        $filename = 'loan-statement-' . now()->format('Y-m-d-His') . '.csv';
        $handle = fopen('php://memory', 'w');

        // Header
        fputcsv($handle, ['Loan ID', 'Member', 'Amount', 'Interest Rate', 'Repayment Period', 'Balance', 'Status', 'Issue Date', 'Due Date']);

        // Data
        foreach ($loans as $loan) {
            fputcsv($handle, [
                $loan->id,
                $loan->member->name ?? 'N/A',
                number_format($loan->amount, 2),
                $loan->interest_rate . '%',
                $loan->repayment_period . ' months',
                number_format($loan->balance, 2),
                strtoupper($loan->status),
                $loan->issue_date->format('Y-m-d'),
                $loan->due_date?->format('Y-m-d') ?? 'N/A',
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response()->streamDownload(
            function () use ($csv) {
                echo $csv;
            },
            $filename,
            ['Content-Type' => 'text/csv']
        );
    }

    /**
     * Export Repayment Schedule to CSV
     */
    public function exportRepaymentCSV(Request $request)
    {
        $filters = $request->validate([
            'loan_id' => 'nullable|integer',
            'member_id' => 'nullable|integer',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
        ]);

        $query = Repayment::with('loan.member');

        if (!empty($filters['loan_id'])) {
            $query->where('loan_id', $filters['loan_id']);
        }
        if (!empty($filters['member_id'])) {
            $query->whereHas('loan', function ($q) {
                $q->where('member_id', request('member_id'));
            });
        }
        if (!empty($filters['from_date'])) {
            $query->whereDate('payment_date', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate('payment_date', '<=', $filters['to_date']);
        }

        $repayments = $query->orderBy('payment_date', 'desc')->get();

        $filename = 'repayment-schedule-' . now()->format('Y-m-d-His') . '.csv';
        $handle = fopen('php://memory', 'w');

        // Header
        fputcsv($handle, ['Repayment ID', 'Loan ID', 'Member', 'Amount Paid', 'Balance After', 'Payment Date']);

        // Data
        foreach ($repayments as $repayment) {
            fputcsv($handle, [
                $repayment->id,
                $repayment->loan_id,
                $repayment->loan->member->name ?? 'N/A',
                number_format($repayment->amount_paid, 2),
                number_format($repayment->balance_after_payment, 2),
                $repayment->payment_date->format('Y-m-d'),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response()->streamDownload(
            function () use ($csv) {
                echo $csv;
            },
            $filename,
            ['Content-Type' => 'text/csv']
        );
    }

    /**
     * Export Share Holdings to CSV
     */
    public function exportSharesCSV(Request $request)
    {
        $filters = $request->validate([
            'member_id' => 'nullable|integer',
        ]);

        $query = Share::with('member');

        if (!empty($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }

        $shares = $query->orderBy('created_at', 'desc')->get();

        $filename = 'share-holdings-' . now()->format('Y-m-d-His') . '.csv';
        $handle = fopen('php://memory', 'w');

        // Header
        fputcsv($handle, ['Share ID', 'Member', 'Quantity', 'Price per Share', 'Total Value', 'Status', 'Acquired Date']);

        // Data
        foreach ($shares as $share) {
            fputcsv($handle, [
                $share->id,
                $share->member->name ?? 'Pool (Unassigned)',
                $share->quantity,
                number_format($share->price_per_share, 2),
                number_format($share->total_value, 2),
                strtoupper($share->status ?? 'active'),
                $share->acquired_at->format('Y-m-d'),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response()->streamDownload(
            function () use ($csv) {
                echo $csv;
            },
            $filename,
            ['Content-Type' => 'text/csv']
        );
    }

    /**
     * Export Dividend Statement to CSV
     */
    public function exportDividendCSV(Request $request)
    {
        $filters = $request->validate([
            'member_id' => 'nullable|integer',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
        ]);

        $query = Transaction::where('type', 'dividend')->with('member');

        if (!empty($filters['member_id'])) {
            $query->where('member_id', $filters['member_id']);
        }
        if (!empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        $dividends = $query->orderBy('created_at', 'desc')->get();

        $filename = 'dividend-statement-' . now()->format('Y-m-d-His') . '.csv';
        $handle = fopen('php://memory', 'w');

        // Header
        fputcsv($handle, ['Transaction ID', 'Member', 'Amount', 'Description', 'Date']);

        // Data
        foreach ($dividends as $dividend) {
            fputcsv($handle, [
                $dividend->id,
                $dividend->member->name ?? 'N/A',
                number_format($dividend->amount, 2),
                $dividend->description,
                $dividend->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response()->streamDownload(
            function () use ($csv) {
                echo $csv;
            },
            $filename,
            ['Content-Type' => 'text/csv']
        );
    }
}

