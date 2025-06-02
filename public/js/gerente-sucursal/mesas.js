document.addEventListener('DOMContentLoaded', function () {
    // Función para mostrar notificaciones
    function mostrarNotificacion(mensaje, tipo = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${tipo} border-0 position-fixed bottom-0 end-0 m-3`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${mensaje}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    // Función para manejar el cierre del modal
    function cerrarModal(modalId) {
        const modal = document.getElementById(modalId);
        const modalInstance = bootstrap.Modal.getInstance(modal);
        
        // Remover aria-hidden antes de cerrar
        modal.removeAttribute('aria-hidden');
        
        // Cerrar el modal
        modalInstance.hide();
        
        // Restaurar el foco al botón que abrió el modal
        const triggerButton = document.querySelector(`[data-bs-target="#${modalId}"]`);
        if (triggerButton) {
            triggerButton.focus();
        }
    }

    // Manejar el formulario de nueva mesa
    const formNuevaMesa = document.getElementById('formNuevaMesa');
    if (formNuevaMesa) {
        formNuevaMesa.addEventListener('submit', async function (e) {
            e.preventDefault();

            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Error al crear la mesa');
                }

                mostrarNotificacion('Mesa creada exitosamente');
                cerrarModal('nuevaMesaModal');
                setTimeout(() => window.location.reload(), 1000);
            } catch (error) {
                mostrarNotificacion(error.message, 'danger');
            }
        });
    }

    // Manejar el formulario de editar mesa
    const formEditarMesa = document.getElementById('formEditarMesa');
    if (formEditarMesa) {
        formEditarMesa.addEventListener('submit', async function (e) {
            e.preventDefault();

            try {
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Error al actualizar la mesa');
                }

                mostrarNotificacion('Mesa actualizada exitosamente');
                cerrarModal('editarMesaModal');
                setTimeout(() => window.location.reload(), 1000);
            } catch (error) {
                mostrarNotificacion(error.message, 'danger');
            }
        });
    }

    // Cargar datos de mesa para editar
    const editarMesaModal = document.getElementById('editarMesaModal');
    if (editarMesaModal) {
        editarMesaModal.addEventListener('show.bs.modal', async function (event) {
            const button = event.relatedTarget;
            const mesaId = button.getAttribute('data-mesa');

            try {
                const response = await fetch(`/gerente/mesas/${mesaId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error('Error al cargar los datos de la mesa');
                }

                const data = await response.json();
                const mesa = data.mesa;

                // Actualizar el formulario
                const form = this.querySelector('form');
                form.action = `/gerente/mesas/${mesaId}`;
                form.querySelector('#edit_nombre').value = mesa.nombre;
                form.querySelector('#edit_capacidad').value = mesa.capacidad;
                form.querySelector('#edit_sucursal_id').value = mesa.sucursal_id;
                form.querySelector('#edit_estado_mesa_id').value = mesa.estado_mesa_id;
            } catch (error) {
                mostrarNotificacion(error.message, 'danger');
                cerrarModal('editarMesaModal');
            }
        });
    }

    // Cargar datos de mesa para ver detalles
    const verMesaModal = document.getElementById('verMesaModal');
    if (verMesaModal) {
        verMesaModal.addEventListener('show.bs.modal', async function (event) {
            const button = event.relatedTarget;
            const mesaId = button.getAttribute('data-mesa');

            try {
                const response = await fetch(`/gerente/mesas/${mesaId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error('Error al cargar los datos de la mesa');
                }

                const data = await response.json();
                const mesa = data.mesa;

                if (!mesa) {
                    throw new Error('No se encontraron datos de la mesa');
                }

                // Actualizar el contenido del modal
                document.getElementById('mesa-nombre').textContent = mesa.nombre || 'No especificado';
                document.getElementById('mesa-sucursal').textContent = mesa.sucursal?.nombre || 'No especificada';
                document.getElementById('mesa-capacidad').textContent = `${mesa.capacidad || 0} personas`;
                document.getElementById('mesa-estado').textContent = mesa.estado_mesa?.nombre || 'No especificado';
            } catch (error) {
                mostrarNotificacion(error.message, 'danger');
                cerrarModal('verMesaModal');
            }
        });
    }
});
