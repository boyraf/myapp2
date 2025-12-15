<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Member;
use App\Models\Loan;
use App\Models\Repayment;
use App\Models\Share;
use App\Models\Transaction;
use App\Models\Admin;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    /**
     * @test
     */
    public function admin_can_view_loan_statement_report()
    {
        $member = Member::factory()->create(['shares' => 10]);
        Loan::factory()->create([
            'member_id' => $member->id,
            'status' => 'approved',
            'issue_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reports.loans'));

        $response->assertStatus(200);
        $response->assertViewHas('loans');
        $response->assertViewHas('totalLoans', 1);
        $response->assertSee($member->name);
    }

    /**
     * @test
     */
    public function loan_statement_filters_by_status()
    {
        $member = Member::factory()->create(['shares' => 10]);
        Loan::factory()->create([
            'member_id' => $member->id,
            'status' => 'approved',
            'issue_date' => now()->toDateString(),
        ]);
        Loan::factory()->create([
            'member_id' => $member->id,
            'status' => 'pending',
            'issue_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reports.loans', ['status' => 'approved']));

        $response->assertStatus(200);
        $response->assertViewHas('totalLoans', 1);
    }

    /**
     * @test
     */
    public function admin_can_view_repayment_schedule_report()
    {
        $member = Member::factory()->create(['shares' => 10]);
        $loan = Loan::factory()->create([
            'member_id' => $member->id,
            'status' => 'approved',
            'issue_date' => now()->toDateString(),
        ]);
        Repayment::factory()->create([
            'loan_id' => $loan->id,
            'balance_after_payment' => 500,
            'payment_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reports.repayments'));

        $response->assertStatus(200);
        $response->assertViewHas('repayments');
        $response->assertViewHas('totalRepayments', 1);
    }

    /**
     * @test
     */
    public function repayment_schedule_filters_by_member()
    {
        $member1 = Member::factory()->create(['shares' => 10]);
        $member2 = Member::factory()->create(['shares' => 10]);

        $loan1 = Loan::factory()->create([
            'member_id' => $member1->id,
            'status' => 'approved',
            'issue_date' => now()->toDateString(),
        ]);
        $loan2 = Loan::factory()->create([
            'member_id' => $member2->id,
            'status' => 'approved',
            'issue_date' => now()->toDateString(),
        ]);

        Repayment::factory()->create([
            'loan_id' => $loan1->id,
            'balance_after_payment' => 500,
            'payment_date' => now()->toDateString(),
        ]);
        Repayment::factory()->create([
            'loan_id' => $loan2->id,
            'balance_after_payment' => 500,
            'payment_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reports.repayments', ['member_id' => $member1->id]));

        $response->assertStatus(200);
        $response->assertViewHas('totalRepayments', 1);
    }

    /**
     * @test
     */
    public function admin_can_view_share_holdings_report()
    {
        $member = Member::factory()->create(['shares' => 50]);
        Share::factory()->create([
            'member_id' => $member->id,
            'quantity' => 20,
            'price_per_share' => 100,
            'total_value' => 2000,
            'acquired_at' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reports.shares'));

        $response->assertStatus(200);
        $response->assertViewHas('shares');
        $response->assertViewHas('totalShares', 20);
    }

    /**
     * @test
     */
    public function admin_can_view_dividend_statement_report()
    {
        $member = Member::factory()->create(['shares' => 50]);
        Transaction::factory()->create([
            'member_id' => $member->id,
            'type' => 'dividend',
            'amount' => 500,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reports.dividends'));

        $response->assertStatus(200);
        $response->assertViewHas('dividends');
        $response->assertViewHas('totalDividends', 1);
        $response->assertViewHas('totalAmount', 500);
    }

    /**
     * @test
     */
    public function dividend_statement_filters_by_member()
    {
        $member1 = Member::factory()->create(['shares' => 50]);
        $member2 = Member::factory()->create(['shares' => 50]);

        Transaction::factory()->create([
            'member_id' => $member1->id,
            'type' => 'dividend',
            'amount' => 500,
        ]);
        Transaction::factory()->create([
            'member_id' => $member2->id,
            'type' => 'dividend',
            'amount' => 750,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reports.dividends', ['member_id' => $member1->id]));

        $response->assertStatus(200);
        $response->assertViewHas('totalDividends', 1);
        $response->assertViewHas('totalAmount', 500);
    }

    /**
     * @test
     */
    public function admin_can_export_loan_statement_to_csv()
    {
        $member = Member::factory()->create(['shares' => 10]);
        Loan::factory()->create([
            'member_id' => $member->id,
            'status' => 'approved',
            'amount' => 5000,
            'interest_rate' => 5,
            'repayment_period' => 12,
            'issue_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reports.loans.csv'));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }

    /**
     * @test
     */
    public function admin_can_export_repayment_schedule_to_csv()
    {
        $member = Member::factory()->create(['shares' => 10]);
        $loan = Loan::factory()->create([
            'member_id' => $member->id,
            'status' => 'approved',
            'issue_date' => now()->toDateString(),
        ]);
        Repayment::factory()->create([
            'loan_id' => $loan->id,
            'amount_paid' => 1000,
            'balance_after_payment' => 500,
            'payment_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reports.repayments.csv'));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }

    /**
     * @test
     */
    public function admin_can_export_share_holdings_to_csv()
    {
        $member = Member::factory()->create(['shares' => 50]);
        Share::factory()->create([
            'member_id' => $member->id,
            'quantity' => 20,
            'price_per_share' => 100,
            'total_value' => 2000,
            'acquired_at' => now(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reports.shares.csv'));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }

    /**
     * @test
     */
    public function admin_can_export_dividend_statement_to_csv()
    {
        $member = Member::factory()->create(['shares' => 50]);
        Transaction::factory()->create([
            'member_id' => $member->id,
            'type' => 'dividend',
            'amount' => 500,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reports.dividends.csv'));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
    }

    /**
     * @test
     */
    public function member_cannot_access_reports()
    {
        $member = Member::factory()->create(['shares' => 10]);

        $response = $this->actingAs($member, 'member')
            ->get(route('admin.reports.loans'));

        // Should redirect to login or show 403
        $response->assertStatus(302); // Redirect because not authenticated as admin
    }

    /**
     * @test
     */
    public function loan_statement_shows_correct_summary_stats()
    {
        $member = Member::factory()->create(['shares' => 10]);
        
        Loan::factory()->create([
            'member_id' => $member->id,
            'status' => 'approved',
            'amount' => 5000,
            'balance' => 2500,
            'issue_date' => now()->toDateString(),
        ]);
        Loan::factory()->create([
            'member_id' => $member->id,
            'status' => 'approved',
            'amount' => 3000,
            'balance' => 1500,
            'issue_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reports.loans'));

        $response->assertViewHas('totalLoans', 2);
        $response->assertViewHas('totalAmount', 8000);
        $response->assertViewHas('totalBalance', 4000);
    }

    /**
     * @test
     */
    public function repayment_schedule_shows_correct_summary_stats()
    {
        $member = Member::factory()->create(['shares' => 10]);
        $loan = Loan::factory()->create([
            'member_id' => $member->id,
            'status' => 'approved',
            'issue_date' => now()->toDateString(),
        ]);

        Repayment::factory()->create([
            'loan_id' => $loan->id,
            'amount_paid' => 1000,
            'balance_after_payment' => 500,
            'payment_date' => now()->toDateString(),
        ]);
        Repayment::factory()->create([
            'loan_id' => $loan->id,
            'amount_paid' => 500,
            'balance_after_payment' => 0,
            'payment_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reports.repayments'));

        $response->assertViewHas('totalRepayments', 2);
        $response->assertViewHas('totalAmountPaid', 1500);
        $response->assertViewHas('avgRepayment', 750);
    }
}
