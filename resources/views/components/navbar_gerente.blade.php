<ul class="nav flex-column p-3">
    <li class="nav-item">
        <a class="nav-link text-dark {{ request()->routeIs('gerente.dashboard') || request()->routeIs('mesero.ordenes*') ? 'active' : '' }}" 
           href="{{ route('gerente.dashboard') }}">
            <i class="bi bi-house me-2"></i>Dashboard
        </a>
    </li>
     <li class="nav-item">
        <a class="nav-link text-dark {{ request()->routeIs('gerente.menu') || request()->routeIs('mesero.mesas*') ? 'active' : '' }}" 
           href="{{ route('gerente.menu') }}">
            <i class="bi bi-ui-checks-grid me-2"></i>Menú
        </a>
    </li>
</ul>