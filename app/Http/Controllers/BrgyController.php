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
        $selectedHealthCenter = $request->query('has_health_center');
        $search = $request->query('search', '');
        $sort = $request->query('sort', 'brgy_name');

        if (! is_string($selectedMunicipality)) {
            $selectedMunicipality = 'all';
        }

        if (! is_string($selectedPopulationGroup) || $selectedPopulationGroup === '') {
            $selectedPopulationGroup = 'all';
        }

        if (! is_string($selectedHealthCenter)) {
            $selectedHealthCenter = null;
        }

        if (! is_string($search)) {
            $search = '';
        }

        if (! is_string($sort) || ! in_array($sort, ['brgy_name', 'population'], true)) {
            $sort = 'brgy_name';
        }

        $selectedMunicipalityName = $selectedMunicipality;

        $municipalities = Municipality::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (Municipality $municipality) => [
                'id' => $municipality->getKey(),
                'name' => $municipality->name,
            ])
            ->all();

        $query = Barangay::query()
            ->with('municipality');

        if ($selectedMunicipality !== 'all' && $selectedMunicipality !== '') {
            $selectedMunicipalityRecord = collect($municipalities)
                ->firstWhere('id', (int) $selectedMunicipality);
            $selectedMunicipalityName = $selectedMunicipalityRecord['name'] ?? $selectedMunicipality;
            $query->where('city', $selectedMunicipalityName);
        }

        if ($search !== '') {
            $query->where('brgy_name', 'like', "%{$search}%");
        }

        match ($selectedPopulationGroup) {
            'high' => $query->whereNotNull('population')->where('population', '>=', 2500),
            'lower' => $query->whereNotNull('population')->where('population', '<', 2500),
            'unavailable' => $query->whereNull('population'),
            default => null,
        };

        if ($selectedHealthCenter !== null && $selectedHealthCenter !== ''
            && in_array($selectedHealthCenter, ['0', '1'], true)) {
            $query->where('has_health_center', $selectedHealthCenter);
        }

        $brgys = $query
            ->orderBy($sort)
            ->orderBy('brgy_name')
            ->paginate(7)
            ->withQueryString();
        $brgys->through(fn (Barangay $brgy, int $index) => [
            'id' => $brgys->firstItem() + $index,
            'route_key' => $brgy->getRouteKey(),
            'name' => $brgy->brgy_name,
            'municipality' => $brgy->municipality?->name ?? $brgy->city,
            'municipality_id' => $brgy->municipality?->getKey(),
            'population' => $brgy->population,
            'has_health_center' => $brgy->has_health_center,
        ]);

        return view('brgys.index', compact(
            'brgys',
            'municipalities',
            'selectedMunicipality',
            'selectedMunicipalityName',
            'selectedPopulationGroup',
            'selectedHealthCenter',
            'search',
            'sort'
        ));
    }

    public function create()
    {
        $municipalities = Municipality::query()->orderBy('name')->get();

        return view('brgys.create', compact('municipalities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('brgys', 'brgy_name')],
            'municipality' => ['required', Rule::exists('municipalities', 'name')],
            'population' => ['required', 'integer', 'min:0'],
            'household' => ['nullable', 'integer', 'min:0'],
            'has_health_center' => ['required', 'boolean'],
        ]);

        $brgy = Barangay::create([
            'brgy_name' => $validated['name'],
            'city' => $validated['municipality'],
            'population' => $validated['population'],
            'household' => $validated['household'] ?? null,
            'has_health_center' => $validated['has_health_center'],
        ]);

        Log::info('Barangay saved', [
            'name' => $brgy->brgy_name,
            'municipality' => $brgy->city,
        ]);

        return redirect()->route('brgys.index')->with('success', 'Barangay added successfully.');
    }

    public function show(Barangay $brgy)
    {
        $brgy->load('municipality');

        return view('brgys.show', compact('brgy'));
    }

    public function edit(Barangay $brgy)
    {
        $municipalities = Municipality::query()->orderBy('name')->get();

        return view('brgys.edit', compact('brgy', 'municipalities'));
    }

    public function update(Request $request, Barangay $brgy)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brgys', 'brgy_name')->ignore($brgy->getKey(), 'brgy_name'),
            ],
            'municipality' => ['required', Rule::exists('municipalities', 'name')],
            'population' => ['required', 'integer', 'min:0'],
            'household' => ['nullable', 'integer', 'min:0'],
            'has_health_center' => ['required', 'boolean'],
        ]);

        $brgy->update([
            'brgy_name' => $validated['name'],
            'city' => $validated['municipality'],
            'population' => $validated['population'],
            'household' => $validated['household'] ?? null,
            'has_health_center' => $validated['has_health_center'],
        ]);

        return redirect()->route('brgys.index')->with('success', 'Barangay updated successfully.');
    }

    public function destroy(Barangay $brgy)
    {
        abort_if($brgy->municipality->barangays()->count() === 1, 403);

        $brgy->delete();

        return redirect()->route('brgys.index');
    }
}
