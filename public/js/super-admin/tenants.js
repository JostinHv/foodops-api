document.addEventListener('DOMContentLoaded', function () {
    // Previsualización de imagen
    const logoInput = document.getElementById('logo');
    if (logoInput) {
        logoInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const preview = document.createElement('img');
                    preview.src = e.target.result;
                    preview.className = 'img-thumbnail mt-2';
                    preview.style.maxHeight = '100px';

                    const previewContainer = logoInput.parentElement;
                    const existingPreview = previewContainer.querySelector('img');
                    if (existingPreview) {
                        existingPreview.remove();
                    }
                    previewContainer.appendChild(preview);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Manejo de modales
    const modals = {
        details: document.getElementById('detallesTenantModal'),
        edit: document.getElementById('editarTenantModal'),
    };

    // Actualizar datos en modales
    document.querySelectorAll('[data-tenant]').forEach(button => {
        button.addEventListener('click', function () {
            const tenant = JSON.parse(this.dataset.tenant);

            if (this.dataset.bsTarget === '#detallesTenantModal') {
                updateDetailsModal(tenant);
            } else if (this.dataset.bsTarget === '#editarTenantModal') {
                updateEditModal(tenant);
            }
        });
    });

    // Actualizar IDs en formularios de eliminación/desactivación
    document.querySelectorAll('[data-tenant-id]').forEach(button => {
        button.addEventListener('click', function () {
            const tenantId = this.dataset.tenantId;
            const modal = this.dataset.bsTarget;

            if (modal === '#eliminarItemModal') {
                document.getElementById('formEliminarItem').action =
                    document.getElementById('formEliminarItem').action.replace(/\/\d*$/, '/' + tenantId);
            } else if (modal === '#desactivarItemModal') {
                document.getElementById('formDesactivarItem').action =
                    document.getElementById('formDesactivarItem').action.replace(/\/\d*$/, '/' + tenantId);
            }
        });
    });
});

function updateDetailsModal(tenant) {
    const modal = document.getElementById('detallesTenantModal');
    if (!modal) return;

    // Actualizar información general
    modal.querySelector('#tenant-nombre').textContent = tenant.datos_contacto.nombre_empresa;
    modal.querySelector('#tenant-email').textContent = tenant.datos_contacto.email;
    modal.querySelector('#tenant-telefono').textContent = tenant.datos_contacto.telefono;
    modal.querySelector('#tenant-direccion').textContent = tenant.datos_contacto.direccion;

    // Actualizar logo si existe
    const logoContainer = modal.querySelector('.tenant-logo');
    if (logoContainer) {
        if (tenant.logo) {
            logoContainer.innerHTML = `<img src="/storage/${tenant.logo.url}" alt="Logo" class="img-thumbnail" style="max-height: 100px">`;
        } else {
            logoContainer.innerHTML = `
                <div class="border rounded p-3 text-muted">
                    <i class="bi bi-building fs-1"></i>
                    <p class="small mb-0">Sin logo</p>
                </div>`;
        }
    }

    // Actualizar estado
    const estadoBadge = modal.querySelector('#tenant-estado');
    if (estadoBadge) {
        estadoBadge.className = `badge ${tenant.activo ? 'bg-success' : 'bg-danger'}`;
        estadoBadge.textContent = tenant.activo ? 'Activo' : 'Inactivo';
    }
}

function updateEditModal(tenant) {
    const form = document.getElementById('formEditarTenant');
    if (!form) return;

    // Actualizar campos básicos
    form.querySelector('#dominio').value = tenant.dominio;
    form.querySelector('#activo').checked = tenant.activo;

    // Actualizar datos de contacto
    Object.keys(tenant.datos_contacto).forEach(key => {
        const input = form.querySelector(`[name="datos_contacto[${key}]"]`);
        if (input) {
            input.value = tenant.datos_contacto[key];
        }
    });

    // Actualizar preview del logo si existe
    if (tenant.logo) {
        const logoPreview = document.createElement('img');
        logoPreview.src = `/storage/${tenant.logo.url}`;
        logoPreview.className = 'img-thumbnail mt-2';
        logoPreview.style.maxHeight = '100px';

        const logoContainer = form.querySelector('#logo').parentElement;
        const existingPreview = logoContainer.querySelector('img');
        if (existingPreview) {
            existingPreview.remove();
        }
        logoContainer.appendChild(logoPreview);
    }
}
