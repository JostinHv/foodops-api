document.addEventListener('DOMContentLoaded', function () {
    // Función para mostrar notificaciones
    function mostrarNotificacion(mensaje, tipo = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${tipo} border-0`;
        toast.style.position = 'fixed';
        toast.style.top = '20px';
        toast.style.right = '20px';
        toast.style.zIndex = '1050';
        toast.style.minWidth = '300px';
        toast.style.maxWidth = '350px';
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
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 3000
        });
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

    // Verificar email único
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', async function() {
            try {
                const response = await fetch('/gerente/personal/check-email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: this.value })
                });

                const data = await response.json();
                if (data.exists) {
                    this.setCustomValidity('Este email ya está registrado');
                    mostrarNotificacion('Este email ya está registrado', 'danger');
                } else {
                    this.setCustomValidity('');
                }
            } catch (error) {
                console.error('Error al verificar email:', error);
            }
        });
    }

    // Manejar el formulario de nuevo personal
    const formNuevoPersonal = document.getElementById('formNuevoPersonal');
    if (formNuevoPersonal) {
        formNuevoPersonal.addEventListener('submit', async function (e) {
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
                    throw new Error(data.message || 'Error al crear el personal');
                }

                mostrarNotificacion('Personal creado exitosamente');
                cerrarModal('nuevoPersonalModal');
                setTimeout(() => window.location.reload(), 1000);
            } catch (error) {
                mostrarNotificacion(error.message, 'danger');
            }
        });
    }

    // Manejar el formulario de editar personal
    const formEditarPersonal = document.getElementById('formEditarPersonal');
    if (formEditarPersonal) {
        formEditarPersonal.addEventListener('submit', async function (e) {
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
                    throw new Error(data.message || 'Error al actualizar el personal');
                }

                mostrarNotificacion('Personal actualizado exitosamente');
                cerrarModal('editarPersonalModal');
                setTimeout(() => window.location.reload(), 1000);
            } catch (error) {
                mostrarNotificacion(error.message, 'danger');
            }
        });
    }

    // Cargar datos de personal para editar
    const editarPersonalModal = document.getElementById('editarPersonalModal');
    if (editarPersonalModal) {
        editarPersonalModal.addEventListener('show.bs.modal', async function (event) {
            const button = event.relatedTarget;
            const personalId = button.getAttribute('data-personal');

            try {
                const response = await fetch(`/gerente/personal/${personalId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error('Error al cargar los datos del personal');
                }

                const data = await response.json();
                const { usuario, asignacion } = data;

                // Actualizar el formulario
                const form = this.querySelector('form');
                form.action = `/gerente/personal/${personalId}`;
                form.querySelector('#edit_nombres').value = usuario.nombres;
                form.querySelector('#edit_apellidos').value = usuario.apellidos;
                form.querySelector('#edit_celular').value = usuario.celular;
                form.querySelector('#edit_sucursal_id').value = asignacion?.sucursal_id;
                form.querySelector('#edit_tipo').value = asignacion?.tipo;
            } catch (error) {
                mostrarNotificacion(error.message, 'danger');
                cerrarModal('editarPersonalModal');
            }
        });
    }

    // Cargar datos de personal para ver detalles
    const verPersonalModal = document.getElementById('verPersonalModal');
    if (verPersonalModal) {
        verPersonalModal.addEventListener('show.bs.modal', async function (event) {
            const button = event.relatedTarget;
            const personalId = button.getAttribute('data-personal');

            try {
                const response = await fetch(`/gerente/personal/${personalId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error('Error al cargar los datos del personal');
                }

                const data = await response.json();
                const { usuario, asignacion } = data;

                if (!usuario) {
                    throw new Error('No se encontraron datos del personal');
                }

                // Actualizar el contenido del modal
                document.getElementById('personal-nombre').textContent = `${usuario.nombres} ${usuario.apellidos}`;
                document.getElementById('personal-email').textContent = usuario.email;
                document.getElementById('personal-celular').textContent = usuario.celular;
                document.getElementById('personal-sucursal').textContent = asignacion?.sucursal?.nombre || 'No asignada';
                document.getElementById('personal-tipo').textContent = asignacion?.tipo || 'No especificado';
                document.getElementById('personal-estado').textContent = usuario.activo ? 'Activo' : 'Inactivo';
            } catch (error) {
                mostrarNotificacion(error.message, 'danger');
                cerrarModal('verPersonalModal');
            }
        });
    }

    // Manejar cambio de estado (activar/desactivar)
    const toggleButtons = document.querySelectorAll('[data-action="toggle-activo"]');
    toggleButtons.forEach(button => {
        button.addEventListener('click', async function() {
            const personalId = this.getAttribute('data-personal');
            
            try {
                const response = await fetch(`/gerente/personal/${personalId}/toggle-activo`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Error al cambiar el estado del personal');
                }

                mostrarNotificacion('Estado del personal actualizado exitosamente');
                setTimeout(() => window.location.reload(), 1000);
            } catch (error) {
                mostrarNotificacion(error.message, 'danger');
            }
        });
    });
}); 