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
            Stat::make('Всего объявлений', Listing::count())
                ->description('Активных: ' . Listing::where('status', 'active')->count())
                ->descriptionIcon('heroicon-m-home')
                ->color('success'),

            Stat::make('На паузе', Listing::where('status', 'paused')->count())
                ->description('Архивных: ' . Listing::where('status', 'archived')->count())
                ->descriptionIcon('heroicon-m-pause-circle')
                ->color('warning'),

            Stat::make('Пользователей', User::count())
                ->description('Новых сегодня: ' . User::whereDate('created_at', today())->count())
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Бронирований', Booking::count())
                ->description('Сегодня: ' . Booking::whereDate('created_at', today())->count())
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
        ];
    }
}
