<?php

namespace App\View\Components;

use App\Models\Listing;
use Illuminate\View\Component;
use Illuminate\View\View;

class ListingStar extends Component
{
    public bool $show;

    public function __construct(Listing $listing)
    {
        $user = $listing->user;

        $this->show = $user->isActivePlan() && (
            $user->plan === 'premium' ||
            ($user->plan === 'pro' && $listing->is_boosted && $listing->boosted_until?->isFuture())
        );
    }

    public function render(): View
    {
        return view('components.listing-star');
    }
}
