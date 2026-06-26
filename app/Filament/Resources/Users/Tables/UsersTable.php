<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_admin')
                    ->boolean(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('plan')
                    ->label('Plan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'premium' => 'success',
                        'pro'     => 'info',
                        'starter' => 'gray',
                        default   => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('plan_expires_at')
                    ->label('Plan expires')
                    ->dateTime('d.m.Y')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('email_verified_at')
                    ->label('Verified')
                    ->dateTime('d.m.Y')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('plan')
                    ->label('Plan')
                    ->options([
                        'starter' => 'Starter',
                        'pro'     => 'Pro',
                        'premium' => 'Premium',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
