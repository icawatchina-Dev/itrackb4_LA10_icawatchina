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
@endsection