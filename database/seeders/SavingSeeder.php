<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Saving;
use App\Models\Member;

class SavingSeeder extends Seeder
{
    public function run()
    {
        // For each member, generate 1-5 savings entries
        $members = Member::all();
        foreach ($members as $member) {
            Saving::factory()->count(rand(1,5))->create([
                'member_id' => $member->id
            ]);
        }
    }
}
