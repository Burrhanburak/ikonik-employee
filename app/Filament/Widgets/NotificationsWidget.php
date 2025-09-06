<?php

namespace App\Filament\Widgets;

use App\Models\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class NotificationsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        $employee = Auth::guard('employee')->user();
        
        if (!$employee) {
            return $table->query(Notification::query()->whereRaw('1 = 0'));
        }

        return $table
            ->query(
                $employee->notifications()
                    ->getQuery()
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Başlık')
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('message')
                    ->label('Mesaj')
                    ->limit(50),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                    
                Tables\Columns\IconColumn::make('is_read')
                    ->label('Okundu')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('warning'),
            ])
            ->heading('Bildirimler')
            ->description('Son bildirimleriniz')
            ->striped()
            ->paginated(false);
    }
}