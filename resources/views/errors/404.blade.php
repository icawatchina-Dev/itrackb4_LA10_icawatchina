@extends('layouts.app')

@section('title', 'Page not found')

@section('content')
    <h1>Page not found</h1>
    <p>The page you requested could not be found.</p>
    <a href="{{ route('brgys.index') }}">Back to the list</a>
@endsection
