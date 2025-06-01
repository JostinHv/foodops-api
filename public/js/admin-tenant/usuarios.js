document.addEventListener('DOMContentLoaded', function () {
    // Manejo del modal de edición
    const editarModal = document.getElementById('modalEditarUsuario');
    editarModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const usuarioId = button.getAttribute('data-usuario');
        const form = this.querySelector('form');
        form.action = `/tenant/usuarios/${usuarioId}`;

        // Cargar datos del usuario
        fetch(`/tenant/usuarios/${usuarioId}`)
            .then(response => response.json())
            .then(data => {
                if (!data.usuario) {
                    throw new Error('No se encontraron datos del usuario');
                }

                const usuario = data.usuario;
                // Llenar campos del formulario
                form.querySelector('[name="nombres"]').value = usuario.nombres || '';
                form.querySelector('[name="apellidos"]').value = usuario.apellidos || '';
                form.querySelector('[name="email"]').value = usuario.email || '';
                form.querySelector('[name="celular"]').value = usuario.celular || '';
                form.querySelector('[name="rol_id"]').value = usuario.roles[0]?.id || '';

                // Información de asignación
                const asignacion = usuario.asignaciones_personal && usuario.asignaciones_personal.length > 0
                    ? usuario.asignaciones_personal[0]
                    : null;


                // Asignación de sucursal si existe
                const sucursalId = asignacion?.sucursal_id;
                form.querySelector('[name="sucursal_id"]').value = sucursalId || '';
                form.querySelector('[name="notas_asignacion"]').value = asignacion?.notas || '';

                // Mostrar información de la sucursal si está seleccionada
                if (sucursalId) {
                    mostrarInfoSucursal(
                        '#edit_sucursal_id',
                        '#edit-info-sucursal',
                        '#edit-sucursal-info-content'
                    );
                }
            })
            .catch(error => {
                console.error('Error al cargar datos del usuario:', error);
                alert('Error al cargar los datos del usuario');
            });
    });

    // Función para cargar datos en el modal de detalles
    const verModal = document.getElementById('verUsuarioModal');
    verModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const usuarioId = button.getAttribute('data-usuario');

        // Cargar detalles del usuario
        fetch(`/tenant/usuarios/${usuarioId}`)
            .then(response => response.json())
            .then(data => {
                const usuario = data.usuario;

                // Actualizar contenido del modal
                document.getElementById('usuario-nombre').innerHTML =
                    `<strong>Nombre:</strong> ${usuario.nombres} ${usuario.apellidos}`;
                document.getElementById('usuario-email').innerHTML =
                    `<strong>Email:</strong> ${usuario.email}`;
                document.getElementById('usuario-celular').innerHTML =
                    `<strong>Celular:</strong> ${usuario.celular || 'No especificado'}`;
                document.getElementById('usuario-restaurante').innerHTML =
                    `<strong>Restaurante:</strong> ${usuario.restaurante?.nombre_legal || 'No asignado'}`;
                document.getElementById('usuario-rol').innerHTML =
                    `<strong>Rol:</strong> ${usuario.roles[0]?.nombre || 'No asignado'}`;

                console.log('Datos del usuario:', usuario); // Para debug
                // Información de asignación
                const asignacion = usuario.asignaciones_personal && usuario.asignaciones_personal.length > 0
                    ? usuario.asignaciones_personal[0]
                    : null;
                console.log('Asignación encontrada:', asignacion); // Para debug
                // Información de asignación
                document.getElementById('usuario-sucursal').innerHTML =
                    `<strong>Sucursal:</strong> ${asignacion?.sucursal?.nombre || 'No asignada'}`;
                document.getElementById('usuario-tipo-asignacion').innerHTML =
                    `<strong>Tipo de Asignación:</strong> ${asignacion?.tipo || 'No especificado'}`;
                document.getElementById('usuario-notas-asignacion').innerHTML =
                    `<strong>Notas:</strong> ${asignacion?.notas || 'Sin notas'}`;

                // Estado y último acceso
                const estadoBadge = usuario.activo ?
                    '<span class="badge bg-success">Activo</span>' :
                    '<span class="badge bg-danger">Inactivo</span>';
                document.getElementById('usuario-estado').innerHTML = estadoBadge;

                const ultimoAcceso = usuario.ultimo_acceso ?
                    new Date(usuario.ultimo_acceso).toLocaleString() :
                    'Nunca ha iniciado sesión';
                document.getElementById('usuario-ultimo-acceso').innerHTML =
                    `<strong>Último acceso:</strong> ${ultimoAcceso}`;
            })
            .catch(error => {
                console.error('Error al cargar detalles del usuario:', error);
                alert('Error al cargar los detalles del usuario');
            });
    });

    // Función para mostrar información de sucursal
    function mostrarInfoSucursal(sucursalId, containerSelector) {
        const selectElement = document.querySelector(sucursalId);
        const infoContainer = document.querySelector(containerSelector);

        if (!selectElement || !infoContainer) return;

        const selectedOption = selectElement.options[selectElement.selectedIndex];
        if (!selectedOption || !selectedOption.value) {
            infoContainer.style.display = 'none';
            return;
        }

        const restaurante = selectedOption.dataset.restaurante;
        const direccion = selectedOption.dataset.direccion;
        const capacidad = selectedOption.dataset.capacidad;

        const infoContent = infoContainer.querySelector('[id$="-sucursal-info-content"]');
        if (infoContent) {
            infoContent.innerHTML = `
                <p class="mb-1"><strong>Restaurante:</strong> ${restaurante || 'No especificado'}</p>
                <p class="mb-1"><strong>Dirección:</strong> ${direccion || 'No especificada'}</p>
                <p class="mb-0"><strong>Capacidad:</strong> ${capacidad ? `${capacidad} personas` : 'No especificada'}</p>
            `;
        }

        infoContainer.style.display = 'block';
    }

    // Evento para el select de sucursal al crear
    const sucursalSelect = document.getElementById('sucursal_id');
    if (sucursalSelect) {
        sucursalSelect.addEventListener('change', function () {
            const sucursalId = this.value;
            const infoContainer = document.getElementById('info-sucursal');
            const infoContent = document.getElementById('sucursal-info-content');

            if (sucursalId) {
                const selectedOption = this.options[this.selectedIndex];
                const restaurante = selectedOption.getAttribute('data-restaurante');
                const direccion = selectedOption.getAttribute('data-direccion');
                const capacidad = selectedOption.getAttribute('data-capacidad');

                infoContainer.style.display = 'block';
                infoContent.innerHTML = `
                    <p class="mb-1"><strong>Restaurante:</strong> ${restaurante || 'No especificado'}</p>
                    <p class="mb-1"><strong>Dirección:</strong> ${direccion || 'No especificada'}</p>
                    <p class="mb-0"><strong>Capacidad:</strong> ${capacidad ? `${capacidad} personas` : 'No especificada'}</p>
                `;
            } else {
                infoContainer.style.display = 'none';
            }
        });
    }

    // Evento para el select de sucursal al editar
    const editSucursalSelect = document.getElementById('edit_sucursal_id');
    if (editSucursalSelect) {
        editSucursalSelect.addEventListener('change', function () {
            const sucursalId = this.value;
            const infoContainer = document.getElementById('edit-info-sucursal');
            const infoContent = document.getElementById('edit-sucursal-info-content');

            if (sucursalId) {
                const selectedOption = this.options[this.selectedIndex];
                const restaurante = selectedOption.getAttribute('data-restaurante');
                const direccion = selectedOption.getAttribute('data-direccion');
                const capacidad = selectedOption.getAttribute('data-capacidad');

                infoContainer.style.display = 'block';
                infoContent.innerHTML = `
                     <p class="mb-1"><strong>Restaurante:</strong> ${restaurante || 'No especificado'}</p>
                    <p class="mb-1"><strong>Dirección:</strong> ${direccion || 'No especificada'}</p>
                    <p class="mb-0"><strong>Capacidad:</strong> ${capacidad ? `${capacidad} personas` : 'No especificada'}</p>
                `;
            } else {
                infoContainer.style.display = 'none';
            }
        });
    }
});
