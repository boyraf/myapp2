<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Share;
use App\Models\Member;

class ShareFactory extends Factory
{
    protected $model = Share::class;

    public function definition()
    {
        return [
            'member_id' => Member::factory(),
            'quantity' => $this->faker->numberBetween(1, 100),
            'price_per_share' => $this->faker->randomFloat(2, 100, 1000),
            'total_value' => $this->faker->randomFloat(2, 1000, 100000),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'acquired_at' => $this->faker->dateTimeThisYear(),
        ];
    }
}
