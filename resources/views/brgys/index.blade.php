@extends('layouts.app')

@section('title', 'List of Barangays')

@section('content')
     <div class 'mb-3'>
        <a href="{{ route('brgys.create') }}" class="btn btn-primary">Add Barangay</a>
    </div>
    @if ($selectedMunicipality === 'all' && $selectedPopulationGroup === 'all')
        <p>Showing all barangays</p>
    @elseif ($selectedMunicipality !== 'all' && $selectedPopulationGroup === 'all')
        <p>Showing barangays in {{ $selectedMunicipality }}</p>
    @elseif ($selectedMunicipality === 'all' && $selectedPopulationGroup !== 'all')
        <p>Showing barangays in all municipalities with {{ $selectedPopulationGroup }} population</p>
    @else
        <p>Showing barangays in {{ $selectedMunicipality }} with {{ $selectedPopulationGroup }} population</p>
    @endif

    <a class="btn btn-primary mb-3" href="{{ route('brgys.create') }}">Add barangay</a>

    <nav aria-label="Filter by municipality">
        <a href="{{ route('brgys.index', ['municipality' => 'all', 'population_group' => $selectedPopulationGroup]) }}">All municipalities</a>
        @foreach ($municipalities as $municipality)
            | <a href="{{ route('brgys.index', ['municipality' => $municipality, 'population_group' => $selectedPopulationGroup]) }}">{{ $municipality }}</a>
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
                <th scope="col">#</th>
                <th scope="col">Barangays</th>
                <th scope="col">Municipality</th>
                <th scope="col">Population</th>
                <th scope="col">Population group</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($brgys as $brgy)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><a href="{{ route('brgys.show', $brgy['id']) }}">{{ $brgy['name'] }}</a></td>
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
                </tr>
            @empty
                <tr>
                    <td colspan="5">No barangays are available right now.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    </div>
@endsection
