<?php

namespace App\Filament\Resources\Listings\Pages;

use App\Filament\Resources\Listings\SubscriptionPaymentResource ;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSubscriptionPayment extends EditRecord
{
    protected static string $resource = SubscriptionPaymentResource ::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
