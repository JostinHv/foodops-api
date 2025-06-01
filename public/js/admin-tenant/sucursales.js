document.addEventListener('DOMContentLoaded', function () {
    // Función para cargar los datos de la sucursal en el modal de edición
    const editarModal = document.getElementById('editarSucursalModal');
    editarModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const sucursalId = button.getAttribute('data-sucursal');
        const form = this.querySelector('form');
        form.action = `/tenant/sucursales/${sucursalId}`;

        // Cargar datos de la sucursal
        fetch(`/tenant/sucursales/${sucursalId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (!data.sucursal) {
                    throw new Error('No se encontraron datos de la sucursal');
                }

                const sucursal = data.sucursal;

                // Actualizar los campos con los datos de la sucursal
                const campos = {
                    'restaurante_id': sucursal.restaurante_id || '',
                    'usuario_id': sucursal.usuario_id || '',
                    'nombre': sucursal.nombre || '',
                    'tipo': sucursal.tipo || '',
                    'latitud': sucursal.latitud || '',
                    'longitud': sucursal.longitud || '',
                    'direccion': sucursal.direccion || '',
                    'telefono': sucursal.telefono || '',
                    'email': sucursal.email || '',
                    'capacidad_total': sucursal.capacidad_total || '',
                    'hora_apertura': sucursal.hora_apertura ? new Date(sucursal.hora_apertura).toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    }) : '',
                    'hora_cierre': sucursal.hora_cierre ? new Date(sucursal.hora_cierre).toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    }) : ''
                };

                // Llenar cada campo del formulario
                Object.entries(campos).forEach(([name, value]) => {
                    const input = form.querySelector(`[name="${name}"]`);
                    if (input) {
                        input.value = value;
                    }
                });
            })
            .catch(error => {
                console.error('Error al cargar los datos de la sucursal:', error);
                alert('Error al cargar los datos de la sucursal: ' + error.message);
            });
    });

    // Función para cargar los detalles de la sucursal en el modal de visualización
    const verModal = document.getElementById('verSucursalModal');
    verModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const sucursalId = button.getAttribute('data-sucursal');

        // Cargar datos de la sucursal
        fetch(`/tenant/sucursales/${sucursalId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (!data.sucursal) {
                    throw new Error('No se encontraron datos de la sucursal');
                }

                const sucursal = data.sucursal;

                // Actualizar los campos con los datos de la sucursal
                const elementos = {
                    'sucursal-nombre': sucursal.nombre || 'No especificado',
                    'sucursal-restaurante': sucursal.restaurante?.nombre_legal || 'No especificado',
                    'sucursal-gerente': sucursal.usuario ?
                        `${sucursal.usuario.nombres} ${sucursal.usuario.apellidos} - ${sucursal.usuario.email}` :
                        'No asignado',
                    'sucursal-tipo': sucursal.tipo || 'No especificado',
                    'sucursal-direccion': sucursal.direccion || 'No especificada',
                    'sucursal-telefono': sucursal.telefono || 'No especificado',
                    'sucursal-email': sucursal.email || 'No especificado',
                    'sucursal-capacidad': sucursal.capacidad_total ? `${sucursal.capacidad_total} personas` : 'No especificada',
                    'sucursal-horario': (sucursal.hora_apertura && sucursal.hora_cierre) ?
                        `${new Date(sucursal.hora_apertura).toLocaleTimeString('es-ES', {
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        })} - ${new Date(sucursal.hora_cierre).toLocaleTimeString('es-ES', {
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        })}`.toUpperCase() :
                        'No especificado',
                };

                // Actualizar cada elemento
                Object.entries(elementos).forEach(([id, value]) => {
                    const elemento = document.getElementById(id);
                    if (elemento) {
                        elemento.textContent = value;
                    }
                });

                // Actualizar el estado con el color correspondiente
                const estadoElement = document.getElementById('sucursal-estado');
                if (estadoElement) {
                    estadoElement.className = `badge ${sucursal.activo ? 'bg-success' : 'bg-warning'}`;
                    estadoElement.textContent = sucursal.activo ? 'Activo' : 'Inactivo';
                }

                // Actualizar la ubicación
                const ubicacionElement = document.getElementById('sucursal-ubicacion');
                if (ubicacionElement) {
                    ubicacionElement.textContent = (sucursal.latitud && sucursal.longitud) ?
                        `${sucursal.latitud}, ${sucursal.longitud}` :
                        'No especificada';
                }
            })
            .catch(error => {
                console.error('Error al cargar los detalles de la sucursal:', error);
                alert('Error al cargar los detalles de la sucursal: ' + error.message);
            });
    });
});
