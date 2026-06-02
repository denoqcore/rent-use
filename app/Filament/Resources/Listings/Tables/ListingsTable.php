<?php

namespace App\Filament\Resources\Listings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\DB;

class ListingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->width('60px'),

                TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->limit(35)
                    ->tooltip(fn ($record) => $record->title),

                TextColumn::make('user.name')
                    ->label('Владелец')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Категория')
                    ->sortable(),

                TextColumn::make('city')
                    ->label('Город')
                    ->searchable(),

                TextColumn::make('price_per_day')
                    ->label('Цена/день')
                    ->formatStateUsing(fn ($state, $record) => $state ? $state . ' ' . $record->currency : '—')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active'   => 'success',
                        'paused'   => 'warning',
                        'archived' => 'danger',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active'   => 'Активно',
                        'paused'   => 'Пауза',
                        'archived' => 'Архив',
                        default    => $state,
                    }),

                IconColumn::make('delivery_available')
                    ->label('Доставка')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'active'   => 'Активно',
                        'paused'   => 'Пауза',
                        'archived' => 'Архив',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()->label(''),
                EditAction::make()->label(''),
                DeleteAction::make()->label(''),
                Action::make('activate')
                    ->label('')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->tooltip('Активировать')
                    ->visible(fn ($record) => $record->status !== 'active')
                    ->action(fn ($record) => $record->update(['status' => 'active']))
                    ->requiresConfirmation(false),
                Action::make('pause')
                    ->label('')
                    ->icon('heroicon-o-pause-circle')
                    ->color('warning')
                    ->tooltip('Поставить на паузу')
                    ->visible(fn ($record) => $record->status !== 'paused')
                    ->action(fn ($record) => $record->update(['status' => 'paused'])),
                Action::make('archive')
                    ->label('')
                    ->icon('heroicon-o-archive-box')
                    ->color('danger')
                    ->tooltip('Архивировать')
                    ->visible(fn ($record) => $record->status !== 'archived')
                    ->action(fn ($record) => $record->update(['status' => 'archived']))
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Удалить выбранные'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
