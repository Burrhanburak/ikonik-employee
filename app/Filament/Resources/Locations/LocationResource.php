<?php

namespace App\Filament\Resources\Locations;

use BackedEnum;
use App\Filament\Resources\Locations\Pages\CreateLocation;
use App\Filament\Resources\Locations\Pages\EditLocation;
use App\Filament\Resources\Locations\Pages\ListLocations;
use App\Filament\Resources\Locations\Schemas\LocationForm;
use App\Filament\Resources\Locations\Tables\LocationsTable;
use App\Models\Location;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;
    
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-map-pin';
    
    protected static ?string $navigationLabel = 'Lokasyon Ekle';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $modelLabel = 'Lokasyon Ekle';
    
    protected static ?string $pluralModelLabel = 'Lokasyon Ekle';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return LocationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LocationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLocations::route('/'),
            'create' => CreateLocation::route('/create'),
            'edit' => EditLocation::route('/{record}/edit'),
        ];
    }
}
