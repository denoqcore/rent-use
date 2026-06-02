<?php

namespace App\Filament\Resources\Listings\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ListingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('category.id')
                    ->label('Category'),
                TextEntry::make('city_id')
                    ->numeric(),
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->columnSpanFull(),
                TextEntry::make('slug'),
                TextEntry::make('price_per_day')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('price_per_hour')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('deposit')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('currency'),
                TextEntry::make('address')
                    ->placeholder('-'),
                IconEntry::make('delivery_available')
                    ->boolean(),
                TextEntry::make('delivery_price')
                    ->money()
                    ->placeholder('-'),
                IconEntry::make('requires_document')
                    ->boolean(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
