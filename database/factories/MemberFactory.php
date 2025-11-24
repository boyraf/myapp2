<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Member;

class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'id_number' => $this->faker->unique()->numerify('########'),
            'date_of_birth' => $this->faker->date(),
            'address' => $this->faker->address(),
            'membership_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['active','inactive']),
        ];
    }
}
