@extends('layouts.app')

@section('title', 'Add Barangay')

@section('content')
    <h2>Add Barangay</h2>

    <form method="POST" action="{{ route('brgys.store') }}">
        @csrf

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
                    <option value="{{ $municipality->name }}" @selected(old('municipality') === $municipality->name)>{{ $municipality->name }}</option>
                @endforeach
            </select>
            @error('municipality')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="population">Population</label>
            <input class="form-control @error('population') is-invalid @enderror" type="number" id="population" name="population" min="0" value="{{ old('population') }}" required>
            @error('population')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="household">Households</label>
            <input class="form-control @error('household') is-invalid @enderror" type="number" id="household" name="household" min="0" value="{{ old('household') }}">
            @error('household')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="has_health_center" value="0">
            <input class="form-check-input @error('has_health_center') is-invalid @enderror" type="checkbox" id="has_health_center" name="has_health_center" value="1" @checked(old('has_health_center'))>
            <label class="form-check-label" for="has_health_center">Has health center</label>
            @error('has_health_center')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary" type="submit">Save barangay</button>
        <a class="btn btn-secondary" href="{{ route('brgys.index') }}">Cancel</a>
    </form>
@endsection
