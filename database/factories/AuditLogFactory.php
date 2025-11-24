<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AuditLog;

class AuditLogFactory extends Factory
{
    protected $model = AuditLog::class;

    public function definition()
    {
        return [
            'admin_id' => 1, // or pick random admin ID if multiple
            'action' => $this->faker->randomElement(['created_member','approved_loan','updated_saving','deleted_transaction']),
            'details' => $this->faker->sentence(),
        ];
    }
}
