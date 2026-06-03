<?php

namespace App\Filament\Resources\Listings;

use App\Filament\Resources\Listings\Pages\CreateListing;
use App\Filament\Resources\Listings\Pages\EditListing;
use App\Filament\Resources\Listings\Pages\ListListings;
use App\Filament\Resources\Listings\Pages\ViewListing;
use App\Filament\Resources\Listings\Schemas\ListingForm;
use App\Filament\Resources\Listings\Schemas\ListingInfolist;
use App\Filament\Resources\Listings\Tables\ListingsTable;
use App\Models\Listing;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ListingResource extends Resource
{
    protected static ?string $model = Listing::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;
    protected static ?string $navigationLabel = 'Listings';
    protected static ?string $modelLabel = 'Listing';
    protected static ?string $pluralModelLabel = 'Listings';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ListingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ListingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ListingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListListings::route('/'),
            'create' => CreateListing::route('/create'),
            'view'   => ViewListing::route('/{record}'),
            'edit'   => EditListing::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 'paused')->count() > 0 ? 'warning' : 'success';
    }
}
