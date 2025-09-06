<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Beşiktaş Merkez Ofis',
                'lat_allowed' => 41.0422,
                'lng_allowed' => 29.0067,
                'radius_allowed' => 100,
            ],
            [
                'name' => 'Şişli Şube',
                'lat_allowed' => 41.0608,
                'lng_allowed' => 28.9865,
                'radius_allowed' => 150,
            ],
            [
                'name' => 'Kadıköy Ofis',
                'lat_allowed' => 40.9908,
                'lng_allowed' => 29.0266,
                'radius_allowed' => 120,
            ],
            [
                'name' => 'Taksim Plaza',
                'lat_allowed' => 41.0370,
                'lng_allowed' => 28.9857,
                'radius_allowed' => 80,
            ],
            [
                'name' => 'Levent İş Merkezi',
                'lat_allowed' => 41.0814,
                'lng_allowed' => 29.0122,
                'radius_allowed' => 200,
            ],
            [
                'name' => 'Bakırköy Şube',
                'lat_allowed' => 40.9744,
                'lng_allowed' => 28.8739,
                'radius_allowed' => 90,
            ],
            [
                'name' => 'Forum İstanbul - Bayrampaşa Şube',
                'lat_allowed' => 41.0525,
                'lng_allowed' => 28.9005,
                'radius_allowed' => 100,
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}