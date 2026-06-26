<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
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
                    ->required()
                    ->disabled(fn ($record) => $record?->id === auth()->id())
                    ->dehydrated(),

                TextInput::make('name')
                    ->required(),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),

                TextInput::make('phone')
                    ->tel(),

                FileUpload::make('avatar')
                    ->label('Avatar')
                    ->image()
                    ->directory('avatars')
                    ->disk('public'),

                TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn ($state) => filled($state))
                    ->revealable(),

                DateTimePicker::make('email_verified_at')
                    ->label('Email verified at'),

                Section::make('Subscription')
                    ->columns(3)
                    ->visibleOn('edit')
                    ->schema([
                        Placeholder::make('plan_info')
                            ->label('Plan')
                            ->content(fn ($record) => $record?->planLabel() ?? '—'),

                        Placeholder::make('plan_expires_at_info')
                            ->label('Expires at')
                            ->content(fn ($record) => $record?->plan_expires_at?->format('d.m.Y H:i') ?? '—'),

                        Placeholder::make('last_payment_info')
                            ->label('Last payment')
                            ->content(fn ($record) => $record?->subscriptionPayments()->latest()->first()?->status ?? '—'),
                    ]),

                Section::make('Listings')
                    ->columns(4)
                    ->visibleOn('edit')
                    ->schema([
                        Placeholder::make('listings_total')
                            ->label('Total')
                            ->content(fn ($record) => $record?->listings()->count() ?? 0),

                        Placeholder::make('listings_active')
                            ->label('Active')
                            ->content(fn ($record) => $record?->listings()->where('status', 'active')->count() ?? 0),

                        Placeholder::make('listings_paused')
                            ->label('Paused')
                            ->content(fn ($record) => $record?->listings()->where('status', 'paused')->count() ?? 0),

                        Placeholder::make('listings_drafts')
                            ->label('Drafts')
                            ->content(fn ($record) => $record?->listings()->where('status', 'draft')->count() ?? 0),
                    ]),

                Section::make('Activity')
                    ->columns(3)
                    ->visibleOn('edit')
                    ->schema([
                        Placeholder::make('last_seen_info')
                            ->label('Last seen')
                            ->content(fn ($record) => $record?->last_seen_at?->format('d.m.Y H:i') ?? '—'),

                        Placeholder::make('online_status')
                            ->label('Status')
                            ->content(fn ($record) => $record?->is_online ? 'Online' : 'Offline'),

                        Placeholder::make('rating_info')
                            ->label('Rating')
                            ->content(fn ($record) => $record
                                ? $record->averageRating() . ' ★ (' . $record->reviewCount() . ' reviews)'
                                : '—'
                            ),
                    ]),
            ]);
    }
}
