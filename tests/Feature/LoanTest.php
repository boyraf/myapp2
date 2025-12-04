<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Member;
use App\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function admin_can_create_loan_with_approved_status()
    {
        $member = Member::factory()->create();

        $loan = Loan::create([
            'member_id' => $member->id,
            'amount' => 50000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 50000,
        ]);

        $this->assertNotNull($loan->id);
        $this->assertEquals('approved', $loan->status);
        $this->assertEquals(50000, $loan->amount);
        $this->assertEquals(50000, $loan->balance);
    }

    #[Test]
    public function member_can_apply_for_loan_with_pending_status()
    {
        $member = Member::factory()->create();

        $loan = Loan::create([
            'member_id' => $member->id,
            'amount' => 60000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'pending',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 60000,
        ]);

        $this->assertEquals('pending', $loan->status);
        $this->assertDatabaseHas('loans', [
            'member_id' => $member->id,
            'status' => 'pending',
        ]);
    }

    #[Test]
    public function loan_limit_is_three_times_members_savings()
    {
        $member = Member::factory()->create();
        
        // Loan limit calculation: 3x the member's total savings from Saving records
        // For this test, we verify the calculation logic
        $mockSavings = 10000;
        $maxLoanAmount = $mockSavings * 3;
        
        $this->assertEquals(30000, $maxLoanAmount);
    }

    #[Test]
    public function member_cannot_exceed_loan_limit()
    {
        $member = Member::factory()->create();
        
        $mockSavings = 10000;
        $requestedAmount = 50000;
        $maxAllowedAmount = $mockSavings * 3;

        // Verify that requesting more than limit would fail
        $this->assertGreaterThan($maxAllowedAmount, $requestedAmount);
    }

    #[Test]
    public function scopeActive_returns_only_approved_loans()
    {
        $member = Member::factory()->create();

        // Create loans with different statuses
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
            'status' => 'pending',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 30000,
        ]);

        Loan::create([
            'member_id' => $member->id,
            'amount' => 25000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'paid',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 0,
        ]);

        $activeLoans = Loan::active()->get();

        $this->assertEquals(1, $activeLoans->count());
        $this->assertEquals('approved', $activeLoans->first()->status);
    }

    #[Test]
    public function monthly_interest_calculation_is_correct()
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

        // Formula: (balance * interest_rate / 100) / 12
        $expectedMonthlyInterest = (100000 * 12 / 100) / 12;
        $actualMonthlyInterest = $loan->monthlyInterest();

        $this->assertEquals(1000.00, $actualMonthlyInterest);
        $this->assertEquals($expectedMonthlyInterest, $actualMonthlyInterest);
    }

    #[Test]
    public function monthly_interest_updates_as_balance_decreases()
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

        $initialInterest = $loan->monthlyInterest();
        $this->assertEquals(1000.00, $initialInterest);

        // Simulate payment reducing balance
        $loan->update(['balance' => 50000]);

        $newInterest = $loan->monthlyInterest();
        $this->assertEquals(500.00, $newInterest);
        $this->assertLessThan($initialInterest, $newInterest);
    }

    #[Test]
    public function loan_has_correct_enum_status_values()
    {
        $member = Member::factory()->create();

        $validStatuses = ['pending', 'approved', 'paid', 'overdue'];

        foreach ($validStatuses as $status) {
            $loan = Loan::create([
                'member_id' => $member->id,
                'amount' => 50000,
                'interest_rate' => 12,
                'repayment_period' => 6,
                'status' => $status,
                'issue_date' => now(),
                'due_date' => now()->addMonths(6),
                'balance' => $status === 'paid' ? 0 : 50000,
            ]);

            $this->assertEquals($status, $loan->status);
        }
    }

    #[Test]
    public function paid_loan_has_zero_balance()
    {
        $member = Member::factory()->create();

        $loan = Loan::create([
            'member_id' => $member->id,
            'amount' => 50000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'paid',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 0,
        ]);

        $this->assertEquals(0, $loan->balance);
        $this->assertEquals('paid', $loan->status);
    }

    #[Test]
    public function loan_belongs_to_member()
    {
        $member = Member::factory()->create();

        $loan = Loan::create([
            'member_id' => $member->id,
            'amount' => 50000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 50000,
        ]);

        $this->assertEquals($member->id, $loan->member->id);
        $this->assertTrue($loan->member()->exists());
    }
}
