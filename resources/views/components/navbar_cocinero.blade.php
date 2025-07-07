<div class="d-flex flex-column flex-shrink-0 p-3">
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('cocinero.orden.index') }}"
               class="nav-link {{ request()->routeIs('cocinero.orden*') ? 'active' : '' }}">
                <i class="bi bi-cart me-2"></i>
                Órdenes
            </a>
        </li>
    </ul>
</div>
