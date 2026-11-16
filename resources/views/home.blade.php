@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <section class="p-5 mb-4 bg-light rounded-3 shadow-sm">
        <h2 class="fw-bold">Browse and manage local barangay records</h2>
        <p class="text-muted mb-4">
            Find barangays by municipality and keep their population information organized.
        </p>
        <a href="{{ route('brgys.index') }}" class="btn btn-primary">Manage Barangays</a>
    </section>

    <div class="row g-3 mb-4">
        <div class="col-sm-6">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="display-6 fw-bold">{{ $municipalityCount }}</div>
                    <div class="text-muted">municipalities</div>
                </div>    
            </div>
        </div>

        <div class="col-sm-6">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="display-6 fw-bold">{{ $brgyCount }}</div>
                    <div class="text-muted">barangays</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body d-flex align-items-center gap-3">
            <img src="{{ asset('img/chin.jpg') }}" alt="China M. Icawat" width="96" height="96"
                class="rounded-circle object-fit-cover">
            <div>
                <h5 class="mb-1">China M. Icawat</h5>
                <div class="text-muted small">BSIT · 3rd Year · Block C</div>
            </div>
        </div>
    </div>

@endsection