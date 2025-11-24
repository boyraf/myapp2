<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call all your SACCO seeders
        $this->call([
            AdminSeeder::class,
            MemberSeeder::class,
            SavingSeeder::class,
            LoanSeeder::class,
            RepaymentSeeder::class,
            TransactionSeeder::class,
            AuditLogSeeder::class,
        ]);
    }
}
