<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Repayment;

class RepaymentFactory extends Factory
{
    protected $model = Repayment::class;

    public function definition()
    {
        $amount = $this->faker->randomFloat(2, 100, 2000);
        return [
            'amount_paid' => $amount,
            'balance_after_payment' => $this->faker->randomFloat(2, 0, 10000),
            'payment_date' => $this->faker->date(),
        ];
    }
}
