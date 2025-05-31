<!-- Modal: Detalles del Tenant -->
<div class="modal fade" id="detallesTenantModal" tabindex="-1" aria-labelledby="detallesTenantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detallesTenantModalLabel">Detalles del Tenant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    @include('super-admin.tenants.partials.details-info')
                    @include('super-admin.tenants.partials.details-stats')
                </div>
            </div>
        </div>
    </div>
</div>

