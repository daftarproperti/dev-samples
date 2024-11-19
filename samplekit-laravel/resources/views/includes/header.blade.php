
<a class="navbar-brand mb-0" href="{{ route('listings.index') }}">
    @if(config('app.logo'))
        <img src="{{ config('app.logo') }}" alt="{{ config('app.name') }}" height="36" class="d-inline-block align-text-top">
    @else
        {{ config('app.name') }}
    @endif
</a>
<div class="navbar-collapse justify-content-end" id="navbarNav">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('listings.index') }}"><i class="bi bi-house-door-fill"></i> Beranda</a>
        </li>
    </ul>
</div>
