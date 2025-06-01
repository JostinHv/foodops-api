<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'FoodOps')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

                <div class="dropdown d-flex align-items-center">
                    <i class="bi bi-person-circle"></i>
                    <img src="{{ Auth::user()->foto ?? asset('images/default-user.png') }}" alt="Avatar"
                        class="rounded-circle" width="40" height="40">
                    <div class="text-end me-2 d-none d-sm-block">
                        <div class="fw-semibold">{{ Auth::user()->name }}</div>
                        <div class="text-muted small">{{ ucfirst(Auth::user()->role) }}</div>
                    </div>
                    <a href="#" class="d-block dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Perfil</a></li>
                        <li>
                            <form method="POST" action="">
                                @csrf
                                <button type="submit" class="dropdown-item">Cerrar sesión</button>
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
                @includeWhen(Auth::user()->role === 'mesero', 'components.navbar_mesero')
                @includeWhen(Auth::user()->role === 'admin', 'components.navbar_admin')
                @includeWhen(Auth::user()->role === 'gerente', 'components.navbar_gerente')
                @includeWhen(Auth::user()->role === 'superadmin', 'components.navbar_superadmin')
            </nav>

            {{-- Contenido principal --}}
            <main class="flex-grow-1 p-4">
                @yield('content')
            </main>
        </div>
    @else
        {{-- Para login, registro y vistas públicas --}}
        <main class="container py-4">
            @yield('content')
        </main>
    @endauth

    </body>
</html>
