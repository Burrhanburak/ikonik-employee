<?php

namespace App\Filament\Resources\Admins\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class AdminForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Yönetici Bilgileri')
                    ->description('Yönetici hesabının temel bilgilerini girin')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Ad Soyad')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Yöneticinin adını giriniz')
                                    ->columnSpan(1),
                                    
                                TextInput::make('email')
                                    ->label('E-posta Adresi')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->placeholder('ornek@firma.com')
                                    ->columnSpan(1),
                            ]),
                    ]),
                    
                Section::make('Güvenlik Ayarları')
                    ->description('Giriş bilgileri ve güvenlik ayarları')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        TextInput::make('password')
                            ->label('Parola')
                            ->password()
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(fn ($state): bool => filled($state))
                            ->minLength(8)
                            ->placeholder('Güçlü bir parola seçiniz')
                            ->helperText('En az 8 karakter olmalıdır. Düzenleme sırasında boş bırakırsanız mevcut parola korunur.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
