<?php

namespace App\Filament\Employee\Resources\Checkins;

use App\Filament\Employee\Resources\Checkins\Pages\CreateCheckin;
use App\Filament\Employee\Resources\Checkins\Pages\EditCheckin;
use App\Filament\Employee\Resources\Checkins\Pages\ListCheckins;
use App\Filament\Employee\Resources\Checkins\Schemas\CheckinForm;
use App\Filament\Employee\Resources\Checkins\Tables\CheckinsTable;
use App\Models\Checkin;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class CheckinResource extends Resource
{
    protected static ?string $model = Checkin::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clock';
    
    protected static ?string $navigationLabel = 'Check-in Gecmisim';
    
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return CheckinForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CheckinsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $employee = Auth::guard('employee')->user();
        
        return parent::getEloquentQuery()
            ->where('employee_id', $employee?->id);
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
            // Create sayfasını kaldırdık - check-in sayfası kullanılacak
        ];
    }
}
