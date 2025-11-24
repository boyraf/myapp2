<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Repayment;
use App\Models\Loan;

class RepaymentSeeder extends Seeder
{
    public function run()
    {
        $loans = Loan::all();
        foreach ($loans as $loan) {
            Repayment::factory()->count(rand(1, $loan->repayment_period))->create([
                'loan_id' => $loan->id
            ]);
        }
    }
}
