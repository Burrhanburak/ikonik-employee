<?php

namespace App\Filament\Resources\Shifts\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkAction;
use Filament\Notifications\Notification;

class ShiftsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
                '2xl' => 4,
            ])
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Çalışan')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('location.name')
                    ->label('Lokasyon')
                    ->sortable()
                    ->searchable()
                    ->color('gray'),
                    
                TextColumn::make('work_date')
                    ->label('Tarih')
                    ->date('d/m/Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar-days'),
                    
                TextColumn::make('start_time')
                    ->label('Başlangıç')
                    ->time('H:i')
                    ->sortable()
                    ->icon('heroicon-o-play')
                    ->color('success'),
                    
                TextColumn::make('end_time')
                    ->label('Bitiş')
                    ->time('H:i')
                    ->sortable()
                    ->icon('heroicon-o-stop')
                    ->color('danger'),
                    
                TextColumn::make('actual_status')
                    ->label('Durum')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        // Check-in durumuna göre gerçek durumu hesapla
                        if (!$record->work_date) {
                            return 'pending'; // Tarih yoksa pending
                        }
                        
                        // O günkü check-in'i bul (shift ile direkt bağlı olmasa bile)
                        $latestCheckin = \App\Models\Checkin::where('employee_id', $record->employee_id)
                            ->whereDate('created_at', $record->work_date)
                            ->latest()
                            ->first();
                            
                        if (!$latestCheckin) {
                            // Check-in yapılmamış
                            if ($record->work_date && $record->work_date->isPast()) {
                                return 'missed'; // Geçmiş tarihte check-in yapılmamış
                            }
                            return 'pending'; // Henüz başlamadı
                        }
                        
                        if ($latestCheckin->status === 'completed') {
                            // Check-in'i bu shift'e bağla
                            if (!$latestCheckin->shift_id) {
                                $latestCheckin->update(['shift_id' => $record->id]);
                            }
                            return 'completed'; // Check-out yapılmış
                        }
                        
                        if ($latestCheckin->status === 'active') {
                            // Check-in'i bu shift'e bağla
                            if (!$latestCheckin->shift_id) {
                                $latestCheckin->update(['shift_id' => $record->id]);
                            }
                            return 'active'; // Check-in yapılmış ama check-out yapılmamış
                        }
                        
                        return $record->status; // Manuel durum
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'active' => 'info', 
                        'completed' => 'success',
                        'missed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Henüz Başlamadı',
                        'active' => 'Mesai Devam Ediyor',
                        'completed' => 'Mesai Bitti',
                        'missed' => 'Gelmedi',
                        default => ucfirst($state),
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Beklemede',
                        'active' => 'Aktif',
                        'completed' => 'Tamamlandı',
                        'missed' => 'Kaçırıldı',
                    ]),
            ])
            ->actions([
                // Durumu otomatik senkronize et
                Action::make('sync_status')
                    ->label('Durumu Güncelle')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->action(function ($record) {
                        // Check-in durumuna göre shift durumunu güncelle
                        if (!$record->work_date) {
                            Notification::make()
                                ->warning()
                                ->title('Hata')
                                ->body('Vardiya tarihi belirtilmemiş.')
                                ->send();
                            return;
                        }
                        
                        // O günkü check-in'i bul (shift ile direkt bağlı olmasa bile)
                        $latestCheckin = \App\Models\Checkin::where('employee_id', $record->employee_id)
                            ->whereDate('created_at', $record->work_date)
                            ->latest()
                            ->first();
                            
                        $newStatus = 'pending';
                        if (!$latestCheckin) {
                            $newStatus = ($record->work_date && $record->work_date->isPast()) ? 'missed' : 'pending';
                        } elseif ($latestCheckin->status === 'completed') {
                            $newStatus = 'completed';
                            // Check-in'i bu shift'e bağla
                            if (!$latestCheckin->shift_id) {
                                $latestCheckin->update(['shift_id' => $record->id]);
                            }
                        } elseif ($latestCheckin->status === 'active') {
                            $newStatus = 'active';
                            // Check-in'i bu shift'e bağla
                            if (!$latestCheckin->shift_id) {
                                $latestCheckin->update(['shift_id' => $record->id]);
                            }
                        }
                        
                        $record->update(['status' => $newStatus]);
                        
                        $statusText = match ($newStatus) {
                            'pending' => 'Henüz Başlamadı',
                            'active' => 'Mesai Devam Ediyor',
                            'completed' => 'Mesai Bitti',
                            'missed' => 'Gelmedi',
                        };
                        
                        Notification::make()
                            ->success()
                            ->title('Durum Güncellendi')
                            ->body("Vardiya durumu: {$statusText}")
                            ->send();
                    }),
                    
                // Manuel işaretleme (sadece otomatik durumla farklıysa)
                Action::make('mark_absent')
                    ->label('Gelmedi İşaretle')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(function ($record) {
                        if (!$record->work_date) {
                            return false; // Tarih yoksa gösterme
                        }
                        
                        // O günkü check-in'i bul (shift ile direkt bağlı olmasa bile)
                        $latestCheckin = \App\Models\Checkin::where('employee_id', $record->employee_id)
                            ->whereDate('created_at', $record->work_date)
                            ->latest()
                            ->first();
                        return !$latestCheckin && !$record->work_date->isFuture();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Çalışan Gelmedi')
                    ->modalDescription('Bu çalışanı gelmedi olarak işaretlemek istediğinizden emin misiniz?')
                    ->modalSubmitActionLabel('Evet, Gelmedi')
                    ->action(function ($record) {
                        $record->update(['status' => 'missed']);
                        
                        Notification::make()
                            ->warning()
                            ->title('Gelmedi İşaretlendi')
                            ->body("#{$record->employee->name} çalışanı gelmedi olarak işaretlendi.")
                            ->send();
                    }),
                
                EditAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Vardiya Düzenle')
                    ->modalDescription('Bu vardiya düzenlenecek. Emin misiniz?')
                    ->modalSubmitActionLabel('Evet, Düzenle')
                    ->label('Düzenle'),
                ViewAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Vardiya Görüntüle')
                    ->modalDescription('Bu vardiya görüntülenecek. Emin misiniz?')
                    ->modalSubmitActionLabel('Evet, Görüntüle')
                    ->label('Görüntüle'),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Vardiya Sil')
                    ->modalDescription('Bu vardiya silinecek. Emin misiniz?')
                    ->modalSubmitActionLabel('Evet, Sil')
                    ->label('Sil')
               ,
                
                ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Seçilenleri Sil'),
                        
                    // Toplu Onaylama
                    BulkAction::make('bulk_approve')
                        ->label('Seçilenleri Onayla')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Toplu Vardiya Onayı')
                        ->modalDescription('Seçili vardiyaları onaylamak istediğinizden emin misiniz?')
                        ->modalSubmitActionLabel('Evet, Hepsini Onayla')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $approvedCount = 0;
                            $records->each(function ($record) use (&$approvedCount) {
                                if ($record->status === 'pending') {
                                    $record->update(['status' => 'active']);
                                    $approvedCount++;
                                }
                            });
                            
                            Notification::make()
                                ->success()
                                ->title('Toplu Onay Tamamlandı')
                                ->body("{$approvedCount} vardiya onaylandı.")
                                ->send();
                        }),
                        
                    // Toplu Tamamlama
                    BulkAction::make('bulk_complete')
                        ->label('Seçilenleri Tamamla')
                        ->icon('heroicon-o-check-badge')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->modalHeading('Toplu Vardiya Tamamlama')
                        ->modalDescription('Seçili vardiyaları tamamlamak istediğinizden emin misiniz?')
                        ->modalSubmitActionLabel('Evet, Hepsini Tamamla')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $completedCount = 0;
                            $records->each(function ($record) use (&$completedCount) {
                                if ($record->status === 'active') {
                                    $record->update(['status' => 'completed']);
                                    $completedCount++;
                                }
                            });
                            
                            Notification::make()
                                ->success()
                                ->title('Toplu Tamamlama Başarılı')
                                ->body("{$completedCount} vardiya tamamlandı.")
                                ->send();
                        }),
                ]),
            ]);
    }
}
