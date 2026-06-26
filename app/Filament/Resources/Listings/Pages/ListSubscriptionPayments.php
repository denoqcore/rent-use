<?php

namespace App\Filament\Resources\Listings\Pages;

use App\Filament\Resources\Listings\SubscriptionPaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSubscriptionPayments extends ListRecords
{
    protected static string $resource = SubscriptionPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
