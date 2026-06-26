<?php

namespace App\Filament\Resources\Listings\Pages;

use App\Filament\Resources\Listings\SubscriptionPaymentResource;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSubscriptionPayment extends ViewRecord
{
    protected static string $resource = SubscriptionPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Edit'),
            DeleteAction::make()->label('Delete'),
        ];
    }
}
