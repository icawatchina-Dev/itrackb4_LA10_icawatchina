<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\User;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        $a=Barangay::create(['name'=>'Virac']);
        $b=Barangay::create(['name'=>'Calatagan Proper']);
        $c=Barangay::create(['name'=>'Calatagan Tibang']);
        
        Barangay::create([
            'brgy_name' => 'Barangay 1',
            'city' => 'City A',
            'population' => 3000,
            'user_id' => $a->id,
        ]);
        Barangay::create([
            'brgy_name' => 'Barangay 2',
            'city' => 'City A',
            'population' => 2000,
            'user_id' => $a->id,
        ]);
        Barangay::create([
            'brgy_name' => 'Barangay 3',
            'city' => 'City B',
            'population' => 1500,
            'user_id' => $b->id,
        ]);
        Barangay::create([
            'brgy_name' => 'Barangay 4',
            'city' => 'City B',
            'population' => 4000,
            'user_id' => $b->id,
        ]);
        Barangay::create([
            'brgy_name' => 'Barangay 5',
            'city' => 'City C',
            'population' => 1000,
            'user_id' => $c->id,
        ]);
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        $this->call([
            BarangaySeeder::class,
        ]);
    }
}
