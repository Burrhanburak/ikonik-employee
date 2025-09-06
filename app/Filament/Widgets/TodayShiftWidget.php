<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class TodayShiftWidget extends Widget
{
    protected string $view = 'filament.widgets.today-shift-widget';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 2;

    protected function getViewData(): array
    {
        $employee = Auth::guard('employee')->user();
        
        if (!$employee) {
            return ['shift' => null, 'location' => null];
        }

        $todayShift = $employee->todayShifts()->first();
        $location = $todayShift ? $todayShift->location : null;

        return [
            'shift' => $todayShift,
            'location' => $location,
            'employee' => $employee
        ];
    }
}