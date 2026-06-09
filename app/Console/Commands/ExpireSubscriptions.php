<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ExpireSubscriptions extends Command
{
    protected $signature   = 'subscriptions:expire';
    protected $description = 'Downgrade users with expired subscriptions to starter';

    public function handle(): void
    {
        $count = User::where('plan', '!=', 'starter')
            ->where('plan_expires_at', '<', now())
            ->update([
                'plan'            => 'starter',
                'plan_expires_at' => null,
            ]);

        $this->info("Downgraded {$count} expired subscriptions.");
    }
}
