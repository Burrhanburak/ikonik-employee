<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Kişisel Bilgiler')
                    ->description('Çalışanın temel bilgilerini girin')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Ad Soyad')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ahmet Yılmaz'),
                                    
                                TextInput::make('email')
                                    ->label('E-posta Adresi')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('ahmet@example.com'),
                            ]),
                            
                        TextInput::make('password')
                            ->label('Şifre')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(fn ($state): bool => filled($state))
                            ->minLength(8)
                            ->helperText('Minimum 8 karakter olmalıdır'),
                    ]),
                    
                Section::make('Profil Fotoğrafı')
                    ->description('Çalışanın sistem kayıtlı profil fotoğrafı')
                    ->icon('heroicon-o-camera')
                    ->schema([
                        FileUpload::make('profile_photo')
                            ->label('Profil Fotoğrafı')
                            ->image()
                            ->imageEditor()
                            ->directory('profiles')
                            ->visibility('private')
                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                            ->maxSize(2048) // 2MB
                            ->nullable(),
                    ]),
                    
                Section::make('Çalışma Lokasyonları')
                    ->description('Çalışanın yetkili olduğu lokasyonları seçin')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        Select::make('locations')
                            ->label('Yetkili Lokasyonlar')
                            ->relationship('locations', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->helperText('Çalışanın check-in yapabileceği lokasyonları seçin'),
                    ]),
            ]);
    }
}
