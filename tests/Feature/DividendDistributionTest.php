<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\Share;

class DividendDistributionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dividends_are_distributed_proportionally_to_shares()
    {
        // Create members with shares
        $m1 = Member::factory()->create(['shares' => 10]);
        $m2 = Member::factory()->create(['shares' => 30]);
        $m3 = Member::factory()->create(['shares' => 60]);

        $total = 1000; // distribute 1000

        $response = $this->actingAs(Member::factory()->create(), 'admin')
                         ->post(route('admin.shares.distribute'), ['amount' => $total, 'description' => 'Yearly dividend']);

        $response->assertSessionHas('success');

        // Check transactions
        $this->assertDatabaseHas('transactions', ['member_id' => $m1->id, 'type' => 'dividend']);
        $this->assertDatabaseHas('transactions', ['member_id' => $m2->id, 'type' => 'dividend']);
        $this->assertDatabaseHas('transactions', ['member_id' => $m3->id, 'type' => 'dividend']);

        // Verify amounts roughly match the ratios
        $t1 = Transaction::where('member_id', $m1->id)->where('type', 'dividend')->first();
        $t2 = Transaction::where('member_id', $m2->id)->where('type', 'dividend')->first();
        $t3 = Transaction::where('member_id', $m3->id)->where('type', 'dividend')->first();

        $this->assertEquals(100.00, $t1->amount);
        $this->assertEquals(300.00, $t2->amount);
        $this->assertEquals(600.00, $t3->amount);
    }
}
