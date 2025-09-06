<?php

namespace App\Filament\Resources\Admins\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AdminsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ad Soyad')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('email')
                    ->label('E-posta Adresi')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('E-posta adresi kopyalandı'),
                    
                TextColumn::make('created_at')
                    ->label('Oluşturulma Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('updated_at')
                    ->label('Güncelleme Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->label('Düzenle')
                    ->icon('heroicon-o-pencil'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Seçili Olanları Sil'),
                ]),
            ])
            ->emptyStateHeading('Henüz yönetici eklenmemiş')
            ->emptyStateDescription('Yönetici eklemek için "Yeni Yönetici" butonuna tıklayın.')
            ->emptyStateIcon('heroicon-o-shield-check');
    }
}
