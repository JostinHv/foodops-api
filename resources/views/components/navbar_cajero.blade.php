<div class="d-flex flex-column flex-shrink-0 p-3">
    <ul class="nav nav-pills flex-column mb-auto">
{{--       <li class="nav-item">
            <a href="{{ route('cajero.dashboard') }}"
               class="nav-link {{ request()->routeIs('cajero.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>
        </li>--}} 
        <li class="nav-item">
            <a href="{{ route('cajero.facturacion') }}"
               class="nav-link {{ request()->routeIs('cajero.facturacion') ? 'active' : '' }}">
                <i class="bi bi-receipt me-2"></i>
                Facturación
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('cajero.caja') }}" 
                class="nav-link {{ request()->routeIs('cajero.caja') ? 'active' : '' }}">
                <i class="bi bi-cash me-2"></i>
                Caja
            </a>
        </li>
    </ul>
</div>