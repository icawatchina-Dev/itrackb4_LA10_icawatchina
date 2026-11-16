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
        $barangays = [
            ['brgy_name' => 'San Isidro', 'population' => 2450, 'city' => 'Virac', 'household' => 612, 'has_health_center' => true],
            ['brgy_name' => 'Rawis', 'population' => 1800, 'city' => 'Virac', 'household' => 450, 'has_health_center' => false],
            ['brgy_name' => 'Calatagan', 'population' => 3200, 'city' => 'Virac', 'household' => 800, 'has_health_center' => true],
            ['brgy_name' => 'San Jose', 'population' => 1500, 'city' => 'Virac', 'household' => 400, 'has_health_center' => false],
            ['brgy_name' => 'Salvacion', 'population' => 2800, 'city' => 'Virac', 'household' => 700, 'has_health_center' => true],
            ['brgy_name' => 'San Pedro', 'population' => 2200, 'city' => 'Virac', 'household' => 550, 'has_health_center' => false],
            ['brgy_name' => 'Gogon', 'population' => 3000, 'city' => 'Virac', 'household' => 750, 'has_health_center' => true],
            ['brgy_name' => 'Francia', 'population' => 1700, 'city' => 'Virac', 'household' => 425, 'has_health_center' => false],
            ['brgy_name' => 'Codon', 'population' => 2600, 'city' => 'San Andres', 'household' => 650, 'has_health_center' => true],
            ['brgy_name' => 'San Andres Proper', 'population' => 2000, 'city' => 'San Andres', 'household' => 500, 'has_health_center' => false],
            ['brgy_name' => 'Carangag', 'population' => 2400, 'city' => 'San Andres', 'household' => 600, 'has_health_center' => true],
            ['brgy_name' => 'Barihay', 'population' => 1900, 'city' => 'San Andres', 'household' => 475, 'has_health_center' => false],
            ['brgy_name' => 'Cabungahan', 'population' => 2800, 'city' => 'San Andres', 'household' => 700, 'has_health_center' => true],
            ['brgy_name' => 'Manambrag', 'population' => 2100, 'city' => 'San Andres', 'household' => 525, 'has_health_center' => false],
            ['brgy_name' => 'San Roque', 'population' => 2500, 'city' => 'San Andres', 'household' => 625, 'has_health_center' => true],
            ['brgy_name' => 'Tominawog', 'population' => 2300, 'city' => 'San Andres', 'household' => 575, 'has_health_center' => false],
            ['brgy_name' => 'Bote', 'population' => 2700, 'city' => 'Bato', 'household' => 675, 'has_health_center' => true],
            ['brgy_name' => 'Sipi', 'population' => 1600, 'city' => 'Bato', 'household' => 400, 'has_health_center' => false],
            ['brgy_name' => 'Buenavista', 'population' => 2900, 'city' => 'Bato', 'household' => 725, 'has_health_center' => true],
            ['brgy_name' => 'Carorian', 'population' => 2000, 'city' => 'Bato', 'household' => 500, 'has_health_center' => false],
            ['brgy_name' => 'Cagraray', 'population' => 3100, 'city' => 'Bato', 'household' => 775, 'has_health_center' => true],
            ['brgy_name' => 'Guinobatan', 'population' => 1850, 'city' => 'Bato', 'household' => 462, 'has_health_center' => false],
            ['brgy_name' => 'Ilawod', 'population' => 2550, 'city' => 'Bato', 'household' => 637, 'has_health_center' => true],
            ['brgy_name' => 'Bote East', 'population' => 2150, 'city' => 'Bato', 'household' => 537, 'has_health_center' => false],
        ];

        foreach ($barangays as $barangay) {
            Barangay::updateOrCreate(
                ['brgy_name' => $barangay['brgy_name']],
                $barangay
            );
        }
    }
}
