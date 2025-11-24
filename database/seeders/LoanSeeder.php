<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Loan;
use App\Models\Member;

class LoanSeeder extends Seeder
{
    public function run()
    {
        $members = Member::all();
        foreach ($members as $member) {
            Loan::factory()->count(rand(0,2))->create([
                'member_id' => $member->id
            ]);
        }
    }
}
