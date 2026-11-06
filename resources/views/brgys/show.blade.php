@extends('layouts.app')

@section('title', $brgy['name'] . ' Details')

@section('content')
    <div class="card" style="max-width: 32rem;">
        <div class="card-body">
            <h2 class="card-title">Barangay Details</h2>
            <p><strong>ID:</strong> {{ $brgy['id'] }}</p>
            <p><strong>Name:</strong> {{ $brgy['name'] }}</p>
            <p><strong>Municipality:</strong> {{ $brgy['municipality'] }}</p>
            <p><strong>Population:</strong> {{ $brgy['population'] ?? 'Not available' }}</p>
            <a class="btn btn-primary" href="{{ route('brgys.index') }}">Back to barangay list</a>
        </div>
    </div>
@endsection