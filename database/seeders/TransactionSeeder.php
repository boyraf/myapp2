<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Member;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        $members = Member::all();
        foreach ($members as $member) {
            Transaction::factory()->count(rand(2,5))->create([
                'member_id' => $member->id
            ]);
        }
    }
}
