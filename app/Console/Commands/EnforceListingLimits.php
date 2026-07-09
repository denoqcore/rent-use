<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class EnforceListingLimits extends Command
{
    protected $signature   = 'listings:enforce-limits';
    protected $description = 'Retroactively pause excess active listings for all users based on current plan';

    public function handle(): void
    {
        $count = 0;
        User::chunk(100, function ($users) use (&$count) {
            foreach ($users as $user) {
                $before = $user->listings()->where('status', 'active')->count();
                $user->enforceListingLimit();
                $after = $user->listings()->where('status', 'active')->count();
                if ($after < $before) $count++;
            }
        });
        $this->info("Enforced limits, affected {$count} users.");
    }
}
