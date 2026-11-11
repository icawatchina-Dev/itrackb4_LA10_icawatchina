<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Municipality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BrgyController extends Controller
{
    public function index(Request $request)
    {
        DB::enableQueryLog();
        DB::flushQueryLog();
        $selectedMunicipality = $request->query('municipality', 'all');
        $selectedPopulationGroup = $request->query('population_group', 'all');
        $municipalities = Municipality::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (Municipality $municipality) => [
                'id' => $municipality->getKey(),
                'name' => $municipality->name,
            ])
            ->all();

        DB::flushQueryLog();

        $brgys = Barangay::query()
            ->with('municipality')
            ->get()
            ->values()
            ->map(fn (Barangay $brgy, int $index) => [
                'id' => $brgy->getKey() ?? $index + 1,
                'name' => $brgy->getAttribute('brgy_name'),
                'municipality' => $brgy->municipality->name,
                'municipality_id' => $brgy->municipality->getKey(),
                'population' => $brgy->getAttribute('population'),
            ])
            ->all();

        $selectedMunicipalityName = $selectedMunicipality;
        if ($selectedMunicipality !== 'all') {
            $selectedMunicipalityRecord = collect($municipalities)
                ->firstWhere('id', (int) $selectedMunicipality);
            $selectedMunicipalityName = $selectedMunicipalityRecord['name'] ?? $selectedMunicipality;

            $brgys = array_values(array_filter(
                $brgys,
                fn (array $brgy) => $selectedMunicipalityRecord
                    ? $brgy['municipality_id'] === $selectedMunicipalityRecord['id']
                    : $brgy['municipality'] === $selectedMunicipality
            ));
        } else {
            $selectedMunicipalityName = 'all';
        }

        if ($selectedPopulationGroup !== 'all') {
            $brgys = array_values(array_filter(
                $brgys,
                function (array $brgy) use ($selectedPopulationGroup): bool {
                    $population = $brgy['population'] ?? null;

                    return match ($selectedPopulationGroup) {
                        'high' => $population !== null && $population >= 2500,
                        'lower' => $population !== null && $population < 2500,
                        'unavailable' => $population === null,
                        default => true,
                    };
                }
            ));
        }

        return view('brgys.index', compact(
            'brgys',
            'municipalities',
            'selectedMunicipality',
            'selectedMunicipalityName',
            'selectedPopulationGroup'
        ));
    }

    public function create()
    {
        $municipalities = Municipality::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('brgys.create', compact('municipalities'));
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
        $validated['population'] = isset($validated['population'])
            ? (int) $validated['population']
            : null;

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
        $path = storage_path('app/private/barangays.json');

        if (! file_exists($path)) {
            return [];
        }

        return json_decode(
            file_get_contents($path),
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
