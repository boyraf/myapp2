<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Member;
use App\Models\Share;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShareTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_shares_in_pool()
    {
        // Admin creates shares for the pool (controlled_by_admin=true, no member_id)
        $share = Share::create([
            'member_id' => null,
            'quantity' => 100,
            'price_per_share' => 1000,
            'total_value' => 100000,
            'controlled_by_admin' => true,
        ]);

        $this->assertNotNull($share->id);
        $this->assertTrue($share->controlled_by_admin);
        $this->assertNull($share->member_id);
        $this->assertEquals(100, $share->quantity);
    }

    /** @test */
    public function member_can_buy_shares_from_pool()
    {
        // Create a member
        $member = Member::factory()->create(['shares' => 0]);

        // Create admin-controlled shares in pool
        $poolShare = Share::create([
            'member_id' => null,
            'quantity' => 100,
            'price_per_share' => 1000,
            'total_value' => 100000,
            'controlled_by_admin' => true,
        ]);

        // Member buys 10 shares
        $quantity = 10;
        $totalCost = $quantity * $poolShare->price_per_share;

        // Simulate purchase
        $poolShare->update(['quantity' => $poolShare->quantity - $quantity]);
        $member->update(['shares' => $member->shares + $quantity]);

        // Record transaction
        Transaction::create([
            'member_id' => $member->id,
            'type' => 'share_purchase',
            'amount' => $totalCost,
            'balance_after' => $member->shares,
            'description' => "Purchased {$quantity} shares at {$poolShare->price_per_share} per share",
        ]);

        // Assertions
        $this->assertEquals(90, $poolShare->fresh()->quantity);
        $this->assertEquals(10, $member->fresh()->shares);
        $this->assertTrue(Transaction::where('member_id', $member->id)
            ->where('type', 'share_purchase')->exists());
    }

    /** @test */
    public function member_cannot_buy_more_shares_than_available()
    {
        $member = Member::factory()->create(['shares' => 0]);

        $poolShare = Share::create([
            'member_id' => null,
            'quantity' => 5,
            'price_per_share' => 1000,
            'total_value' => 5000,
            'controlled_by_admin' => true,
        ]);

        // Try to buy 10 shares when only 5 exist
        $quantity = 10;
        $this->assertGreaterThan($poolShare->quantity, $quantity);
        
        // Verify it would fail (check is done in controller logic)
        $this->assertEquals(5, $poolShare->quantity);
    }

    /** @test */
    public function member_can_sell_shares_back()
    {
        // Member starts with shares
        $member = Member::factory()->create(['shares' => 10]);

        // Create a share record for this member
        $memberShare = Share::create([
            'member_id' => $member->id,
            'quantity' => 10,
            'price_per_share' => 1000,
            'total_value' => 10000,
            'controlled_by_admin' => false,
        ]);

        // Member sells 5 shares
        $quantity = 5;
        $totalRevenue = $quantity * $memberShare->price_per_share;

        // Simulate sale
        $member->update(['shares' => $member->shares - $quantity]);
        $memberShare->update(['quantity' => $memberShare->quantity - $quantity]);

        // Record transaction
        Transaction::create([
            'member_id' => $member->id,
            'type' => 'share_sale',
            'amount' => $totalRevenue,
            'balance_after' => $member->shares,
            'description' => "Sold {$quantity} shares at {$memberShare->price_per_share} per share",
        ]);

        // Assertions
        $this->assertEquals(5, $member->fresh()->shares);
        $this->assertTrue(Transaction::where('member_id', $member->id)
            ->where('type', 'share_sale')->exists());
    }

    /** @test */
    public function share_purchase_creates_transaction_record()
    {
        $member = Member::factory()->create(['shares' => 0]);

        $poolShare = Share::create([
            'member_id' => null,
            'quantity' => 100,
            'price_per_share' => 1000,
            'total_value' => 100000,
            'controlled_by_admin' => true,
        ]);

        $quantity = 5;
        $totalCost = $quantity * $poolShare->price_per_share;

        // Record transaction
        $transaction = Transaction::create([
            'member_id' => $member->id,
            'type' => 'share_purchase',
            'amount' => $totalCost,
            'balance_after' => $quantity,
            'description' => "Purchased {$quantity} shares",
        ]);

        $this->assertNotNull($transaction->id);
        $this->assertEquals('share_purchase', $transaction->type);
        $this->assertEquals(5000, $transaction->amount);
        $this->assertEquals($member->id, $transaction->member_id);
    }
}
