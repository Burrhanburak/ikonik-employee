<?php

namespace App\Filament\Employee\Resources\Checkins\Pages;

use App\Filament\Employee\Resources\Checkins\CheckinResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCheckin extends EditRecord
{
    protected static string $resource = CheckinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
