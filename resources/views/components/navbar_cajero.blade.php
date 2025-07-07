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
        <li class="nav-item">
            <a href="{{ route('cajero.caja.apertura') }}"
               class="nav-link {{ request()->routeIs('cajero.caja.apertura') ? 'active' : '' }}">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Apertura de Caja
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('cajero.caja.cierre') }}"
               class="nav-link {{ request()->routeIs('cajero.caja.cierre') ? 'active' : '' }}">
                <i class="bi bi-box-arrow-left me-2"></i>
                Cierre de Caja
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('cajero.caja.movimientos') }}"
               class="nav-link {{ request()->routeIs('cajero.caja.movimientos') ? 'active' : '' }}">
                <i class="bi bi-list-ul me-2"></i>
                Movimientos de Caja
            </a>
        </li>
    </ul>
</div>