<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetDailyBoosts extends Command
{
    protected $signature = 'boosts:reset-daily';
    protected $description = 'Reset daily boost counter for all users';

    public function handle(): void
    {
        User::where('boosts_used_today', '>', 0)
            ->update(['boosts_used_today' => 0]);
    }
}
