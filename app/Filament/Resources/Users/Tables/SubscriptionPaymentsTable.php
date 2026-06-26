<?php

namespace App\Filament\Resources\Subscriptions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('plan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'premium' => 'success',
                        'pro'     => 'info',
                        'vip'     => 'warning',
                        'starter' => 'gray',
                        default   => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active'    => 'success',
                        'cancelled' => 'danger',
                        'expired'   => 'gray',
                        default     => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('price')
                    ->money()
                    ->sortable(),

                TextColumn::make('starts_at')
                    ->dateTime('d.m.Y')
                    ->sortable(),

                TextColumn::make('ends_at')
                    ->dateTime('d.m.Y')
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('payment_provider')
                    ->label('Provider')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('plan')
                    ->options([
                        'starter' => 'Starter',
                        'vip'     => 'VIP',
                        'pro'     => 'Pro',
                        'premium' => 'Premium',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'active'    => 'Active',
                        'cancelled' => 'Cancelled',
                        'expired'   => 'Expired',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()->label(''),
                EditAction::make()->label(''),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('starts_at', 'desc');
    }
}
