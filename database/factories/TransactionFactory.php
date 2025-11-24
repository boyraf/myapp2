<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transaction;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        $type = $this->faker->randomElement(['saving_deposit','saving_withdrawal','loan_disbursement','repayment']);
        $amount = $this->faker->randomFloat(2, 50, 5000);

        return [
            'type' => $type,
            'amount' => $amount,
            'balance_after' => $this->faker->randomFloat(2, 0, 10000),
            'description' => $this->faker->sentence(),
        ];
    }
}
