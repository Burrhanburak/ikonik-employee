<?php

namespace App\Filament\Resources\Checkins\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ViewField;

class CheckinForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Check-in Bilgileri')
                    ->description('Çalışan check-in detaylarını girin')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('employee_id')
                                    ->label('Çalışan')
                                    ->relationship('employee', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                    
                                Select::make('location_id')
                                    ->label('Lokasyon')
                                    ->relationship('location', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                            ]),
                            
                    ]),
                    
                Section::make('Konum ve Fotoğraf')
                    ->description('GPS koordinatları ve selfie fotoğrafı')
                    ->icon('heroicon-o-camera')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('latitude')
                                    ->label('Enlem (Latitude)')
                                    ->required()
                                    ->numeric()
                                    ->step(0.00000001)
                                    ->placeholder('41.0082376'),
                                    
                                TextInput::make('longitude')
                                    ->label('Boylam (Longitude)')
                                    ->required()
                                    ->numeric()
                                    ->step(0.00000001)
                                    ->placeholder('28.9783589'),
                            ]),
                            
                        ViewField::make('selfie_photo')
                            ->label('Selfie Fotoğrafı')
                            ->view('filament.components.base64-image')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
