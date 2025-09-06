<?php

namespace Database\Seeders;

use App\Models\Checkin;
use App\Models\Employee;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CheckinSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::with('locations')->get();
        
        foreach ($employees as $employee) {
            if ($employee->locations->count() > 0) {
                // Her çalışan için bugün bir check-in oluştur
                $location = $employee->locations->random();
                
                // Bazı çalışanlar alan içinde, bazıları dışında olsun
                $isInArea = rand(1, 10) > 3; // %70 ihtimalle alan içinde
                
                if ($isInArea) {
                    // Alan içinde rastgele bir nokta
                    $distance = rand(10, $location->radius_allowed - 10);
                    $angle = rand(0, 360) * (M_PI / 180);
                    
                    $lat = $location->lat_allowed + ($distance / 111000) * cos($angle);
                    $lng = $location->lng_allowed + ($distance / (111000 * cos($location->lat_allowed * M_PI / 180))) * sin($angle);
                } else {
                    // Alan dışında rastgele bir nokta
                    $distance = rand($location->radius_allowed + 20, $location->radius_allowed + 200);
                    $angle = rand(0, 360) * (M_PI / 180);
                    
                    $lat = $location->lat_allowed + ($distance / 111000) * cos($angle);
                    $lng = $location->lng_allowed + ($distance / (111000 * cos($location->lat_allowed * M_PI / 180))) * sin($angle);
                }
                
                Checkin::create([
                    'employee_id' => $employee->id,
                    'location_id' => $location->id,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'selfie_photo' => null, // Test için şimdilik null
                    'created_at' => Carbon::today()->addHours(rand(8, 16))->addMinutes(rand(0, 59)),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}