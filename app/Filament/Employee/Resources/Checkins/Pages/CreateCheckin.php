<?php

namespace App\Filament\Employee\Resources\Checkins\Pages;

use App\Filament\Employee\Resources\Checkins\CheckinResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCheckin extends CreateRecord
{
    protected static string $resource = CheckinResource::class;
}
