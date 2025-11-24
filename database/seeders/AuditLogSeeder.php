<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuditLog;
use App\Models\Admin;

class AuditLogSeeder extends Seeder
{
    public function run()
    {
        $admins = Admin::all();

        // If no admins exist, create a default one
        if ($admins->isEmpty()) {
            $admins = collect([
                Admin::factory()->create([
                    'name' => 'Super Admin',
                    'email' => 'admin@sacco.com',
                    'password' => bcrypt('password123'),
                    'role' => 'superadmin'
                ])
            ]);
        }

        // For each admin, create 5–10 fake audit logs
        foreach ($admins as $admin) {
            AuditLog::factory()->count(rand(5, 10))->create([
                'admin_id' => $admin->id
            ]);
        }
    }
}
