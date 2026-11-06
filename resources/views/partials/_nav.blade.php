<ul class="nav nav-tabs">
	<li class="nav-item">
		<a class="nav-link {{ request()->is('brgys*') ? 'active' : '' }}" @if (request()->is('brgys*')) aria-current="page" @endif href="{{ route('brgys.index') }}">All Barangays</a>
	</li>
	<li class="nav-item">
		<a class="nav-link {{ request()->routeIs('brgys.filter') ? 'active' : '' }}" href="{{ route('brgys.filter', ['municipality' => 'San Andres']) }}">Filter by Municipality</a>
	</li>
</ul>