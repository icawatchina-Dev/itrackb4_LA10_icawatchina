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
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                            @if (request()->routeIs('login')) aria-current="page" @endif
                            href="{{ route('login') }}">Log in</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('register.*') ? 'active' : '' }}"
                            @if (request()->routeIs('register.*')) aria-current="page" @endif
                            href="{{ route('register.create') }}">Register</a>
                    </li>
                @endguest
            </ul>
            @auth
                <span class="navbar-text ms-lg-3">Signed in as {{ auth()->user()->name }}</span>
                <form class="ms-lg-3" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-light btn-sm" type="submit">Log out</button>
                </form>
            @endauth
        </div>
    </div>
</nav>
