<?php

namespace App\Filament\Widgets;

use App\Models\Checkin;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class RecentCheckinsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        $employee = Auth::guard('employee')->user();
        
        if (!$employee) {
            return $table->query(Checkin::query()->whereRaw('1 = 0'));
        }

        return $table
            ->query(
                $employee->checkins()
                    ->getQuery()
                    ->with(['location'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarih/Saat')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('location.name')
                    ->label('Lokasyon')
                    ->default('Belirtilmemiş')
                    ->badge(),
                    
                Tables\Columns\TextColumn::make('latitude')
                    ->label('Enlem')
                    ->numeric(2)
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('longitude')
                    ->label('Boylam')
                    ->numeric(2)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->heading('Son Check-in’ler')
            ->description('Son 10 check-in kaydınız')
            ->striped()
            ->paginated(false);
    }
}