<?php

namespace Database\Seeders;

use App\Models\Municipality;
use Illuminate\Database\Seeder;
use JsonException;
use RuntimeException;

class MunicipalitySeeder extends Seeder
{
    /**
     * @throws JsonException
     */
    public function run(): void
    {
        $path = storage_path('app/private/barangays.json');

        if (! is_file($path)) {
            throw new RuntimeException("Barangay data file not found: {$path}");
        }

        $barangays = json_decode(
            file_get_contents($path),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $municipalityNames = collect($barangays)
            ->pluck('municipality')
            ->filter(fn ($name) => is_string($name) && $name !== '')
            ->unique()
            ->values();

        foreach ($municipalityNames as $name) {
            Municipality::firstOrCreate(['name' => $name]);
        }
    }
}
