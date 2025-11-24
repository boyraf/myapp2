<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Loan;

class LoanFactory extends Factory
{
    protected $model = Loan::class;

    public function definition()
    {
        $amount = $this->faker->randomFloat(2, 1000, 20000);
        $interest = $this->faker->randomFloat(2, 5, 15); // 5% - 15%
        $period = $this->faker->numberBetween(3, 24); // months
        $issueDate = $this->faker->dateTimeBetween('-6 months', 'now');
        $dueDate = (clone $issueDate)->modify("+$period months");

        return [
            'amount' => $amount,
            'interest_rate' => $interest,
            'repayment_period' => $period,
            'status' => $this->faker->randomElement(['pending','approved','paid','overdue']),
            'issue_date' => $issueDate->format('Y-m-d'),
            'due_date' => $dueDate->format('Y-m-d'),
            'balance' => $amount + ($amount * $interest / 100),
        ];
    }
}
