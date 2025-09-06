<?php

namespace App\Filament\Resources\Checkins\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

class CheckinsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Çalışan')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('location.name')
                    ->label('Lokasyon')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('created_at')
                    ->label('Check-in')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                    
                TextColumn::make('checkout_time')
                    ->label('Check-out')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Devam ediyor...')
                    ->sortable(),
                    
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'warning',
                        'completed' => 'success', 
                        'missed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'completed' => 'Tamamlandı',
                        'missed' => 'Kaçırıldı',
                        default => ucfirst($state),
                    }),
                    
                TextColumn::make('work_duration')
                    ->label('Çalışma Süresi')
                    ->getStateUsing(function ($record) {
                        if (!$record->checkout_time) {
                            // Aktif mesai için şu anki süreyi hesapla
                            $checkinTime = $record->created_at;
                            $currentTime = now();
                            $diffInMinutes = $checkinTime->diffInMinutes($currentTime);
                            $hours = floor($diffInMinutes / 60);
                            $minutes = $diffInMinutes % 60;
                            return "{$hours}s {$minutes}dk (Aktif)";
                        }
                        
                        $checkinTime = $record->created_at;
                        $checkoutTime = $record->checkout_time;
                        $diffInMinutes = $checkinTime->diffInMinutes($checkoutTime);
                        $hours = floor($diffInMinutes / 60);
                        $minutes = $diffInMinutes % 60;
                        
                        return "{$hours}s {$minutes}dk";
                    }),
                    
                ImageColumn::make('selfie_photo')
                    ->label('Fotoğraf')
                    ->circular()
                   
                    ->width(40)
                    ->height(40),
                    
                TextColumn::make('notes')
                    ->label('Notlar')
                    ->limit(30)
                    ->placeholder('Not yok')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
