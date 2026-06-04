<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('is_admin')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('avatar'),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Toggle::make('is_online')
                    ->required(),
                DateTimePicker::make('last_seen_at'),
                TextInput::make('response_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('message_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('avg_response_minutes')
                    ->numeric(),
            ]);
    }
}
