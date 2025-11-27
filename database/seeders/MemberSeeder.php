<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Member;

class MemberSeeder extends Seeder
{
    public function run()
    {
        // Generate 20 fake members
        Member::factory()->count(20)->create([
            'password' => Hash::make('Password@123'), // default password for all seeded members
        ]);
    }
}
