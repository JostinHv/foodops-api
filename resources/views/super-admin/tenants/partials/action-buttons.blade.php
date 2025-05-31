<div class="d-flex gap-2">
    <button class="btn btn-sm btn-outline-primary"
            title="Ver detalles"
            data-bs-toggle="modal"
            data-bs-target="#detallesTenantModal"
            data-tenant="{{ json_encode($tenant) }}">
        <i class="bi bi-eye"></i>
    </button>
    <button class="btn btn-sm btn-outline-secondary"
            title="Editar"
            data-bs-toggle="modal"
            data-bs-target="#editarTenantModal"
            data-tenant="{{ json_encode($tenant) }}">
        <i class="bi bi-pencil"></i>
    </button>
    <button class="btn btn-sm btn-outline-danger"
            title="Eliminar"
            data-bs-toggle="modal"
            data-bs-target="#eliminarItemModal"
            data-tenant-id="{{ $tenant['id'] }}">
        <i class="bi bi-trash"></i>
    </button>
    <button class="btn btn-sm btn-outline-danger"
            title="Desactivar"
            data-bs-toggle="modal"
            data-bs-target="#desactivarItemModal"
            data-tenant-id="{{ $tenant['id'] }}">
        <i class="bi bi-person-dash"></i>
    </button>
</div>

