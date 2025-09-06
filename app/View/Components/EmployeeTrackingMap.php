<?php

namespace App\View\Components;

use App\Models\Location;
use App\Models\Checkin;
use Illuminate\View\Component;
use Carbon\Carbon;

class EmployeeTrackingMap extends Component
{
    public array $locations;
    public array $checkins;
    public array $stats;

    public function __construct()
    {
        $this->loadMapData();
    }

    public function loadMapData(): void
    {
        $today = Carbon::today();

        // Load locations
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

        // Load today's check-ins (latest for each employee)
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

        // Calculate stats
        $this->stats = [
            'total_locations' => count($this->locations),
            'active_employees' => count($this->checkins),
            'employees_in_area' => collect($this->checkins)->where('is_in_area', true)->count(),
            'employees_out_area' => collect($this->checkins)->where('is_in_area', false)->count(),
        ];
    }

    public function render()
    {
        return view('components.employee-tracking-map');
    }
}