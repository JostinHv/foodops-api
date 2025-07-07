<div class="d-flex flex-column flex-shrink-0 p-3">
    <ul class="nav nav-pills flex-column mb-auto">
{{--        <li class="nav-item">--}}
{{--            <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">--}}
{{--                <i class="bi bi-speedometer2 me-2"></i>--}}
{{--                Dashboard--}}
{{--            </a>--}}
{{--        </li>--}}
        <li class="nav-item">
            <a href="{{ route('superadmin.tenant') }}" class="nav-link {{ request()->routeIs('superadmin.tenant*') ? 'active' : '' }}">
                <i class="bi bi-building me-2"></i>
                Tenants
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('planes') }}" class="nav-link {{ request()->routeIs('planes*') ? 'active' : '' }}">
                <i class="bi bi-credit-card me-2"></i>
                Planes
            </a>
        </li>
    <li class="nav-item">
            <a href="{{ route('superadmin.pago') }}" class="nav-link {{ request()->routeIs('superadmin.pago') ? 'active' : '' }}">
                <i class="bi bi-cash-stack me-2"></i>
                Pagos
        </a>
    </li>
     <li class="nav-item">
            <a href="{{ route('superadmin.igv') }}" class="nav-link {{ request()->routeIs('superadmin.igv') ? 'active' : '' }}">
                <i class="bi bi-percent me-2"></i>
                IGV
        </a>
    </li>
    <li class="nav-item">
            <a href="{{ route('superadmin.movimientos') }}" class="nav-link {{ request()->routeIs('superadmin.movimientos') ? 'active' : '' }}">
                <i class="bi bi-clock-history me-2"></i>
                Auditoría
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('superadmin.sugerencias.index') }}" class="nav-link {{ request()->routeIs('superadmin.sugerencias.*') ? 'active' : '' }}">
            <i class="bi bi-lightbulb me-2"></i>
            Sugerencias
        </a>
    </li>
</ul>
</div>
