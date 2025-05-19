<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title', 'FoodOps')</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        @stack('styles')
    </head>
    <body>

        <!-- Header o navbar común -->

        <!-- Contenido que cambiará en cada vista -->
        <main class="container py-4">
            @yield('content')
        </main>

    </body>
</html>
