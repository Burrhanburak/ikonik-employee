<?php

namespace App\Filament\Resources\Shifts\Pages;

use App\Filament\Resources\Shifts\ShiftResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShift extends EditRecord
{
    protected static string $resource = ShiftResource::class;
    
    public function getTitle(): string
    {
        return 'Vardiya Düzenle';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Sil'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Tablodaki ile aynı dinamik durum hesaplaması
        if (!$data['work_date']) {
            $data['status'] = 'pending';
        } else {
            // O günkü check-in'i bul (shift ile direkt bağlı olmasa bile)
            $latestCheckin = \App\Models\Checkin::where('employee_id', $data['employee_id'])
                ->whereDate('created_at', $data['work_date'])
                ->latest()
                ->first();
                
            if (!$latestCheckin) {
                // Check-in yapılmamış
                if ($data['work_date'] && \Carbon\Carbon::parse($data['work_date'])->isPast()) {
                    $data['status'] = 'missed'; // Geçmiş tarihte check-in yapılmamış
                } else {
                    $data['status'] = 'pending'; // Henüz başlamadı
                }
            } else {
                if ($latestCheckin->status === 'completed') {
                    // Check-in'i bu shift'e bağla
                    if (!$latestCheckin->shift_id) {
                        $latestCheckin->update(['shift_id' => $this->record->id]);
                    }
                    $data['status'] = 'completed'; // Check-out yapılmış
                } elseif ($latestCheckin->status === 'active') {
                    // Check-in'i bu shift'e bağla
                    if (!$latestCheckin->shift_id) {
                        $latestCheckin->update(['shift_id' => $this->record->id]);
                    }
                    $data['status'] = 'active'; // Check-in yapılmış ama check-out yapılmamış
                } else {
                    $data['status'] = $data['status']; // Manuel durum
                }
            }
        }
        
        return $data;
    }

}
