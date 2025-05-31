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
                        <th>Restaurantes</th>
                        <th>Usuarios</th>
                        <th>Estado</th>
                        <th>Último Acceso</th>
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

