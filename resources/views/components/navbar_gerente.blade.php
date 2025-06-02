<div class="d-flex flex-column flex-shrink-0 p-3">
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('gerente.dashboard') }}"
               class="nav-link {{ request()->routeIs('gerente.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('gerente.menu') }}"
               class="nav-link {{ request()->routeIs('gerente.menu') ? 'active' : '' }}">
                <i class="bi bi-menu-button-wide me-2"></i>
                Menú
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('gerente.mesas') }}"
               class="nav-link {{ request()->routeIs('gerente.mesas*') ? 'active' : '' }}">
                <i class="bi bi-grid-3x3 me-2"></i>
                Mesas
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('gerente.personal') }}"
               class="nav-link {{ request()->routeIs('gerente.personal') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i>
                Personal
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('gerente.facturacion') }}"
               class="nav-link {{ request()->routeIs('gerente.facturacion') ? 'active' : '' }}">
                <i class="bi bi-receipt me-2"></i>
                Facturación
            </a>
        </li>
    </ul>
</div>
