<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Admin;
use App\Models\Member;
use App\Models\Loan;
use App\Models\Guarantor;
use App\Models\Transaction;

class LoanApprovalTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_approve_loan_when_guarantor_coverage_is_sufficient()
    {
        $admin = Admin::factory()->create();
        $member = Member::factory()->create();

        $loan = Loan::create([
            'member_id' => $member->id,
            'amount' => 100000,
            'interest_rate' => 10,
            'repayment_period' => 12,
            'status' => 'pending',
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addMonths(12),
            'balance' => 100000,
        ]);

        // Create guarantors whose coverage sums to 60,000 (>= 50% requirement)
        $g1 = Member::factory()->create();
        $g2 = Member::factory()->create();

        Guarantor::create(['loan_id' => $loan->id, 'guarantor_id' => $g1->id, 'amount_guaranteed' => 30000]);
        Guarantor::create(['loan_id' => $loan->id, 'guarantor_id' => $g2->id, 'amount_guaranteed' => 30000]);

        // Act as admin and approve
        $response = $this->actingAs($admin, 'admin')
                         ->post(route('admin.loans.approve', $loan->id));

        $response->assertRedirect(route('admin.loans'));
        $this->assertEquals('approved', $loan->fresh()->status);

        $this->assertDatabaseHas('transactions', [
            'member_id' => $member->id,
            'type' => 'loan_disbursement',
            'amount' => 100000,
        ]);
    }

    /** @test */
    public function admin_cannot_approve_when_coverage_is_insufficient()
    {
        $admin = Admin::factory()->create();
        $member = Member::factory()->create();

        $loan = Loan::create([
            'member_id' => $member->id,
            'amount' => 50000,
            'interest_rate' => 10,
            'repayment_period' => 6,
            'status' => 'pending',
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addMonths(6),
            'balance' => 50000,
        ]);

        // Create guarantor with insufficient coverage (10,000 < 25,000 required)
        $g1 = Member::factory()->create();
        Guarantor::create(['loan_id' => $loan->id, 'guarantor_id' => $g1->id, 'amount_guaranteed' => 10000]);

        $response = $this->actingAs($admin, 'admin')
                         ->post(route('admin.loans.approve', $loan->id));

        $response->assertRedirect(route('admin.loans'));
        $response->assertSessionHas('error');
        $this->assertEquals('pending', $loan->fresh()->status);

        $this->assertDatabaseMissing('transactions', [
            'member_id' => $member->id,
            'type' => 'loan_disbursement',
        ]);
    }
}
