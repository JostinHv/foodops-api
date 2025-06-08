<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad - FoodOps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}"> {{-- Usado para estilos generales de header/footer --}}
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}"> {{-- Paleta de colores --}}
    {{-- Considerar un archivo CSS base compartido si hay más estilos comunes --}}
     <style>
        /* Estilos específicos para el contenido legal usando la paleta */
        .legal-content h1, .legal-content h2, .legal-content h4 {
            color: var(--primary-color);
        }
        .legal-content p, .legal-content li {
            color: var(--text-dark);
        }
        .legal-content ul li i {
            color: var(--accent-color);
        }
        .legal-content .card-body .text-muted {
             color: var(--text-muted) !important; /* Usar variable de texto secundario */
        }
         .legal-content .card-body {
             padding: 2rem; /* Asegurar padding en el contenido */
         }
          .legal-content .card {
             border: none;
             border-radius: var(--border-radius);
             overflow: hidden;
             box-shadow: var(--card-shadow);
             transition: var(--transition-base);
         }
    </style>
</head>
<body>
    <!-- Header (Copiado de home.blade.php) -->
    <header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="FoodOps Logo" class="me-2" style="width: 32px; height: 32px;">
                <span class="fw-bold" style="color: var(--primary-color);">FoodOps</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('detalles.planes') }}">Planes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contacto') }}">Contacto</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary">Iniciar Sesión</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('register') }}" class="btn btn-primary">Registrarse</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <main class="legal-content">
        {{-- Contenido original de politica-privacidad.blade.php --}}
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="mb-4">Política de Privacidad</h1>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h2 class="h4 mb-3">1. Información que Recopilamos</h2>
                            <p>Recopilamos información que usted nos proporciona directamente, como nombre, dirección de correo electrónico, información de contacto y datos de su restaurante.</p>

                            <h2 class="h4 mb-3 mt-4">2. Uso de la Información</h2>
                            <p>Utilizamos su información para:</p>
                            <ul>
                                <li>Proporcionar y mantener nuestros servicios</li>
                                <li>Procesar sus transacciones</li>
                                <li>Enviar actualizaciones y comunicaciones importantes</li>
                                <li>Mejorar nuestros servicios</li>
                            </ul>

                            <h2 class="h4 mb-3 mt-4">3. Protección de Datos</h2>
                            <p>Implementamos medidas de seguridad técnicas y organizativas para proteger su información personal contra acceso no autorizado, alteración o destrucción.</p>

                            <h2 class="h4 mb-3 mt-4">4. Compartir Información</h2>
                            <p>No vendemos ni alquilamos su información personal a terceros. Solo compartimos información cuando:</p>
                            <ul>
                                <li>Es necesario para proporcionar nuestros servicios</li>
                                <li>Está requerido por ley</li>
                                <li>Usted nos da su consentimiento</li>
                            </ul>

                            <h2 class="h4 mb-3 mt-4">5. Sus Derechos</h2>
                            <p>Usted tiene derecho a:</p>
                            <ul>
                                <li>Acceder a su información personal</li>
                                <li>Corregir información inexacta</li>
                                <li>Solicitar la eliminación de sus datos</li>
                                <li>Oponerse al procesamiento de sus datos</li>
                            </ul>

                            <h2 class="h4 mb-3 mt-4">6. Cookies y Tecnologías Similares</h2>
                            <p>Utilizamos cookies y tecnologías similares para mejorar su experiencia en nuestro sitio web y analizar el uso de nuestros servicios.</p>

                            <h2 class="h4 mb-3 mt-4">7. Cambios en la Política</h2>
                            <p>Nos reservamos el derecho de modificar esta política de privacidad en cualquier momento. Las modificaciones entrarán en vigor inmediatamente después de su publicación.</p>

                            <h2 class="h4 mb-3 mt-4">8. Contacto</h2>
                            <p>Si tiene preguntas sobre esta política de privacidad, puede contactarnos a través de:</p>
                            <ul>
                                <li>Email: privacy@foodops.com</li>
                                <li>Teléfono: +1 (234) 567-890</li>
                            </ul>

                            <div class="mt-5">
                                <p class="custom-text-muted">Última actualización: {{ date('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer (Copiado de home.blade.php) -->
    <footer class="py-5" style="background-color: var(--dark-bg); color: var(--text-light);">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="FoodOps Logo" class="me-2" style="height: 40px;">
                        <span class="h4 mb-0" style="color: var(--primary-color);">FoodOps</span>
                    </div>
                    <p class="custom-text-muted">Transformando la gestión de restaurantes con tecnología innovadora.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="custom-text-muted hover-primary"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="custom-text-muted hover-primary"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="custom-text-muted hover-primary"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="custom-text-muted hover-primary"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-3" style="color: var(--primary-color);">Enlaces</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}" class="custom-text-muted hover-primary">Inicio</a></li>
                        <li class="mb-2"><a href="{{ route('detalles.planes') }}" class="custom-text-muted hover-primary">Planes</a></li>
                        <li class="mb-2"><a href="{{ route('contacto') }}" class="custom-text-muted hover-primary">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-3" style="color: var(--primary-color);">Soporte</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="custom-text-muted hover-primary">FAQ</a></li>
                        <li class="mb-2"><a href="#" class="custom-text-muted hover-primary">Centro de Ayuda</a></li>
                        <li class="mb-2"><a href="#" class="custom-text-muted hover-primary">Soporte Técnico</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h5 class="mb-3" style="color: var(--primary-color);">Contacto</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-envelope me-2" style="color: var(--primary-color);"></i>
                            <a href="mailto:info@foodops.com" class="custom-text-muted hover-primary">info@foodops.com</a>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-telephone me-2" style="color: var(--primary-color);"></i>
                            <a href="tel:+1234567890" class="custom-text-muted hover-primary">+1 (234) 567-890</a>
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255, 255, 255, 0.1);">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="custom-text-muted mb-0">&copy; {{ date('Y') }} FoodOps. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="{{ route('terminos.condiciones') }}" class="custom-text-muted hover-primary me-3">Términos y
                        Condiciones</a>
                    <a href="{{ route('politica.privacidad') }}" class="custom-text-muted hover-primary">Política de
                        Privacidad</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 