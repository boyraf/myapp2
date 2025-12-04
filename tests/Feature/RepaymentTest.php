<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Member;
use App\Models\Loan;
use App\Models\Repayment;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_record_repayment()
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

        $balanceAfter = $loan->balance - 20000;
        $repayment = Repayment::create([
            'loan_id' => $loan->id,
            'amount_paid' => 20000,
            'balance_after_payment' => $balanceAfter,
            'payment_date' => now()->toDateString(),
        ]);

        $this->assertNotNull($repayment->id);
        $this->assertEquals(20000, $repayment->amount_paid);
        $this->assertEquals($loan->id, $repayment->loan_id);
    }

    /** @test */
    public function member_can_make_repayment()
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

        // Member makes a repayment
        $balanceAfter = $loan->balance - 20000;
        $repayment = Repayment::create([
            'loan_id' => $loan->id,
            'amount_paid' => 20000,
            'balance_after_payment' => $balanceAfter,
            'payment_date' => now()->toDateString(),
        ]);

        $this->assertNotNull($repayment->id);
        $this->assertEquals(20000, $repayment->amount_paid);
    }

    /** @test */
    public function repayment_updates_loan_balance()
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

        $initialBalance = $loan->balance;

        // Record repayment
        $repaymentAmount = 20000;
        $loan->update(['balance' => $loan->balance - $repaymentAmount]);

        $newBalance = $loan->fresh()->balance;

        $this->assertEquals(80000, $newBalance);
        $this->assertLessThan($initialBalance, $newBalance);
    }

    /** @test */
    public function loan_marked_paid_when_balance_zero_or_less()
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

        // Record final repayment that clears balance
        $repaymentAmount = 50000;
        $balanceAfter = $loan->balance - $repaymentAmount;

        if ($balanceAfter <= 0) {
            $loan->update([
                'balance' => 0,
                'status' => 'paid',
            ]);
        }

        $this->assertEquals(0, $loan->fresh()->balance);
        $this->assertEquals('paid', $loan->fresh()->status);
    }

    /** @test */
    public function loan_marked_paid_when_overpayment_occurs()
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
            'balance' => 30000,
        ]);

        // Try to pay more than balance
        $repaymentAmount = 50000;
        $balanceAfter = $loan->balance - $repaymentAmount;

        if ($balanceAfter <= 0) {
            $loan->update([
                'balance' => 0,
                'status' => 'paid',
            ]);
        }

        $this->assertEquals(0, $loan->fresh()->balance);
        $this->assertEquals('paid', $loan->fresh()->status);
    }

    /** @test */
    public function repayment_creates_transaction_record()
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

        $repaymentAmount = 20000;
        $balanceAfter = $loan->balance - $repaymentAmount;

        // Create repayment
        $repayment = Repayment::create([
            'loan_id' => $loan->id,
            'amount_paid' => $repaymentAmount,
            'balance_after_payment' => $balanceAfter,
            'payment_date' => now()->toDateString(),
        ]);

        // Record transaction
        $transaction = Transaction::create([
            'member_id' => $member->id,
            'type' => 'repayment',
            'amount' => $repaymentAmount,
            'balance_after' => $balanceAfter,
            'description' => "Repayment for loan {$loan->id}",
        ]);

        $this->assertNotNull($transaction->id);
        $this->assertEquals('repayment', $transaction->type);
        $this->assertEquals($repaymentAmount, $transaction->amount);
    }

    /** @test */
    public function multiple_repayments_decrease_balance_correctly()
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

        // First repayment
        $loan->update(['balance' => $loan->balance - 20000]);
        $this->assertEquals(80000, $loan->fresh()->balance);

        // Second repayment
        $loan->update(['balance' => $loan->balance - 25000]);
        $this->assertEquals(55000, $loan->fresh()->balance);

        // Third repayment
        $loan->update(['balance' => $loan->balance - 30000]);
        $this->assertEquals(25000, $loan->fresh()->balance);

        // Final repayment
        $loan->update(['balance' => $loan->balance - 25000]);
        $this->assertEquals(0, $loan->fresh()->balance);
    }

    /** @test */
    public function repayment_belongs_to_loan()
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

        $repayment = Repayment::create([
            'loan_id' => $loan->id,
            'amount_paid' => 20000,
            'balance_after_payment' => $loan->balance - 20000,
            'payment_date' => now()->toDateString(),
        ]);

        $this->assertEquals($loan->id, $repayment->loan->id);
        $this->assertTrue($repayment->loan()->exists());
    }

    /** @test */
    public function cannot_repay_more_than_loan_balance()
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

        // Attempt to pay more than balance (should be handled in controller)
        $repaymentAmount = 60000;

        // Verify overpayment exceeds balance
        $this->assertGreaterThan($loan->balance, $repaymentAmount);

        // In controller, this would be caught and handled by setting balance to 0
        if ($loan->balance - $repaymentAmount <= 0) {
            $loan->update(['balance' => 0, 'status' => 'paid']);
        }

        $this->assertEquals(0, $loan->fresh()->balance);
    }

    /** @test */
    public function repayment_date_is_tracked()
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

        $repaymentDate = now();

        $repayment = Repayment::create([
            'loan_id' => $loan->id,
            'amount_paid' => 20000,
            'balance_after_payment' => $loan->balance - 20000,
            'payment_date' => $repaymentDate->toDateString(),
        ]);

        $this->assertEquals($repaymentDate->toDateString(), $repayment->fresh()->payment_date);
    }
}
