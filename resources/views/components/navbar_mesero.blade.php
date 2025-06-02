<div class="d-flex flex-column flex-shrink-0 p-3">
    <ul class="nav nav-pills flex-column mb-auto">
{{--    <li class="nav-item">--}}
{{--            <a href="{{ route('mesero.dashboard') }}" class="nav-link {{ request()->routeIs('mesero.dashboard') ? 'active' : '' }}">--}}
{{--                <i class="bi bi-speedometer2 me-2"></i>--}}
{{--                Dashboard--}}
{{--            </a>--}}
{{--    </li>--}}
    <li class="nav-item">
            <a href="{{ route('mesero.orden.index') }}"
               class="nav-link {{ request()->routeIs('mesero.orden*') ? 'active' : '' }}">
                <i class="bi bi-cart me-2"></i>
                Órdenes
            </a>
    </li>
            </ul>
        </div>
