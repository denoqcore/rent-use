<?php

namespace App\Filament\Resources\Listings\Pages;

use App\Filament\Resources\Listings\SubscriptionPaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSubscriptionPayment extends CreateRecord
{
    protected static string $resource = SubscriptionPaymentResource::class;
}
