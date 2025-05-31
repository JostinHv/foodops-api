<!-- Encabezado -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Usuarios de {{ $tenant->datos_contacto['nombre_empresa'] ?? $tenant->dominio }}</h1>
        <p class="mb-0 text-muted">Gestión de usuarios y roles del tenant</p>
    </div>
    <div>
        <a href="{{ route('superadmin.tenant') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver a Tenants
        </a>
    </div>
</div>

