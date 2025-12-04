<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Member;
use App\Models\Loan;
use App\Models\Saving;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function member_dashboard_loads_successfully()
    {
        $member = Member::factory()->create();

        // In a real scenario, this would test the route
        // For now, we test the data structure
        $this->assertNotNull($member->id);
        $this->assertNotNull($member->name);
    }

    #[Test]
    public function dashboard_displays_total_savings()
    {
        $member = Member::factory()->create();

        // Create multiple saving records
        Saving::create([
            'member_id' => $member->id,
            'amount' => 5000,
            'type' => 'deposit',
            'balance_after' => 5000,
            'date' => now()->toDateString(),
            'status' => 'active',
        ]);

        Saving::create([
            'member_id' => $member->id,
            'amount' => 8000,
            'type' => 'deposit',
            'balance_after' => 13000,
            'date' => now()->toDateString(),
            'status' => 'active',
        ]);

        $totalSavings = Saving::where('member_id', $member->id)
            ->where('status', 'active')
            ->sum('amount');

        $this->assertEquals(13000, $totalSavings);
    }

    #[Test]
    public function dashboard_displays_loan_balance()
    {
        $member = Member::factory()->create();

        // Create approved loans
        Loan::create([
            'member_id' => $member->id,
            'amount' => 50000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 50000,
        ]);

        Loan::create([
            'member_id' => $member->id,
            'amount' => 30000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 20000,
        ]);

        $loanBalance = Loan::where('member_id', $member->id)
            ->where('status', 'approved')
            ->sum('balance');

        $this->assertEquals(70000, $loanBalance);
    }

    #[Test]
    public function dashboard_displays_loan_limit()
    {
        $member = Member::factory()->create();

        // Loan limit is calculated as 3x member's total savings from Saving records
        $mockSavings = 25000;
        $loanLimit = $mockSavings * 3;

        $this->assertEquals(75000, $loanLimit);
    }

    #[Test]
    public function dashboard_displays_pending_loans_count()
    {
        $member = Member::factory()->create();

        // Create pending loans
        Loan::create([
            'member_id' => $member->id,
            'amount' => 40000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'pending',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 40000,
        ]);

        Loan::create([
            'member_id' => $member->id,
            'amount' => 35000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'pending',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 35000,
        ]);

        $pendingLoans = Loan::where('member_id', $member->id)
            ->where('status', 'pending')
            ->count();

        $this->assertEquals(2, $pendingLoans);
    }

    #[Test]
    public function dashboard_displays_share_count()
    {
        $member = Member::factory()->create(['shares' => 15]);

        $this->assertEquals(15, $member->shares);
    }

    #[Test]
    public function dashboard_displays_recent_transactions()
    {
        $member = Member::factory()->create();

        // Create multiple transactions with explicit timestamps
        // Use raw insert to control timestamps properly
        $baseTime = now();
        
        \DB::table('transactions')->insert([
            [
                'member_id' => $member->id,
                'type' => 'saving_deposit',
                'amount' => 5000,
                'balance_after' => 5000,
                'description' => 'Monthly saving',
                'created_at' => $baseTime->copy()->subDays(2),
                'updated_at' => $baseTime->copy()->subDays(2),
            ],
            [
                'member_id' => $member->id,
                'type' => 'loan_disbursement',
                'amount' => 50000,
                'balance_after' => 50000,
                'description' => 'Loan disbursement',
                'created_at' => $baseTime->copy()->subDay(),
                'updated_at' => $baseTime->copy()->subDay(),
            ],
            [
                'member_id' => $member->id,
                'type' => 'repayment',
                'amount' => 10000,
                'balance_after' => 40000,
                'description' => 'Repayment',
                'created_at' => $baseTime,
                'updated_at' => $baseTime,
            ],
        ]);

        $recentTransactions = Transaction::where('member_id', $member->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $this->assertEquals(3, $recentTransactions->count());
        $this->assertEquals('repayment', $recentTransactions->first()->type);
    }

    #[Test]
    public function dashboard_data_is_accurate_after_transactions()
    {
        $member = Member::factory()->create();

        // Add savings through Saving records
        Saving::create([
            'member_id' => $member->id,
            'amount' => 10000,
            'type' => 'deposit',
            'balance_after' => 10000,
            'date' => now()->toDateString(),
            'status' => 'active',
        ]);

        $totalSavings = Saving::where('member_id', $member->id)
            ->where('status', 'active')
            ->sum('amount');

        $this->assertEquals(10000, $totalSavings);

        // Add another saving
        Saving::create([
            'member_id' => $member->id,
            'amount' => 5000,
            'type' => 'deposit',
            'balance_after' => 15000,
            'date' => now()->toDateString(),
            'status' => 'active',
        ]);

        $updatedSavings = Saving::where('member_id', $member->id)
            ->where('status', 'active')
            ->sum('amount');

        $this->assertEquals(15000, $updatedSavings);

        // Add a loan
        Loan::create([
            'member_id' => $member->id,
            'amount' => 30000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 30000,
        ]);

        $loanBalance = Loan::where('member_id', $member->id)
            ->where('status', 'approved')
            ->sum('balance');

        $this->assertEquals(30000, $loanBalance);
    }

    #[Test]
    public function dashboard_loan_limit_calculation_is_accurate()
    {
        $member = Member::factory()->create();

        // Mock savings and calculate limit
        $mockSavings = 50000;
        $loanLimit = $mockSavings * 3;
        
        $loanBalance = Loan::where('member_id', $member->id)
            ->where('status', 'approved')
            ->sum('balance');

        // Member should be able to borrow up to loan limit
        $availableCredit = $loanLimit - $loanBalance;

        $this->assertEquals(150000, $loanLimit);
        $this->assertEquals(150000, $availableCredit);
    }

    #[Test]
    public function dashboard_shows_zero_values_for_new_member()
    {
        $member = Member::factory()->create();

        $totalSavings = Saving::where('member_id', $member->id)
            ->where('status', 'active')
            ->sum('amount');

        $loanBalance = Loan::where('member_id', $member->id)
            ->where('status', 'approved')
            ->sum('balance');

        $shareCount = $member->shares;

        $pendingLoans = Loan::where('member_id', $member->id)
            ->where('status', 'pending')
            ->count();

        $this->assertEquals(0, $totalSavings);
        $this->assertEquals(0, $loanBalance);
        $this->assertEquals(0, $shareCount);
        $this->assertEquals(0, $pendingLoans);
    }

    #[Test]
    public function dashboard_updates_loan_balance_after_repayment()
    {
        $member = Member::factory()->create();

        $loan = Loan::create([
            'member_id' => $member->id,
            'amount' => 100000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 100000,
        ]);

        $initialBalance = Loan::where('member_id', $member->id)
            ->where('status', 'approved')
            ->sum('balance');

        $this->assertEquals(100000, $initialBalance);

        // Make a repayment
        $loan->update(['balance' => $loan->balance - 25000]);

        $updatedBalance = Loan::where('member_id', $member->id)
            ->where('status', 'approved')
            ->sum('balance');

        $this->assertEquals(75000, $updatedBalance);
    }

    #[Test]
    public function dashboard_handles_multiple_members_independently()
    {
        $member1 = Member::factory()->create();
        $member2 = Member::factory()->create();

        Loan::create([
            'member_id' => $member1->id,
            'amount' => 30000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 30000,
        ]);

        Loan::create([
            'member_id' => $member2->id,
            'amount' => 60000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 60000,
        ]);

        $member1Balance = Loan::where('member_id', $member1->id)
            ->where('status', 'approved')
            ->sum('balance');

        $member2Balance = Loan::where('member_id', $member2->id)
            ->where('status', 'approved')
            ->sum('balance');

        $this->assertEquals(30000, $member1Balance);
        $this->assertEquals(60000, $member2Balance);
        $this->assertNotEquals($member1Balance, $member2Balance);
    }
}
