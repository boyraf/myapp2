<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Models\Transaction;

class DistributeDividends extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dividends:distribute {amount : Total distributable amount} {--d|description= : Description for the distribution}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Distribute dividends to members based on shares';

    public function handle()
    {
        $amount = (float) $this->argument('amount');
        $description = $this->option('description') ?? 'Dividend distribution';

        if ($amount <= 0) {
            $this->error('Amount must be greater than zero.');
            return 1;
        }

        $totalShares = Member::sum('shares');

        if ($totalShares <= 0) {
            $this->error('No shares found to distribute dividends to.');
            return 1;
        }

        $members = Member::where('shares', '>', 0)->get();

        foreach ($members as $member) {
            $ratio = $member->shares / $totalShares;
            $memberAmount = round($amount * $ratio, 2);

            if ($memberAmount <= 0) {
                continue;
            }

            Transaction::create([
                'member_id' => $member->id,
                'type' => 'dividend',
                'amount' => $memberAmount,
                'balance_after' => $memberAmount,
                'description' => $description,
            ]);

            $this->info("Distributed {$memberAmount} to member {$member->id}");
        }

        $this->info('Dividend distribution complete.');

        return 0;
    }
}
