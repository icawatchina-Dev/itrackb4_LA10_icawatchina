<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        $testUser = User::factory()->make([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            $testUser->getAttributes()
        );

        $this->call([
            MunicipalitySeeder::class,
            BarangaySeeder::class,
        ]);
    }
}
