<?php

namespace App\Filament\Resources\Checkins;

use BackedEnum;
use App\Filament\Resources\Checkins\Pages\CreateCheckin;
use App\Filament\Resources\Checkins\Pages\EditCheckin;
use App\Filament\Resources\Checkins\Pages\ListCheckins;
use App\Filament\Resources\Checkins\Schemas\CheckinForm;
use App\Filament\Resources\Checkins\Tables\CheckinsTable;
use App\Models\Checkin;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CheckinResource extends Resource
{
    protected static ?string $model = Checkin::class;
    
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-clock';
    
    protected static ?string $navigationLabel = 'Check-in Kayitlari';
    
    protected static ?int $navigationSort = 3;
    
    protected static ?string $modelLabel = 'Check-in';
    
    protected static ?string $pluralModelLabel = 'Check-in Kayitlari';

    public static function form(Schema $schema): Schema
    {
        return CheckinForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CheckinsTable::configure($table);
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
            'index' => ListCheckins::route('/'),
            'create' => CreateCheckin::route('/create'),
            'edit' => EditCheckin::route('/{record}/edit'),
        ];
    }
}
