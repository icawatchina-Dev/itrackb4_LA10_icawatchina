<?php

namespace App\Http\Controllers;

use App\Models\Barangay;
use App\Models\Municipality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class BrgyController extends Controller
{
    public function index(Request $request)
    {
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

        $brgys = Barangay::query()
            ->with('municipality')
            ->get()
            ->values()
            ->map(fn (Barangay $brgy, int $index) => [
                'id' => $index + 1,
                'route_key' => $brgy->getRouteKey(),
                'name' => $brgy->getAttribute('brgy_name') ?? $brgy->name,
                'municipality' => $brgy->municipality->name ?? null,
                'municipality_id' => $brgy->municipality?->getKey(),
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
        $validated = $request->validate([
            'brgy_name' => ['required', 'string', 'max:255', Rule::unique('brgys', 'brgy_name')],
            'city' => ['required', Rule::exists('municipalities', 'name')],
            'population' => ['nullable', 'integer', 'min:0'],
            'household' => ['nullable', 'integer', 'min:0'],
            'has_health_center' => ['required', 'boolean'],
        ]);

        $brgy = Barangay::create($validated);

        Log::info('Barangay saved', [
            'id' => $brgy->getKey(),
        ]);

        return redirect()->route('brgys.index')->with('success', 'Barangay added successfully.');
    }

    public function show(Barangay $brgy)
    {
        return view('brgys.show', compact('brgy'));
    }

    public function edit(Barangay $brgy)
    {
        $municipalities = Municipality::all();

        return view('brgys.edit', compact('brgy', 'municipalities'));
    }

    public function update(Request $request, Barangay $brgy)
    {
        $validated = $request->validate([
            'brgy_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brgys', 'brgy_name')->ignore($brgy->getKey(), 'brgy_name'),
            ],
            'city' => ['required', Rule::exists('municipalities', 'name')],
            'population' => ['nullable', 'integer', 'min:0'],
            'household' => ['nullable', 'integer', 'min:0'],
            'has_health_center' => ['required', 'boolean'],
        ]);

        $brgy->update($validated);

        return redirect()->route('brgys.index')->with('success', 'Barangay updated successfully.');
    }

    public function destroy(Barangay $brgy)
    {
        abort_if($brgy->municipality->barangays()->count() === 1, 403);

        $brgy->delete();

        return redirect()->route('brgys.index');
    }
}