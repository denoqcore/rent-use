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
use Illuminate\Support\Facades\DB;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;

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
                    ->label('Title')
                    ->searchable()
                    ->limit(35)
                    ->tooltip(fn ($record) => $record->title),

                TextColumn::make('user.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),

                TextColumn::make('city.name')
                    ->label('City')
                    ->searchable(),

                TextColumn::make('price_per_day')
                    ->label('Price/day')
                    ->formatStateUsing(fn ($state, $record) => $state ? $state . ' ' . $record->currency : '—')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active'   => 'success',
                        'paused'   => 'warning',
                        'archived' => 'danger',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active'   => 'Active',
                        'paused'   => 'Pause',
                        'archived' => 'Archived',
                        default    => $state,
                    }),

                IconColumn::make('delivery_available')
                    ->label('Delivery')
                    ->boolean(),

                IconColumn::make('is_boosted')
                    ->label('Boost')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('boosted_until')
                    ->label('Boost until')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d.m.Y')
                    ->sortable(),
            ])
                ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active'   => 'Active',
                        'paused'   => 'Pause',
                        'archived' => 'Archived',
                    ]),

                Filter::make('boosted_active')
                    ->label('Currently boosted')
                    ->query(fn ($query) => $query->where('is_boosted', true)->where('boosted_until', '>', now())),
            ])
            ->recordActions([
                ViewAction::make()->label(''),
                EditAction::make()->label(''),

                Action::make('activate')
                    ->label('')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->tooltip('Activate')
                    ->visible(fn ($record) => $record->status !== 'active' && $record->status !== 'archived')
                    ->action(fn ($record) => $record->update(['status' => 'active']))
                    ->requiresConfirmation(false),

                Action::make('pause')
                    ->label('')
                    ->icon('heroicon-o-pause-circle')
                    ->color('warning')
                    ->tooltip('Pause')
                    ->visible(fn ($record) => $record->status === 'active')
                    ->action(fn ($record) => $record->update(['status' => 'paused'])),

                Action::make('archive')
                    ->label('')
                    ->icon('heroicon-o-archive-box')
                    ->color('danger')
                    ->tooltip('Archive')
                    ->visible(fn ($record) => $record->status === 'active' || $record->status === 'paused')
                    ->action(fn ($record) => $record->update(['status' => 'archived']))
                    ->requiresConfirmation(),

                Action::make('restore')
                    ->label('')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->tooltip('Restore')
                    ->visible(fn ($record) => $record->status === 'archived')
                    ->action(fn ($record) => $record->update(['status' => 'active'])),

                Action::make('delete'),
                RestoreAction::make(),
                ForceDeleteAction::make()
                    ->label('')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->tooltip('Delete')
                    ->action(fn ($record) => $record->delete())
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Delete selected'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
