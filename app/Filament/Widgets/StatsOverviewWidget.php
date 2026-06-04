<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Listing;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        return [
            Stat::make('Total listings', Listing::count())
                ->description('Active: ' . Listing::where('status', 'active')->count())
                ->descriptionIcon('heroicon-m-home')
                ->color('success'),

            Stat::make('Paused', Listing::where('status', 'paused')->count())
                ->description('Archived: ' . Listing::where('status', 'archived')->count())
                ->descriptionIcon('heroicon-m-pause-circle')
                ->color('warning'),

            Stat::make('Users', User::count())
                ->description('New today: ' . User::whereDate('created_at', today())->count())
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Bookings', Booking::count())
                ->description('Today: ' . Booking::whereDate('created_at', today())->count())
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
        ];
    }
}
