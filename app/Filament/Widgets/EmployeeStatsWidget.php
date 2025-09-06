<?php

namespace App\Filament\Widgets;

use App\Models\Checkin;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeStatsWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $employee = Auth::guard('employee')->user();
        
        if (!$employee) {
            return [];
        }

        $todayCheckins = $employee->checkins()
            ->whereDate('created_at', today())
            ->count();

        $monthlyCheckins = $employee->checkins()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalShifts = $employee->shifts()
            ->whereMonth('work_date', now()->month)
            ->whereYear('work_date', now()->year)
            ->count();

        $lastCheckin = $employee->checkins()
            ->latest()
            ->first();

        return [
            Stat::make('Bugünkü Check-in', $todayCheckins)
                ->description('Bugün yapılan check-in sayısı')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Aylık Check-in', $monthlyCheckins)
                ->description('Bu ay yapılan check-in sayısı')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Aylık Vardiya', $totalShifts)
                ->description('Bu ay atanan vardiya sayısı')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Son Check-in', $lastCheckin ? $lastCheckin->created_at->format('H:i') : 'Yok')
                ->description($lastCheckin ? $lastCheckin->created_at->format('d.m.Y') : 'Henüz check-in yapılmamış')
                ->descriptionIcon('heroicon-m-clock')
                ->color($lastCheckin ? 'success' : 'danger'),
        ];
    }
}