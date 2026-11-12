@extends('layouts.app')

@section('title', 'Server error')

@section('content')
    <h1>500 - Server Error</h1>
    <p>Sorry, something went wrong. Please try again later.</p>
    <a href="{{ route('brgys.index') }}">Back to barangay list</a>
@endsection
