<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Forms\Components\ViewField;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Lokasyon Bilgileri')
                    ->description('Çalışma lokasyonunun temel bilgilerini girin')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        TextInput::make('name')
                            ->label('Lokasyon Adı')
                            ->required()
                            ->placeholder('Örn: İstanbul Ofis, Ankara Şube')
                            ->maxLength(255),
                    ]),
                    
                Section::make('GPS Koordinatları')
                    ->description('Harita ile lokasyon seçin veya manuel koordinat girin')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        ViewField::make('location_map')
                            ->label('Lokasyon Haritası')
                            ->view('forms.components.location-map-picker')
                            ->dehydrated(false),
                            
                        Grid::make(2)
                            ->schema([
                                TextInput::make('lat_allowed')
                                    ->label('Enlem (Latitude)')
                                    ->required()
                                    ->numeric()
                                    ->step(0.00000001)
                                    ->placeholder('41.0082376')
                                    ->helperText('Haritadan otomatik doldurulur')
                                    ->reactive(),
                                    
                                TextInput::make('lng_allowed')
                                    ->label('Boylam (Longitude)')
                                    ->required()
                                    ->numeric()
                                    ->step(0.00000001)
                                    ->placeholder('28.9783589')
                                    ->helperText('Haritadan otomatik doldurulur')
                                    ->reactive(),
                            ]),
                            
                        TextInput::make('radius_allowed')
                            ->label('Çalışma Yarıçapı (metre)')
                            ->required()
                            ->numeric()
                            ->suffix('metre')
                            ->placeholder('100')
                            ->helperText('Çalışanların check-in yapabileceği maksimum mesafe')
                            ->minValue(1)
                            ->maxValue(5000)
                            ->reactive()
                            ->extraInputAttributes([
                                'x-on:input' => 'if ($el.value && window.locationMapPickerInstance) { window.locationMapPickerInstance.updateRadius($el.value); }'
                            ]),
                    ]),
            ]);
    }
}
