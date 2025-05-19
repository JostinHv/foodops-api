<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>FoodOps</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    </head>
    <body>
        <header class="d-flex align-items-center justify-content-between p-4 mb-4 shadow-sm">
            <div class="d-flex align-items-center gap-2">
                <img src="{{asset('images/logo.png')}}" alt="Logo" style="width: 24px; height: 24px;">
                <span class="fw-bold text-danger fs-5">FoodOps</span>
            </div>
            <div class="d-flex gap-2">
                <a href="{{route('login')}}" class="btn button-secondary">Iniciar Sesión</a>
                <a href="{{route('register')}}" class="btn button-primary">Registrarse</a>
            </div>
        </header>
    
        <section class="text-center py-5">
            <h1 class="display-4 fw-bold mb-3">Sistema de Gestión de Restaurantes</h1>
            <p class="lead text-muted mb-4">
                Administra tus restaurantes, sucursales, personal, menús, órdenes y facturación en una sola plataforma
            </p>
            <a href="{{route('login')}}" class="button-primary">Comenzar ahora →</a>
        </section>
    
        <div class="d-flex justify-content-center gap-3 mb-4">
            <button class="btn btn-secondary">Mensual</button>
            <button class="btn btn-outline-secondary">Anual</button>
        </div>
    
        <section class="container pb-5">
            <div class="row justify-content-center g-4">
                @for($i = 0; $i < 3; $i++)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card {{ $i == 1 ? 'highlight' : '' }}">
                        <div class="card-body">
                            <h3 class="card-title h5 mb-3">Title</h3>
                            <p class="card-text h3 mb-3">$50 <small class="text-muted fs-6">/mo</small></p>
                            <ul class="mb-4">
                                <li>List item</li>
                                <li>List item</li>
                                <li>List item</li>
                                <li>List item</li>
                            </ul>
                            <a href="#" class="button-primary d-block text-center">Elegir</a>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </section>
    </body>
</html>