@extends('layouts.app')

@section('title', 'Add Barangay')

@section('content')
    <h2>Add Barangay</h2>

    <form method="POST" action="{{ route('brgys.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label" for="id">ID</label>
            <input class="form-control @error('id') is-invalid @enderror" type="number" id="id" name="id" min="1" value="{{ old('id') }}" required>
            @error('id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="name">Name</label>
            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="municipality">Municipality</label>
            <select class="form-select @error('municipality') is-invalid @enderror" id="municipality" name="municipality" required>
                <option value="" disabled @selected(old('municipality') === null)>Choose a municipality</option>
                @foreach ($municipalities as $municipality)
                    <option value="{{ $municipality }}" @selected(old('municipality') === $municipality)>{{ $municipality }}</option>
                @endforeach
            </select>
            @error('municipality')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="population">Population</label>
            <input class="form-control @error('population') is-invalid @enderror" type="number" id="population" name="population" min="0" value="{{ old('population') }}">
            @error('population')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary" type="submit">Save barangay</button>
        <a class="btn btn-secondary" href="{{ route('brgys.index') }}">Cancel</a>
    </form>
@endsection@extends('layouts.app')

@section('title', 'Add New Barangay')

@section('content')
    <div class="container">
        <h1>Add New Barangay</h1>

        <form action="{{ route('brgys.store') }}" method="POST">
            @csrf 

            <div class="mb-3">
                <label for="name" class="form-label">Barangay Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="municipality" class="form-label">Municipality</label>
                <input type="text" class="form-control" id="municipality" name="municipality" required>
            </div>

            <div class="mb-3">
                <label for="population" class="form-label">Population</label>
                <input type="number" class="form-control" id="population" name="population">
            </div>

            <button type="submit" class="btn btn-success">Save Barangay</button>
            <a href="{{ route('brgys.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection