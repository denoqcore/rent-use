<?php

namespace App\Filament\Resources\Listings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Schemas\Schema;

class ListingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Main information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required(),

                        Select::make('user_id')
                            ->label('Owner')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),

                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->required(),

                        TextInput::make('city')
                            ->label('City')
                            ->required(),

                        TextInput::make('address')
                            ->label('Adress'),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required(),

                        Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Цены')
                    ->columns(2)
                    ->schema([
                        TextInput::make('price_per_day')
                            ->label('Price/Day')
                            ->numeric(),

                        TextInput::make('price_per_hour')
                            ->label('Price/Hour')
                            ->numeric(),

                        TextInput::make('deposit')
                            ->label('Deposit')
                            ->numeric(),

                        Select::make('currency')
                            ->label('Currency')
                            ->options([
                                'MDL' => 'MDL — LEI',
                                'USD' => 'USD — USD',
                                'EUR' => 'EUR — EUR',
                            ])
                            ->default('MDL')
                            ->required(),
                    ]),

                Section::make('Delivery terms')
                    ->columns(2)
                    ->schema([
                        Toggle::make('delivery_available')
                            ->label('Delivery available')
                            ->reactive(),

                        TextInput::make('delivery_price')
                            ->label('Delivery price')
                            ->numeric()
                            ->visible(fn ($get) => $get('delivery_available')),

                        Toggle::make('requires_document')
                            ->label('Requires document'),
                    ]),

                Section::make('Status')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'active'   => 'Active',
                                'paused'   => 'Pause',
                                'archived' => 'Archive',
                            ])
                            ->default('active')
                            ->required(),
                    ]),
            ]);
    }
}
