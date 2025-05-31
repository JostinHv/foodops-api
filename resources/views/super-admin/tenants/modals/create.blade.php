<!-- Modal: Nuevo Tenant -->
<div class="modal fade" id="nuevoTenantModal" tabindex="-1" aria-labelledby="nuevoTenantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formNuevoTenant" action="{{ route('superadmin.tenant.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="nuevoTenantModalLabel">Crear Nuevo Tenant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    @include('super-admin.tenants.forms.tenant-form')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Tenant</button>
                </div>
            </form>
        </div>
    </div>
</div>

