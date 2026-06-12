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
        $users = User::where('plan', '!=', 'starter')
            ->where('plan_expires_at', '<', now())
            ->get();

        foreach ($users as $user) {
            $user->update([
                'plan'            => 'starter',
                'plan_expires_at' => null,
            ]);

            $limit = $user->maxListings();

            $excess = $user->listings()
                ->where('status', 'active')
                ->orderByDesc('created_at')
                ->skip($limit)
                ->get();

            foreach ($excess as $listing) {
                $listing->update([
                    'status'        => 'paused',
                    'paused_reason' => 'plan_limit',
                ]);
            }
        }

        $this->info("Downgraded {$users->count()} expired subscriptions.");
    }
}
