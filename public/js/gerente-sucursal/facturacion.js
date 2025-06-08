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

    // Evento para el botón de ver factura
    document.querySelectorAll('[data-action="ver-factura"]').forEach(button => {
        button.addEventListener('click', async function() {
            const facturaId = this.dataset.factura;
            const modal = document.getElementById('verFacturaModal');
            const modalBody = modal.querySelector('#factura-detalles-contenido');
            const loadingPlaceholder = modalBody.querySelector('#loading-placeholder');
            const loadedContent = modalBody.querySelector('#factura-loaded-content');

            // Mostrar loading y ocultar contenido cargado
            if (loadingPlaceholder) loadingPlaceholder.style.display = 'block';
            if (loadedContent) loadedContent.style.display = 'none';

            try {
                const response = await fetch(`/gerente/facturacion/${facturaId}`);
                const data = await response.json();

                if (response.ok && data.factura) {
                    const factura = data.factura;

                    // Poblar Detalles de la Factura
                    document.getElementById('detalle-factura-numero').textContent = factura.nro_factura || 'N/A';
                    document.getElementById('detalle-factura-fecha').textContent = factura.created_at ? new Date(factura.created_at).toLocaleString('es-ES', { dateStyle: 'short', timeStyle: 'short' }) : 'N/A';

                    // Poblar Detalles de la Orden
                    document.getElementById('detalle-orden-numero').textContent = factura.orden?.nro_orden || 'N/A';
                    document.getElementById('detalle-orden-cliente').textContent = factura.orden?.nombre_cliente || 'Cliente General';
                    document.getElementById('detalle-orden-mesa').textContent = factura.orden?.mesa?.nombre || 'N/A';
                    document.getElementById('detalle-orden-fecha').textContent = factura.orden?.created_at ? new Date(factura.orden.created_at).toLocaleString('es-ES', { dateStyle: 'short', timeStyle: 'short' }) : 'N/A';

                    // Poblar Items de la Orden
                    const itemsList = document.getElementById('detalle-items-list');
                    itemsList.innerHTML = ''; // Limpiar lista actual
                    if (factura.orden?.items_ordenes && factura.orden.items_ordenes.length > 0) {
                        factura.orden.items_ordenes.forEach(item => {
                            const itemName = item.item_menu?.nombre || 'Producto Desconocido';
                            const itemUnitPrice = parseFloat(item.monto / item.cantidad || 0).toFixed(2);
                            const itemTotal = parseFloat(item.monto || 0).toFixed(2);
                            const listItem = document.createElement('li');
                            listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                            listItem.innerHTML = `
                                <div>
                                    <strong>${item.cantidad}x ${itemName}</strong><br>
                                    <small class="text-muted">P.U.: S/ ${itemUnitPrice}</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">S/ ${itemTotal}</span>
                            `;
                            itemsList.appendChild(listItem);
                        });
                    } else {
                         itemsList.innerHTML = '<li class="list-group-item text-muted">No hay items asociados a esta orden.</li>';
                    }

                    // Poblar Resumen de Totales
                    document.getElementById('detalle-subtotal').textContent = parseFloat(factura.monto_total || 0).toFixed(2);
                    document.getElementById('detalle-igv-porcentaje').textContent = factura.igv?.valor_porcentaje || 'N/A';
                    document.getElementById('detalle-igv').textContent = parseFloat(factura.monto_total_igv || 0).toFixed(2);
                    document.getElementById('detalle-total').textContent = (parseFloat(factura.monto_total || 0) + parseFloat(factura.monto_total_igv || 0)).toFixed(2);

                    // Poblar Información de Pago
                    document.getElementById('detalle-metodo-pago').textContent = factura.metodo_pago?.nombre || 'N/A';
                    document.getElementById('detalle-estado-pago').textContent = (factura.estado_pago || 'N/A').charAt(0).toUpperCase() + (factura.estado_pago || '').slice(1);
                    document.getElementById('detalle-notas').textContent = factura.notas || 'Ninguna';

                    // Ocultar loading y mostrar contenido cargado
                    if (loadingPlaceholder) loadingPlaceholder.style.display = 'none';
                    if (loadedContent) loadedContent.style.display = 'block';

                    // Configurar los botones de descarga/impresión en el modal footer (ya deberían estar configurados si usan el ID de factura correcto)
                    const btnDescargarPDF = document.getElementById('btnDescargarPDF');
                    const btnImprimirPOS = document.getElementById('btnImprimirPOS');

                    if (btnDescargarPDF) {
                         btnDescargarPDF.href = `/gerente/facturacion/${facturaId}/pdf`;
                    }
                    if (btnImprimirPOS) {
                        btnImprimirPOS.href = `/gerente/facturacion/${facturaId}/pdf-pos`;
                    }

                } else {
                     // Ocultar loading y mostrar mensaje de error
                    if (loadingPlaceholder) loadingPlaceholder.style.display = 'none';
                    if (loadedContent) loadedContent.style.display = 'none';
                    modalBody.innerHTML = '<div class="alert alert-danger">Error al cargar los detalles de la factura.</div>';
                }
            } catch (error) {
                 // Ocultar loading y mostrar mensaje de error
                 if (loadingPlaceholder) loadingPlaceholder.style.display = 'none';
                 if (loadedContent) loadedContent.style.display = 'none';
                modalBody.innerHTML = '<div class="alert alert-danger">Error al cargar los detalles de la factura. Intente nuevamente.</div>';
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
