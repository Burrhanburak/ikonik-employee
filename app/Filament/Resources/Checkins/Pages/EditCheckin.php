<?php

namespace App\Filament\Resources\Checkins\Pages;

use App\Filament\Resources\Checkins\CheckinResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\EditRecord;

class EditCheckin extends EditRecord
{
    protected static string $resource = CheckinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Sil')
                ->requiresConfirmation()
                ->modalHeading('Check-in Sil')
                ->modalDescription('Bu check-in silinecek. Emin misiniz?')
                ->modalSubmitActionLabel('Evet, Sil'),
        ];
    }
}
