<?php

namespace App\Console\Commands;

use App\Models\Listing;
use Illuminate\Console\Command;

class ExpireBoosts extends Command
{
    protected $signature   = 'boosts:expire';
    protected $description = 'Expire boosted listings after 24h';

    public function handle(): void
    {
        Listing::where('is_boosted', true)
            ->where('boosted_until', '<=', now())
            ->update([
                'is_boosted'    => false,
                'boosted_until' => null,
            ]);

        $this->info('Expired boosts cleared.');
    }
}
