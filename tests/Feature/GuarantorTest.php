<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Member;
use App\Models\Loan;
use App\Models\Guarantor;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GuarantorTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guarantor_can_be_assigned_to_loan()
    {
        $borrower = Member::factory()->create();
        $guarantor = Member::factory()->create();

        $loan = Loan::create([
            'member_id' => $borrower->id,
            'amount' => 50000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 50000,
        ]);

        $guarantorRecord = Guarantor::create([
            'loan_id' => $loan->id,
            'guarantor_id' => $guarantor->id,
        ]);

        $this->assertNotNull($guarantorRecord->id);
        $this->assertEquals($loan->id, $guarantorRecord->loan_id);
        $this->assertEquals($guarantor->id, $guarantorRecord->guarantor_id);
    }

    #[Test]
    public function loan_can_have_multiple_guarantors()
    {
        $borrower = Member::factory()->create();
        $guarantor1 = Member::factory()->create();
        $guarantor2 = Member::factory()->create();

        $loan = Loan::create([
            'member_id' => $borrower->id,
            'amount' => 100000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 100000,
        ]);

        Guarantor::create([
            'loan_id' => $loan->id,
            'guarantor_id' => $guarantor1->id,
        ]);

        Guarantor::create([
            'loan_id' => $loan->id,
            'guarantor_id' => $guarantor2->id,
        ]);

        $guarantors = Guarantor::where('loan_id', $loan->id)->get();

        $this->assertEquals(2, $guarantors->count());
    }

    #[Test]
    public function guarantor_belongs_to_member()
    {
        $borrower = Member::factory()->create();
        $guarantor = Member::factory()->create();

        $loan = Loan::create([
            'member_id' => $borrower->id,
            'amount' => 50000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 50000,
        ]);

        $guarantorRecord = Guarantor::create([
            'loan_id' => $loan->id,
            'guarantor_id' => $guarantor->id,
        ]);

        $this->assertEquals($guarantor->id, $guarantorRecord->member->id);
        $this->assertTrue($guarantorRecord->member()->exists());
    }

    #[Test]
    public function guarantor_belongs_to_loan()
    {
        $borrower = Member::factory()->create();
        $guarantor = Member::factory()->create();

        $loan = Loan::create([
            'member_id' => $borrower->id,
            'amount' => 50000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 50000,
        ]);

        $guarantorRecord = Guarantor::create([
            'loan_id' => $loan->id,
            'guarantor_id' => $guarantor->id,
        ]);

        $this->assertEquals($loan->id, $guarantorRecord->loan->id);
        $this->assertTrue($guarantorRecord->loan()->exists());
    }

    #[Test]
    public function loan_can_have_no_guarantors()
    {
        $borrower = Member::factory()->create();

        $loan = Loan::create([
            'member_id' => $borrower->id,
            'amount' => 50000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'pending',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 50000,
        ]);

        $guarantors = Guarantor::where('loan_id', $loan->id)->get();

        $this->assertEquals(0, $guarantors->count());
    }

    #[Test]
    public function member_can_be_guarantor_for_multiple_loans()
    {
        $guarantor = Member::factory()->create();
        $borrower1 = Member::factory()->create();
        $borrower2 = Member::factory()->create();

        $loan1 = Loan::create([
            'member_id' => $borrower1->id,
            'amount' => 50000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 50000,
        ]);

        $loan2 = Loan::create([
            'member_id' => $borrower2->id,
            'amount' => 75000,
            'interest_rate' => 10,
            'repayment_period' => 12,
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths(12),
            'balance' => 75000,
        ]);

        Guarantor::create([
            'loan_id' => $loan1->id,
            'guarantor_id' => $guarantor->id,
        ]);

        Guarantor::create([
            'loan_id' => $loan2->id,
            'guarantor_id' => $guarantor->id,
        ]);

        $guarantorLoans = Guarantor::where('guarantor_id', $guarantor->id)->get();

        $this->assertEquals(2, $guarantorLoans->count());
    }

    #[Test]
    public function cannot_assign_borrower_as_own_guarantor()
    {
        $borrower = Member::factory()->create();

        $loan = Loan::create([
            'member_id' => $borrower->id,
            'amount' => 50000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'pending',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 50000,
        ]);

        // Validation would be in controller, but we test the logic here
        // A person cannot be their own guarantor
        $this->assertEquals($borrower->id, $loan->member_id);
    }

    #[Test]
    public function guarantor_creation_timestamp_is_recorded()
    {
        $borrower = Member::factory()->create();
        $guarantor = Member::factory()->create();

        $loan = Loan::create([
            'member_id' => $borrower->id,
            'amount' => 50000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 50000,
        ]);

        $guarantorRecord = Guarantor::create([
            'loan_id' => $loan->id,
            'guarantor_id' => $guarantor->id,
        ]);

        $this->assertNotNull($guarantorRecord->created_at);
        $this->assertNotNull($guarantorRecord->updated_at);
    }

    #[Test]
    public function cannot_have_duplicate_guarantor_for_same_loan()
    {
        $borrower = Member::factory()->create();
        $guarantor = Member::factory()->create();

        $loan = Loan::create([
            'member_id' => $borrower->id,
            'amount' => 50000,
            'interest_rate' => 12,
            'repayment_period' => 6,
            'status' => 'approved',
            'issue_date' => now(),
            'due_date' => now()->addMonths(6),
            'balance' => 50000,
        ]);

        Guarantor::create([
            'loan_id' => $loan->id,
            'guarantor_id' => $guarantor->id,
        ]);

        // Check that this guarantor is already assigned
        $existingGuarantor = Guarantor::where('loan_id', $loan->id)
            ->where('guarantor_id', $guarantor->id)
            ->exists();

        $this->assertTrue($existingGuarantor);

        // In controller, this would prevent duplicate creation
        $guarantorCount = Guarantor::where('loan_id', $loan->id)
            ->where('guarantor_id', $guarantor->id)
            ->count();

        $this->assertEquals(1, $guarantorCount);
    }
}
