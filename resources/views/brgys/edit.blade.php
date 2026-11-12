@extends('layouts.app')

@section('title', 'Edit Barangay')

@section('content')
    <h2>Edit Barangay</h2>

    <form method="POST" action="{{ route('brgys.update', $brgy) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label" for="brgy_name">Name</label>
            <input class="form-control @error('brgy_name') is-invalid @enderror" type="text" id="brgy_name" name="brgy_name" value="{{ old('brgy_name', $brgy->brgy_name) }}" required>
            @error('brgy_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="city">Municipality</label>
            <select class="form-select @error('city') is-invalid @enderror" id="city" name="city" required>
                @foreach ($municipalities as $municipality)
                    <option value="{{ $municipality->name }}" @selected(old('city', $brgy->city) == $municipality->name)>{{ $municipality->name }}</option>
                @endforeach
            </select>
            @error('city')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="population">Population</label>
            <input class="form-control @error('population') is-invalid @enderror" type="number" id="population" name="population" min="0" value="{{ old('population', $brgy->population) }}">
            @error('population')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label" for="household">Households</label>
            <input class="form-control @error('household') is-invalid @enderror" type="number" id="household" name="household" min="0" value="{{ old('household', $brgy->household) }}">
            @error('household')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="has_health_center" value="0">
            <input class="form-check-input @error('has_health_center') is-invalid @enderror" type="checkbox" id="has_health_center" name="has_health_center" value="1" @checked(old('has_health_center', $brgy->has_health_center))>
            <label class="form-check-label" for="has_health_center">Has health center</label>
            @error('has_health_center')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button class="btn btn-primary" type="submit">Save barangay</button>
        <a class="btn btn-secondary" href="{{ route('brgys.show', $brgy) }}">Cancel</a>
    </form>
@endsection
