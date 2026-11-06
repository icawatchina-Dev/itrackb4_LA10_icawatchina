@extends('layouts.app')

@section('title', 'Barangay Population Summary')

@section('content')
    <h2>Barangay Population Summary</h2>
    <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
        <tr>
            <th>Name</th>
            <th>Municipality</th>
            <th>Population</th>
        </tr>

        @foreach ($brgys as $brgy)
            <tr>
                <td>{{ $brgy['name'] }}</td>
                <td>{{ $brgy['municipality'] }}</td>
                <td>{{ $brgy['population'] }}</td>
            </tr>
        @endforeach
    </table>
    </div>
@endsection