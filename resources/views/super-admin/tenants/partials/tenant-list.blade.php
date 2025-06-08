<!-- Lista de Tenants -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Lista de Tenants</h5>
        <div class="d-flex align-items-center">
            <span class="badge bg-primary me-3">{{ count($tenants) }} tenants encontrados</span>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tenant</th>
                        <th>Plan</th>
                        <th class="d-none d-sm-table-cell">Restaurantes</th>
                        <th class="d-none d-sm-table-cell">Usuarios</th>
                        <th>Estado</th>
                        <th class="d-none d-md-table-cell">Último Acceso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $tenant)
                        @include('super-admin.tenants.partials.tenant-row', ['tenant' => $tenant])
                    @empty
                        @include('super-admin.tenants.partials.empty-state')
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Estilos para los botones de estado */
    .btn-sm .bi-toggle-on,
    .btn-sm .bi-toggle-off {
        font-size: 1.5rem;
        line-height: 1;
    }

    /* Ajustar el tamaño del tooltip */
    .tooltip {
        font-size: 0.875rem;
    }

    /* Mejorar la apariencia del botón de estado */
    .btn-sm.btn-success,
    .btn-sm.btn-warning {
        padding: 0.25rem;
        line-height: 1;
    }
</style>
@endpush

