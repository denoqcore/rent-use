<?php

namespace App\Filament\Resources\SubscriptionPayments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SubscriptionPaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Payment')
                ->columns(2)
                ->schema([
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->required(),

                    Select::make('plan')
                        ->options([
                            'pro'     => 'Pro',
                            'premium' => 'Premium',
                        ])
                        ->required(),

                    Select::make('status')
                        ->options([
                            'pending'   => 'Pending',
                            'paid'      => 'Paid',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('pending')
                        ->required(),

                    TextInput::make('amount')
                        ->numeric()
                        ->required()
                        ->suffix(fn ($get) => $get('currency') ?? 'MDL'),

                    TextInput::make('currency')
                        ->default('MDL')
                        ->maxLength(3)
                        ->required(),

                    TextInput::make('payment_id')
                        ->label('Payment ID'),

                    DateTimePicker::make('paid_at'),

                    DateTimePicker::make('expires_at'),
                ]),
        ]);
    }
}
