@extends('layouts.app')

@section('title', 'List of Barangays')

@section('content')
    <div class="mb-3">
        @auth
            <a href="{{ route('brgys.create') }}" class="btn btn-primary">+ Add Barangay</a>
        @endauth
    </div>

    <form method="GET" action="{{ route('brgys.index') }}" class="row g-2 align-items-end mb-3">
        <div class="col-md-3">
            <label for="search" class="form-label">Search barangays</label>
            <input class="form-control" type="text" id="search" name="search" value="{{ $search }}">
        </div>
        <div class="col-md-3">
            <label for="municipality" class="form-label">Municipality</label>
            <select class="form-select" id="municipality" name="municipality">
                <option value="all" @selected($selectedMunicipality === 'all' || $selectedMunicipality === '')>All municipalities</option>
                @foreach ($municipalities as $municipality)
                    <option value="{{ $municipality['id'] }}" @selected($selectedMunicipality == $municipality['id'])>
                        {{ $municipality['name'] }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="has_health_center" class="form-label">Health center</label>
            <select class="form-select" id="has_health_center" name="has_health_center">
                <option value="" @selected($selectedHealthCenter === null || $selectedHealthCenter === '')>Any</option>
                <option value="1" @selected($selectedHealthCenter === '1')>Has health center</option>
                <option value="0" @selected($selectedHealthCenter === '0')>No health center</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="population_group" class="form-label">Population group</label>
            <select class="form-select" id="population_group" name="population_group">
                <option value="all" @selected($selectedPopulationGroup === 'all')>Any group</option>
                <option value="high" @selected($selectedPopulationGroup === 'high')>High population</option>
                <option value="lower" @selected($selectedPopulationGroup === 'lower')>Lower population</option>
                <option value="unavailable" @selected($selectedPopulationGroup === 'unavailable')>Population unavailable</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="sort" class="form-label">Sort by</label>
            <select class="form-select" id="sort" name="sort">
                <option value="brgy_name" @selected($sort === 'brgy_name')>Barangay name</option>
                <option value="population" @selected($sort === 'population')>Population</option>
            </select>
        </div>
        <div class="col-md-9 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Apply</button>
            <a href="{{ route('brgys.index') }}" class="btn btn-outline-secondary">Clear</a>
        </div>
    </form>

    <p>Showing {{ $brgys->total() }} matching barangays</p>

    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Barangays</th>
                    <th scope="col">Municipality</th>
                    <th scope="col">Population</th>
                    <th scope="col">Population group</th>
                    <th scope="col">Health center</th>
                    @auth
                        <th scope="col">Actions</th>
                    @endauth
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
                        <td>{{ $brgy['has_health_center'] ? 'Yes' : 'No' }}</td>
                        @auth
                            <td>
                                <a href="{{ route('brgys.edit', $brgy['route_key']) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('brgys.destroy', $brgy['route_key']) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        @endauth
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->check() ? 7 : 6 }}">No barangays are available right now.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $brgys->links() }}
@endsection
