<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'name' => 'Ahmet Yılmaz',
                'email' => 'ahmet.yilmaz@company.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Ayşe Demir',
                'email' => 'ayse.demir@company.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Mehmet Kaya',
                'email' => 'mehmet.kaya@company.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Fatma Özkan',
                'email' => 'fatma.ozkan@company.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Ali Çelik',
                'email' => 'ali.celik@company.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Zeynep Arslan',
                'email' => 'zeynep.arslan@company.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Emre Doğan',
                'email' => 'emre.dogan@company.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Selin Aydın',
                'email' => 'selin.aydin@company.com',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($employees as $employeeData) {
            $employee = Employee::create($employeeData);
            
            // Her çalışanı rastgele 2-4 lokasyona ata
            $locations = Location::inRandomOrder()->take(rand(2, 4))->get();
            $employee->locations()->attach($locations);
        }
    }
}