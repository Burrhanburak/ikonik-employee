<?php

namespace App\Filament\Employee\Resources\Checkins\Pages;

use App\Filament\Employee\Resources\Checkins\CheckinResource;
use App\Filament\Employee\Pages\CheckInPage;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListCheckins extends ListRecords
{
    protected static string $resource = CheckinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('new_checkin')
                ->label('Yeni Check-in')
                ->icon('heroicon-o-camera')
                ->color('primary')
                ->url(CheckInPage::getUrl()),
        ];
    }
}
