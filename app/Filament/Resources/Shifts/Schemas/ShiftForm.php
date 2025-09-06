<?php

namespace App\Filament\Resources\Shifts\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\DatePicker;

class ShiftForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Vardiya Bilgileri')
                    ->description('Vardiya detaylarını girin')
                    ->icon('heroicon-o-calendar-days')
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
                    
                Section::make('Zaman Bilgileri')
                    ->description('Vardiya tarihi ve mesai saatleri')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        DatePicker::make('work_date')
                            ->label('Vardiya Tarihi')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->default(today())
                            ->columnSpanFull(),
                            
                        Grid::make(2)
                            ->schema([
                                TimePicker::make('start_time')
                                    ->label('Başlangıç Saati')
                                    ->required()
                                    ->native(false)
                                    ->seconds(false)
                                    ->displayFormat('H:i')
                                    ->placeholder('09:00'),
                                    
                                TimePicker::make('end_time')
                                    ->label('Bitiş Saati')
                                    ->nullable()
                                    ->native(false)
                                    ->seconds(false)
                                    ->displayFormat('H:i')
                                    ->placeholder('18:00')
                                    ->after('start_time'),
                            ]),
                            
                        Select::make('status')
                            ->label('Durum')
                            ->options([
                                'pending' => 'Beklemede',
                                'active' => 'Aktif',
                                'completed' => 'Tamamlandı',
                                'missed' => 'Kaçırıldı',
                            ])
                            ->default('pending')
                            ->required(),
                    ]),
            ]);
    }
}
