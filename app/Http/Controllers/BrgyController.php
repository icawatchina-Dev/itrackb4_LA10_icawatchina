<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BrgyController extends Controller
{
    public function index(Request $request)
    {
        $allBrgys = Barangay::all()->values()->map(
            fn (Barangay $brgy, int $index) => [
                'id' => $brgy->getKey() ?? $index + 1,
                'name' => $brgy->getAttribute('brgy_name'),
                'municipality' => $brgy->getAttribute('city'),
                'population' => $brgy->getAttribute('population'),
            ]
        );
        $municipalities = $allBrgys->pluck('municipality')->unique()->values();
        $municipality = $request->query('municipality', 'all');
        $populationGroup = $request->query('population_group', 'all');

        $brgys = $allBrgys;

        if ($municipality !== 'all') {
            $brgys = $brgys->filter(
                fn (array $brgy) => strcasecmp($brgy['municipality'], $municipality) === 0
            );
        }

        if ($populationGroup !== 'all') {
            $brgys = $brgys->filter(function (array $brgy) use ($populationGroup) {
                $population = $brgy['population'];

                return match ($populationGroup) {
                    'high' => $population !== null && $population >= 2500,
                    'lower' => $population !== null && $population < 2500,
                    'unavailable' => $population === null,
                    default => false,
                };
            });
        }

        return view('brgys.index', [
            'brgys' => $brgys->values(),
            'municipalities' => $municipalities,
            'selectedMunicipality' => $municipality,
            'selectedPopulationGroup' => $populationGroup,
        ]);
    }

    public function create()
    {
        $municipalities = collect($this->barangays())
            ->pluck('municipality')
            ->unique()
            ->values();

        return view('brgys.create', ['municipalities' => $municipalities]);
    }

    public function store(Request $request)
    {
        $barangays = $this->barangays();
        $municipalities = array_values(array_unique(array_column($barangays, 'municipality')));

        $validated = $request->validate([
            'id' => ['required', 'integer', 'min:1', Rule::notIn(array_column($barangays, 'id'))],
            'name' => ['required', 'string', 'max:255'],
            'municipality' => ['required', Rule::in($municipalities)],
            'population' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['id'] = (int) $validated['id'];
        $population = $validated['population'] ?? null;
        $validated['population'] = $population === null
            ? null
            : (int) $population;

        $barangays[] = $validated;
        $this->saveBarangays($barangays);

        return redirect()->route('brgys.index')->with('success', 'Barangay added successfully.');
    }

    public function show(string $id)
    {
        $brgy = collect($this->barangays())->firstWhere('id', (int) $id);

        abort_unless($brgy, 404);

        return view('brgys.show', ['brgy' => $brgy]);
    }

    private function barangays(): array
    {
        return json_decode(
            file_get_contents(storage_path('app/private/barangays.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    private function saveBarangays(array $barangays): void
    {
        file_put_contents(
            storage_path('app/private/barangays.json'),
            json_encode($barangays, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR)
        );
    }
}
