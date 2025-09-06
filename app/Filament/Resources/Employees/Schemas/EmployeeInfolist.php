<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Schemas\Schema;

class EmployeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Profil Fotoğrafı ve Temel Bilgiler
                Section::make('Çalışan Profili')
                    ->description('Çalışanın temel profil bilgileri')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make([
                            'md' => 3,
                            'xl' => 4,
                        ])
                        ->schema([
                            ImageEntry::make('profile_photo')
                                ->label('Profil Fotoğrafı')
                                ->circular()
                                ->size(120)
                                ->visibility('private')
                                ->placeholder('👤')
                                ->columnSpan(1),
                                
                            Grid::make(1)
                                ->schema([
                                    TextEntry::make('name')
                                        ->label('Ad Soyad')
                                        ->icon('heroicon-m-user')
                                        ->size('lg')
                                        ->weight('bold')
                                        ->color('primary'),
                                        
                                    TextEntry::make('email')
                                        ->label('E-posta Adresi')
                                        ->icon('heroicon-m-envelope')
                                        ->copyable()
                                        ->copyMessage('E-posta kopyalandı!')
                                        ->color('gray'),
                                ])
                                ->columnSpan([
                                    'md' => 2,
                                    'xl' => 3,
                                ]),
                        ]),
                        
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Oluşturulma Tarihi')
                                    ->icon('heroicon-m-calendar')
                                    ->dateTime('d.m.Y H:i')
                                    ->color('success'),
                                    
                                TextEntry::make('updated_at')
                                    ->label('Son Güncelleme')
                                    ->icon('heroicon-m-clock')
                                    ->dateTime('d.m.Y H:i')
                                    ->since()
                                    ->color('warning'),
                            ]),
                    ]),
                
                // Lokasyon Bilgileri
                Section::make('Yetkili Lokasyonlar')
                    ->description('Çalışanın check-in yapabileceği lokasyonlar')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        TextEntry::make('locations.name')
                            ->label('Lokasyonlar')
                            ->badge()
                            ->separator(',')
                            ->color('info')
                            ->placeholder('Henüz lokasyon atanmamış'),
                    ])
                    ->collapsible(),
                    
                // İstatistikler
                Section::make('Check-in İstatistikleri')
                    ->description('Son aktivite bilgileri')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('checkins_count')
                                    ->label('Toplam Check-in')
                                    ->icon('heroicon-m-check-circle')
                                    ->color('success')
                                    ->placeholder('0'),
                                    
                                TextEntry::make('last_checkin.created_at')
                                    ->label('Son Check-in')
                                    ->icon('heroicon-m-clock')
                                    ->dateTime('d.m.Y H:i')
                                    ->since()
                                    ->color('primary')
                                    ->placeholder('Henüz check-in yok'),
                                    
                                TextEntry::make('last_checkin.location.name')
                                    ->label('Son Lokasyon')
                                    ->icon('heroicon-m-map-pin')
                                    ->color('warning')
                                    ->placeholder('Bilinmiyor'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
