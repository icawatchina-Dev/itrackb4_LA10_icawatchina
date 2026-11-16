<nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark" aria-label="Main navigation">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">Barangays in Catanduanes</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
            aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        @if (request()->routeIs('home')) aria-current="page" @endif
                        href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('brgys.*') ? 'active' : '' }}"
                        @if (request()->routeIs('brgys.*')) aria-current="page" @endif
                        href="{{ route('brgys.index') }}">Barangays</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
