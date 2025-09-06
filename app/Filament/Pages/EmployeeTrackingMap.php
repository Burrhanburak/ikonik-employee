<?php

namespace App\Filament\Pages;

use App\Models\Location;
use App\Models\Employee;
use App\Models\Checkin;
use Filament\Pages\Page;
use Carbon\Carbon;

class EmployeeTrackingMap extends Page
{
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-globe-europe-africa';
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Çalışan Takip Haritası';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    protected string $view = 'filament.pages.employee-tracking-map';

    public array $locations = [];
    public array $checkins = [];
    public array $stats = [];

    public function mount(): void
    {
        $this->loadMapData();
    }

    public function loadMapData(): void
    {
        $today = Carbon::today();

        // Lokasyonları yükle
        $this->locations = Location::all(['id', 'name', 'lat_allowed', 'lng_allowed', 'radius_allowed'])
            ->map(function ($location) {
                return [
                    'id' => $location->id,
                    'name' => $location->name,
                    'lat' => (float) $location->lat_allowed,
                    'lng' => (float) $location->lng_allowed,
                    'radius' => $location->radius_allowed,
                ];
            })->toArray();

        // Bugün yapılan check-in'leri yükle (her çalışan için en son)
        $this->checkins = Checkin::with(['employee', 'location'])
            ->whereDate('created_at', $today)
            ->get()
            ->groupBy('employee_id')
            ->map(function ($employeeCheckins) {
                return $employeeCheckins->sortByDesc('created_at')->first();
            })
            ->map(function ($checkin) {
                $location = $checkin->location;
                $isInArea = $location ? $location->isWithinAllowedRadius($checkin->latitude, $checkin->longitude) : false;

                return [
                    'id' => $checkin->id,
                    'employee_name' => $checkin->employee->name,
                    'employee_id' => $checkin->employee_id,
                    'employee_email' => $checkin->employee->email,
                    'employee_photo' => $checkin->employee->profile_photo,
                    'selfie_photo' => $checkin->selfie_photo,
                    'lat' => (float) $checkin->latitude,
                    'lng' => (float) $checkin->longitude,
                    'location_name' => $location ? $location->name : 'Bilinmiyor',
                    'location_id' => $checkin->location_id,
                    'time' => $checkin->created_at->diffForHumans(),
                    'time_exact' => $checkin->created_at->format('d.m.Y H:i'),
                    'is_in_area' => $isInArea,
                    'distance_to_center' => $location ? round($location->distanceFrom($checkin->latitude, $checkin->longitude)) : 0,
                    'status' => $isInArea ? 'in_area' : 'out_area',
                ];
            })
            ->values()
            ->toArray();

        // İstatistikleri hesapla
        $this->stats = [
            'total_locations' => count($this->locations),
            'active_employees' => count($this->checkins),
            'employees_in_area' => collect($this->checkins)->where('is_in_area', true)->count(),
            'employees_out_area' => collect($this->checkins)->where('is_in_area', false)->count(),
        ];
    }

    /**
     * Sayfayı yenile - AJAX ile
     */
    public function refresh(): void
    {
        $this->loadMapData();
        
        $this->dispatch('map-data-updated', [
            'locations' => $this->locations,
            'checkins' => $this->checkins,
            'stats' => $this->stats,
        ]);
    }

    public function getTitle(): string
    {
        return 'Çalışan Takip Haritası';
    }
}