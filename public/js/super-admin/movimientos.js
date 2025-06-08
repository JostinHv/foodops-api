document.addEventListener('DOMContentLoaded', function () {
    // Función para mostrar notificaciones toast
    function mostrarNotificacion(mensaje, tipo = 'success') {
        const toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            // Crear el contenedor de toasts si no existe
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.style.position = 'fixed';
            container.style.top = '20px';
            container.style.right = '20px';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${tipo} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi ${tipo === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle'} me-2"></i>
                    ${mensaje}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        document.getElementById('toast-container').appendChild(toast);
        const bsToast = new bootstrap.Toast(toast, {
            animation: true,
            autohide: true,
            delay: 3000
        });
        bsToast.show();

        // Eliminar el toast del DOM después de que se oculte
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    // Verificar si Bootstrap está disponible
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap no está disponible');
        mostrarNotificacion('Error: Bootstrap no está cargado', 'danger');
        return;
    }

    // Función para formatear fechas
    function formatearFecha(fecha) {
        if (!fecha) return 'N/A';
        return new Date(fecha).toLocaleString('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Función para crear badges de roles
    function crearBadgesRoles(roles) {
        if (!roles || roles.length === 0) return '<span class="text-muted">Sin roles asignados</span>';
        
        return roles.map(rol => `
            <span class="badge bg-info text-dark me-2 mb-2">
                <i class="bi bi-shield me-1"></i>${rol.nombre}
            </span>
        `).join('');
    }

    // Función para manejar el modal de usuario
    const usuarioDetailModal = document.getElementById('verUsuarioDetalle');
    if (usuarioDetailModal) {
        usuarioDetailModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) {
                console.error('No se encontró el botón que activó el modal');
                return;
            }

            const usuarioId = button.getAttribute('data-usuario-id');
            if (!usuarioId) {
                console.error('No se encontró el ID del usuario');
                mostrarNotificacion('Error: ID de usuario no encontrado', 'danger');
                return;
            }

            // Elementos del modal
            const modalBodyLoading = document.getElementById('usuario-detail-loading');
            const modalBodyContent = document.getElementById('usuario-detail-content');
            const modalBodyError = document.getElementById('usuario-detail-error');

            // Verificar que existen los elementos necesarios
            if (!modalBodyLoading || !modalBodyContent || !modalBodyError) {
                console.error('No se encontraron todos los elementos necesarios del modal');
                mostrarNotificacion('Error: Elementos del modal no encontrados', 'danger');
                return;
            }

            // Resetear estado del modal
            modalBodyLoading.classList.remove('d-none');
            modalBodyContent.classList.add('d-none');
            modalBodyError.classList.add('d-none');

            // Cargar datos del usuario
            fetch(`/superadmin/movimientos/usuario/${usuarioId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error ${response.status}: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.usuario) {
                        throw new Error('No se encontraron datos del usuario');
                    }

                    const usuario = data.usuario;

                    // Actualizar información principal
                    document.getElementById('user-detail-nombre').textContent = 
                        `${usuario.nombres || ''} ${usuario.apellidos || ''}`.trim() || 'N/A';
                    document.getElementById('user-detail-email').textContent = usuario.email || 'N/A';
                    
                    // Actualizar estado
                    const estadoElement = document.getElementById('user-detail-activo');
                    estadoElement.className = `badge ${usuario.activo ? 'bg-success' : 'bg-warning'}`;
                    estadoElement.textContent = usuario.activo ? 'Activo' : 'Inactivo';

                    // Actualizar información personal
                    document.getElementById('user-detail-nombre-completo').textContent = 
                        `${usuario.nombres || ''} ${usuario.apellidos || ''}`.trim() || 'N/A';
                    document.getElementById('user-detail-email-detalle').textContent = usuario.email || 'N/A';
                    document.getElementById('user-detail-celular').textContent = usuario.celular || 'N/A';

                    // Actualizar información del tenant
                    document.getElementById('user-detail-tenant').textContent = 
                        usuario.tenant?.nombre || 'N/A';
                    document.getElementById('user-detail-tenant-dominio').textContent = 
                        usuario.tenant?.dominio || 'N/A';

                    // Actualizar información del restaurante
                    document.getElementById('user-detail-restaurante').textContent = 
                        usuario.restaurante?.nombre_legal || 'N/A';
                    document.getElementById('user-detail-restaurante-ruc').textContent = 
                        usuario.restaurante?.nro_ruc || 'N/A';

                    // Actualizar roles
                    document.getElementById('user-detail-roles').innerHTML = crearBadgesRoles(usuario.roles);

                    // Actualizar información de auditoría
                    document.getElementById('user-detail-created-at').textContent = formatearFecha(usuario.created_at);
                    document.getElementById('user-detail-updated-at').textContent = formatearFecha(usuario.updated_at);
                    document.getElementById('user-detail-ultimo-acceso').textContent = formatearFecha(usuario.ultimo_acceso);

                    // Mostrar contenido y ocultar loading
                    modalBodyLoading.classList.add('d-none');
                    modalBodyContent.classList.remove('d-none');
                })
                .catch(error => {
                    console.error('Error al cargar los detalles del usuario:', error);

                    // Mostrar error en el modal
                    const errorElement = document.getElementById('usuario-detail-error-message');
                    if (errorElement) {
                        errorElement.textContent = error.message;
                    }

                    modalBodyLoading.classList.add('d-none');
                    modalBodyError.classList.remove('d-none');

                    // También mostrar notificación
                    mostrarNotificacion('Error al cargar los detalles del usuario: ' + error.message, 'danger');
                });
        });
    } else {
        console.error('Modal verUsuarioDetalle no encontrado');
    }
});
