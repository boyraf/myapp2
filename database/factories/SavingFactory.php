<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Saving;

class SavingFactory extends Factory
{
    protected $model = Saving::class;

    public function definition()
    {
        $amount = $this->faker->randomFloat(2, 100, 5000); // between 100 and 5000
        $type = $this->faker->randomElement(['deposit','withdrawal']);

        return [
            'amount' => $amount,
            'type' => $type,
            'balance_after' => $this->faker->randomFloat(2, 1000, 10000),
            'date' => $this->faker->date(),
        ];
    }
}
