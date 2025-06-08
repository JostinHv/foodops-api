<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>@yield('title', 'FoodOps')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
{{--    <script src="{{ asset('js/app.js') }}"></script>--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @stack('meta')
    @stack('styles')
    @stack('scripts')
</head>
<body>
@auth
    {{-- Encabezado superior --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3 py-2">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <button class="btn btn-outline-secondary me-3" id="toggleMenuBtn" type="button">
                <i class="bi bi-list" id="toggleIcon"></i>
            </button>

            <div class="d-flex align-items-center me-auto">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="40" height="40" class="me-2">
                <span class="fw-bold text-danger">FoodOps</span>
            </div>

            <div class="dropdown">
                <div class="user-menu d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false">
                    @if(Auth::user()->foto_perfil_id && Auth::user()->fotoPerfil)
                        <img src="{{ asset('storage/' . Auth::user()->fotoPerfil->ruta) }}"
                             alt="Avatar"
                             class="rounded-circle"
                             width="40" height="40">
                    @else
                        <div class="user-avatar">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    @endif
                    <div class="text-end me-2 d-none d-sm-block">
                        <div class="fw-semibold">{{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}</div>
                        <div class="text-muted small">
                            @foreach(Auth::user()->roles as $role)
                                {{ $role->nombre }}
                            @endforeach
                        </div>
                    </div>
                    <i class="bi bi-chevron-down ms-2"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item"
                           href="{{ Auth::user()->roles->contains('nombre', 'mesero') ? route('mesero.perfil') : (Auth::user()->roles->contains('nombre', 'gerente') ? route('gerente.perfil') : (Auth::user()->roles->contains('nombre', 'administrador') ? route('tenant.perfil') : route('perfil'))) }}">
                            <i class="bi bi-person me-2"></i>
                            Mi Perfil
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Layout de página con sidebar + contenido --}}
    <div class="d-flex" id="mainWrapper">
        {{-- Sidebar vertical --}}
        <nav id="sidebar" class="bg-light border-end">
            @includeWhen(Auth::user()->roles->contains('nombre', 'mesero'), 'components.navbar_mesero')
            @includeWhen(Auth::user()->roles->contains('nombre', 'administrador'), 'components.navbar_admin')
            @includeWhen(Auth::user()->roles->contains('nombre', 'gerente'), 'components.navbar_gerente')
            @includeWhen(Auth::user()->roles->contains('nombre', 'superadmin'), 'components.navbar_superadmin')
        </nav>

        {{-- Contenido principal --}}
        <main class="flex-grow-1 p-4">
            @yield('content')
        </main>
    </div>

    {{-- Overlay para cerrar sidebar en mobile --}}
    <div id="sidebar-overlay"></div>

    <script src="{{ asset('js/navbar.js') }}"></script>

    <script>
        // Script para logout y recarga (mantener aquí si es específico de app.blade.php)
        document.addEventListener('DOMContentLoaded', function () {
            if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
                window.location.reload();
            }

            document.querySelector('form[action="{{ route('logout') }}"]').addEventListener('submit', function (e) {
                e.preventDefault();

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                }).then(() => {
                    window.location.replace('{{ route('login') }}');
                });
            });
        });
    </script>
@else
    {{-- Para login, registro y vistas públicas --}}
    <main class="container py-4">
        @yield('content')
    </main>
@endauth

</body>
</html>
