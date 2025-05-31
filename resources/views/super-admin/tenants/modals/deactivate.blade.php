<!-- Modal: Confirmar Desactivación -->
<div class="modal fade" id="desactivarItemModal" tabindex="-1" aria-labelledby="desactivarItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="desactivarItemModalLabel">Confirmar Desactivación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro que deseas desactivar el Tenant?</p>
                <p class="text-danger">Esta acción suspenderá el acceso al sistema para todos los usuarios del tenant.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <form id="formDesactivarItem" action="{{ route('super-admin.tenants.deactivate', '') }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">Desactivar Tenant</button>
                </form>
            </div>
        </div>
    </div>
</div>

