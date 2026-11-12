@extends('layouts.app')

@section('title', 'List of Barangays')

@section('content')
    <div class="mb-3">
        <a href="{{ route('brgys.create') }}" class="btn btn-primary">Add Barangay</a>
    </div>

    @if ($selectedMunicipality === 'all' && $selectedPopulationGroup === 'all')
        <p>Showing all barangays</p>
    @elseif ($selectedMunicipality !== 'all' && $selectedPopulationGroup === 'all')
        <p>Showing barangays in {{ $selectedMunicipalityName }}</p>
    @elseif ($selectedMunicipality === 'all' && $selectedPopulationGroup !== 'all')
        <p>Showing barangays in all municipalities with {{ $selectedPopulationGroup }} population</p>
    @else
        <p>Showing barangays in {{ $selectedMunicipalityName }} with {{ $selectedPopulationGroup }} population</p>
    @endif

    <nav aria-label="Filter by municipality">
        <a href="{{ route('brgys.index', ['municipality' => 'all', 'population_group' => $selectedPopulationGroup]) }}">All municipalities</a>
        @foreach ($municipalities as $municipality)
            | <a href="{{ route('brgys.index', ['municipality' => $municipality['id'], 'population_group' => $selectedPopulationGroup]) }}">{{ $municipality['name'] }}</a>
        @endforeach
</nav>

    <nav aria-label="Filter by population group" class="mb-3">
        <a href="{{ route('brgys.index', ['municipality' => $selectedMunicipality, 'population_group' => 'all']) }}">All groups</a> |
        <a href="{{ route('brgys.index', ['municipality' => $selectedMunicipality, 'population_group' => 'high']) }}">High population</a> |
        <a href="{{ route('brgys.index', ['municipality' => $selectedMunicipality, 'population_group' => 'lower']) }}">Lower population</a> |
        <a href="{{ route('brgys.index', ['municipality' => $selectedMunicipality, 'population_group' => 'unavailable']) }}">Population unavailable</a>
    </nav>

    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Barangays</th>
                    <th scope="col">Municipality</th>
                    <th scope="col">Population</th>
                    <th scope="col">Population group</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($brgys as $brgy)
                    <tr>
                        <td>{{ $brgy['id'] }}</td>
                        <td><a href="{{ route('brgys.show', $brgy['route_key']) }}">{{ $brgy['name'] }}</a></td>
                        <td>{{ $brgy['municipality'] }}</td>
                        <td>{{ $brgy['population'] ?? 'Not available' }}</td>
                        <td>
                            @if ($brgy['population'] === null)
                                Population unavailable
                            @elseif ($brgy['population'] >= 2500)
                                High population
                            @else
                                Lower population
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('brgys.destroy', $brgy['route_key']) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No barangays are available right now.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
