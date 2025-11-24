<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create a default admin
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@sacco.com',
            'password' => Hash::make('password123'),
            'role' => 'superadmin'
        ]);

        // Optional: generate 5 fake admins using factory (if you have one)
        // Admin::factory()->count(5)->create();
    }
}
