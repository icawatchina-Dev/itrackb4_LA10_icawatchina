<?php

namespace Database\Seeders;

use App\Models\Barangay;
use Illuminate\Database\Seeder;

class BarangaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Barangay::create([
            'brgy_name' => 'Proper 1',
            'population' => 2450,
            'city' => 'Calatagan',
            'household' => 612,
            'has_health_center' => true,
        ]);
        Barangay::create([
            'brgy_name' => 'Proper 2',
            'population' => 1800,
            'city' => 'Calatagan',
            'household' => 450,
            'has_health_center' => false,
        ]);
        Barangay::create([
            'brgy_name' => 'Proper 3',
            'population' => 3200,
            'city' => 'Calatagan',
            'household' => 800,
            'has_health_center' => true,
        ]);
        Barangay::create([
            'brgy_name' => 'Proper 4',
            'population' => 1500,
            'city' => 'Calatagan',
            'household' => 400,
            'has_health_center' => false,
        ]);
        Barangay::create([
            'brgy_name' => 'Proper 5',
            'population' => 2800,
            'city' => 'Calatagan',
            'household' => 700,
            'has_health_center' => true,
        ]);
    }
}
