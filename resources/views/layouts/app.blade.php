<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Barangays in Catanduanes')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="container pt-3">
        <p class="small text-muted mb-1">Prepared by: China M. Icawat</p>
        <h1 class="visually-hidden">Barangays in Catanduanes</h1>
    </header>
    <nav class="container pt-2" aria-label="Main navigation">
        @include('partials._nav')
    </nav>
    <main class="container py-2">
        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>