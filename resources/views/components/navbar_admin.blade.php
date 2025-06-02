<div class="d-flex flex-column flex-shrink-0 p-3">
    <ul class="nav nav-pills flex-column mb-auto">
{{--        <li class="nav-item">--}}
{{--            <a href="{{ route('tenant.dashboard') }}"--}}
{{--               class="nav-link {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">--}}
{{--                <i class="bi bi-speedometer2 me-2"></i>--}}
{{--                Dashboard--}}
{{--            </a>--}}
{{--        </li>--}}
        <li class="nav-item">
            <a href="{{ route('tenant.grupo-restaurant') }}"
               class="nav-link {{ request()->routeIs('tenant.grupo-restaurant*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3 me-2"></i>
                Grupos
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tenant.restaurantes') }}"
               class="nav-link {{ request()->routeIs('tenant.restaurantes*') ? 'active' : '' }}">
                <i class="bi bi-shop me-2"></i>
                Restaurantes
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tenant.sucursales') }}"
               class="nav-link {{ request()->routeIs('tenant.sucursales*') ? 'active' : '' }}">
                <i class="bi bi-building me-2"></i>
                Sucursales
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('tenant.usuarios') }}"
               class="nav-link {{ request()->routeIs('tenant.usuarios*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i>
                Trabajadores
            </a>
        </li>
    </ul>
</div>
