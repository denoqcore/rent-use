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
                Section::make('Основная информация')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Название')
                            ->required(),

                        Select::make('user_id')
                            ->label('Владелец')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),

                        Select::make('category_id')
                            ->label('Категория')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->required(),

                        TextInput::make('city')
                            ->label('Город')
                            ->required(),

                        TextInput::make('address')
                            ->label('Адрес'),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required(),

                        Textarea::make('description')
                            ->label('Описание')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Section::make('Цены')
                    ->columns(2)
                    ->schema([
                        TextInput::make('price_per_day')
                            ->label('Цена в день')
                            ->numeric(),

                        TextInput::make('price_per_hour')
                            ->label('Цена в час')
                            ->numeric(),

                        TextInput::make('deposit')
                            ->label('Залог')
                            ->numeric(),

                        Select::make('currency')
                            ->label('Валюта')
                            ->options([
                                'MDL' => 'MDL — Лей',
                                'USD' => 'USD — Доллар',
                                'EUR' => 'EUR — Евро',
                            ])
                            ->default('MDL')
                            ->required(),
                    ]),

                Section::make('Доставка и условия')
                    ->columns(2)
                    ->schema([
                        Toggle::make('delivery_available')
                            ->label('Доставка доступна')
                            ->reactive(),

                        TextInput::make('delivery_price')
                            ->label('Цена доставки')
                            ->numeric()
                            ->visible(fn ($get) => $get('delivery_available')),

                        Toggle::make('requires_document')
                            ->label('Требуется документ'),
                    ]),

                Section::make('Статус')
                    ->schema([
                        Select::make('status')
                            ->label('Статус')
                            ->options([
                                'active'   => 'Активно',
                                'paused'   => 'Пауза',
                                'archived' => 'Архив',
                            ])
                            ->default('active')
                            ->required(),
                    ]),
            ]);
    }
}
