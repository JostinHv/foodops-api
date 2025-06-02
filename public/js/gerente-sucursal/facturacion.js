// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${tipo} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.style.position = 'fixed';
    toast.style.top = '1rem';
    toast.style.right = '1rem';
    toast.style.zIndex = '1050';
    toast.style.minWidth = '200px';
    toast.style.maxWidth = '400px';

    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${mensaje}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;

    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 3000
    });
    bsToast.show();

    toast.addEventListener('hidden.bs.toast', () => {
        document.body.removeChild(toast);
    });
}

// Función para cerrar modales
function cerrarModal(modalId) {
    const modal = document.getElementById(modalId);
    const bsModal = bootstrap.Modal.getInstance(modal);
    if (bsModal) {
        bsModal.hide();
    }
}

// Función para calcular totales
async function calcularTotales() {
    const ordenId = document.getElementById('orden_id').value;
    const igvId = document.getElementById('igv_id').value;

    if (!ordenId || !igvId) {
        return;
    }

    try {
        const response = await fetch('/gerente/facturacion/calcular-totales', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({orden_id: ordenId, igv_id: igvId})
        });

        const data = await response.json();

        if (response.ok) {
            document.getElementById('subtotal').textContent = `S/ ${data.subtotal.toFixed(2)}`;
            document.getElementById('monto_igv').textContent = `S/ ${data.monto_igv.toFixed(2)}`;
            document.getElementById('total').textContent = `S/ ${data.total.toFixed(2)}`;
            document.getElementById('igv_porcentaje').textContent = data.igv_porcentaje;
        } else {
            mostrarNotificacion(data.message, 'danger');
        }
    } catch (error) {
        mostrarNotificacion('Error al calcular totales', 'danger');
    }
}

// Función para deshabilitar/habilitar botones
function toggleSubmitButton(form, disabled = true) {
    const submitButton = form.querySelector('button[type="submit"]');
    if (submitButton) {
        submitButton.disabled = disabled;
        submitButton.innerHTML = disabled ?
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...' :
            'Guardar Cambios';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Formulario de nueva factura
    const formNuevaFactura = document.getElementById('nuevaFacturaForm');
    if (formNuevaFactura) {
        formNuevaFactura.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Prevenir múltiples envíos
            if (this.submitting) return;
            this.submitting = true;
            toggleSubmitButton(this, true);

            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    mostrarNotificacion(data.message);
                    cerrarModal('nuevaFacturaModal');
                    window.location.reload();
                } else {
                    mostrarNotificacion(data.message, 'danger');
                }
            } catch (error) {
                mostrarNotificacion('Error al crear la factura', 'danger');
            } finally {
                this.submitting = false;
                toggleSubmitButton(this, false);
            }
        });
    }

    // Event listeners para calcular totales
    const ordenSelect = document.getElementById('orden_id');
    const igvSelect = document.getElementById('igv_id');

    if (ordenSelect) {
        ordenSelect.addEventListener('change', calcularTotales);
    }
    if (igvSelect) {
        igvSelect.addEventListener('change', calcularTotales);
    }

    // Botones de acción
    document.querySelectorAll('[data-action="ver-factura"]').forEach(button => {
        button.addEventListener('click', async function() {
            const facturaId = this.dataset.factura;
            const modal = document.getElementById('verFacturaModal');
            
            // Mostrar loading en el modal
            const modalBody = modal.querySelector('.modal-body');
            modalBody.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div><p class="mt-2">Cargando detalles...</p></div>';
            
            try {
                const response = await fetch(`/gerente/facturacion/${facturaId}`);
                const data = await response.json();

                if (response.ok && data.factura) {
                    const factura = data.factura;
                    modalBody.innerHTML = `
                        <div class="mb-3">
                            <h6><i class="bi bi-receipt me-1"></i>Número de Factura</h6>
                            <p>${factura.nro_factura || 'No especificado'}</p>
                        </div>
                        <div class="mb-3">
                            <h6><i class="bi bi-person me-1"></i>Cliente</h6>
                            <p>${factura.orden?.nombre_cliente || 'No especificado'}</p>
                        </div>
                        <div class="mb-3">
                            <h6><i class="bi bi-table me-1"></i>Mesa</h6>
                            <p>${factura.orden?.mesa?.nombre || 'No especificada'}</p>
                        </div>
                        <div class="mb-3">
                            <h6><i class="bi bi-cash me-1"></i>Subtotal</h6>
                            <p>S/ ${parseFloat(factura.monto_total || 0).toFixed(2)}</p>
                        </div>
                        <div class="mb-3">
                            <h6><i class="bi bi-percent me-1"></i>IGV</h6>
                            <p>S/ ${parseFloat(factura.monto_total_igv || 0).toFixed(2)}</p>
                        </div>
                        <div class="mb-3">
                            <h6><i class="bi bi-currency-dollar me-1"></i>Total</h6>
                            <p>S/ ${(parseFloat(factura.monto_total || 0) + parseFloat(factura.monto_total_igv || 0)).toFixed(2)}</p>
                        </div>
                        <div class="mb-3">
                            <h6><i class="bi bi-credit-card me-1"></i>Método de Pago</h6>
                            <p>${factura.metodo_pago?.nombre || 'No especificado'}</p>
                        </div>
                        <div class="mb-3">
                            <h6><i class="bi bi-toggle-on me-1"></i>Estado</h6>
                            <p>${factura.estado_pago || 'No especificado'}</p>
                        </div>
                    `;

                    // Configurar el botón de descarga PDF
                    const btnDescargarPDF = document.getElementById('btnDescargarPDF');
                    if (btnDescargarPDF) {
                        btnDescargarPDF.onclick = () => {
                            window.open(`/gerente/facturacion/${facturaId}/pdf`, '_blank');
                        };
                    }
                } else {
                    modalBody.innerHTML = '<div class="alert alert-danger">Error al cargar los detalles de la factura</div>';
                }
            } catch (error) {
                modalBody.innerHTML = '<div class="alert alert-danger">Error al cargar los detalles de la factura</div>';
                console.error('Error:', error);
            }
        });
    });

    // Botones de eliminar factura
    document.querySelectorAll('[data-action="eliminar-factura"]').forEach(button => {
        button.addEventListener('click', async function () {
            if (confirm('¿Estás seguro de que deseas eliminar esta factura?')) {
                const facturaId = this.dataset.factura;
                try {
                    const response = await fetch(`/gerente/facturacion/${facturaId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();
                    if (response.ok) {
                        mostrarNotificacion(data.message);
                        window.location.reload();
                    } else {
                        mostrarNotificacion(data.message, 'danger');
                    }
                } catch (error) {
                    mostrarNotificacion('Error al eliminar la factura', 'danger');
                }
            }
        });
    });

    // Event listener para el formulario de edición
    const editarFacturaForm = document.getElementById('editarFacturaForm');
    if (editarFacturaForm) {
        editarFacturaForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Prevenir múltiples envíos
            if (this.submitting) return;
            this.submitting = true;
            toggleSubmitButton(this, true);

            try {
                const facturaId = this.dataset.factura;
                const formData = new FormData(this);
                const response = await fetch(`/gerente/facturacion/${facturaId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    mostrarNotificacion(data.message);
                    cerrarModal('editarFacturaModal');
                    window.location.reload();
                } else {
                    mostrarNotificacion(data.message, 'danger');
                }
            } catch (error) {
                mostrarNotificacion('Error al actualizar la factura', 'danger');
                console.error('Error:', error);
            } finally {
                this.submitting = false;
                toggleSubmitButton(this, false);
            }
        });
    }

    // Configurar el modal de edición cuando se abre
    document.querySelectorAll('[data-bs-target="#editarFacturaModal"]').forEach(button => {
        button.addEventListener('click', function () {
            const facturaId = this.dataset.factura;
            const form = document.getElementById('editarFacturaForm');
            form.dataset.factura = facturaId;
        });
    });
});
