document.addEventListener('DOMContentLoaded', function () {
    // Elementos del DOM
    const listaOrdenes = document.getElementById('lista-ordenes');
    const buscarInput = document.getElementById('buscarOrden');
    const filtroEstado = document.getElementById('filtroEstado');
    const ordenarSelect = document.getElementById('ordenarPor');
    const filtroTiempo = document.getElementById('filtroTiempo');
    const filtroFecha = document.getElementById('filtroFecha');
    const detalleModal = document.getElementById('detalleOrdenModal');
    const formCambiarEstado = document.getElementById('formCambiarEstado');

    // Cache para almacenar los detalles de las órdenes
    const ordenCache = new Map();
    const CACHE_DURATION = 60000; // 1 minuto en milisegundos

    // Configuración de Pusher
    const pusherKey = document.querySelector('meta[name="pusher-app-key"]').content;
    const pusherCluster = document.querySelector('meta[name="pusher-app-cluster"]').content;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const jwtToken = localStorage.getItem('jwt_token');

    console.log('Configuración de Pusher:', {
        key: pusherKey,
        cluster: pusherCluster,
        hasJwtToken: !!jwtToken
    });

    const pusher = new Pusher(pusherKey, {
        cluster: pusherCluster,
        authEndpoint: "/broadcasting/auth",
        auth: {
            headers: {
                'X-CSRF-Token': csrfToken,
            }
        },
        logToConsole: true
    });
    // pusher.signin();

    console.log('Pusher inicializado');

    // Obtener el tenant_id y sucursal_id del usuario actual
    const tenantId = document.querySelector('meta[name="tenant-id"]').content;
    const sucursalId = document.querySelector('meta[name="sucursal-id"]').content;

    console.log('Datos del usuario:', {
        tenantId: tenantId,
        sucursalId: sucursalId
    });

    // Suscribirse al canal de órdenes
    const channelName = `private-tenant.${tenantId}.sucursal.${sucursalId}.ordenes`;
    console.log('Intentando suscribirse al canal:', channelName);

    const channel = pusher.subscribe(channelName);

    // Logs de conexión de Pusher
    pusher.connection.bind('connected', function () {
        console.log('✅ Pusher conectado exitosamente');
        console.log('Estado de conexión:', pusher.connection.state);
    });

    pusher.connection.bind('error', function (err) {
        console.error('❌ Error de conexión Pusher:', err);
        // Notificar error de conexión
        if (window.notificationService) {
            window.notificationService.handleError('Error de conexión con Pusher', 'Conexión');
        }
    });

    channel.bind('pusher:subscription_succeeded', function () {
        console.log('✅ Suscripción exitosa al canal:', channelName);
        console.log('Canal suscrito:', channel.name);
    });

    channel.bind('pusher:subscription_error', function (status) {
        console.error('❌ Error de suscripción:', status);
        console.error('Detalles del error:', {
            status: status.status,
            data: status.data
        });
        // Notificar error de suscripción
        if (window.notificationService) {
            window.notificationService.handleError('Error al suscribirse al canal de órdenes', 'Suscripción');
        }
    });

    // Función para obtener el color del badge según el estado
    function obtenerColorEstado(estadoNombre) {
        // Mapa de colores basado en el helper EstadoOrdenHelper
        const colores = {
            'Pendiente': 'danger',
            'En Proceso': 'warning',
            'Preparada': 'info',
            'Servida': 'primary',
            'Solicitando Pago': 'primary',
            'Pagada': 'success',
            'Cancelada': 'danger',
            'En disputa': 'danger',
            'Cerrada': 'secondary'
        };
        return colores[estadoNombre] || 'secondary';
    }

    // Función para obtener la clase CSS según el estado
    function obtenerClaseOrden(estadoNombre, tiempoMinutos) {
        let claseOrden = '';
        let claseTiempo = '';

        if (estadoNombre === 'Pendiente') {
            claseOrden = 'orden-pendiente';
            claseTiempo = tiempoMinutos > 10 ? 'tiempo-urgente' : 'tiempo-normal';
        } else if (estadoNombre === 'En Proceso') {
            claseOrden = 'orden-proceso';
            claseTiempo = tiempoMinutos > 15 ? 'tiempo-urgente' : 'tiempo-normal';
        } else if (estadoNombre === 'Preparada') {
            claseOrden = 'orden-preparada';
            claseTiempo = 'tiempo-normal';
        }

        return { claseOrden, claseTiempo };
    }

    // Función para generar el HTML de una tarjeta de orden
    function generarTarjetaOrden(orden) {
        const { claseOrden, claseTiempo } = obtenerClaseOrden(orden.estado_orden.nombre, orden.tiempo_transcurrido.minutos || 0);
        
        let botonesAccion = '';
        if (orden.estado_orden.nombre === 'Pendiente') {
            botonesAccion = `
                <button class="btn btn-warning btn-sm" onclick="cambiarEstadoRapido(${orden.id}, 'En Proceso', this)">
                    <i class="bi bi-play me-1"></i>Iniciar
                </button>
            `;
        } else if (orden.estado_orden.nombre === 'En Proceso') {
            botonesAccion = `
                <button class="btn btn-info btn-sm" onclick="cambiarEstadoRapido(${orden.id}, 'Preparada', this)">
                    <i class="bi bi-check me-1"></i>Lista
                </button>
            `;
        }

        return `
            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 orden-card ${claseOrden}"
                     data-orden-id="${orden.id}"
                     data-estado-id="${orden.estado_orden_id}"
                     data-estado-nombre="${orden.estado_orden.nombre}"
                     data-fecha="${orden.created_at}"
                     data-tiempo="${orden.tiempo_transcurrido.minutos || 0}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">Orden #${orden.nro_orden}</h5>
                                <p class="text-muted small mb-0 ${claseTiempo}">
                                    <i class="bi bi-clock me-1"></i>
                                    ${orden.tiempo_transcurrido.humano}
                                    ${orden.tiempo_transcurrido.es_hoy ? `(${orden.tiempo_transcurrido.minutos} min)` : ''}
                                </p>
                            </div>
                            <span class="badge bg-${obtenerColorEstado(orden.estado_orden.nombre)}">
                                ${orden.estado_orden.nombre}
                            </span>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-table me-2 text-primary"></i>
                                <span>Mesa ${orden.mesa.nombre}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-person me-2 text-primary"></i>
                                <span>${orden.nombre_cliente || 'Sin especificar'}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-cart me-2 text-primary"></i>
                                <span>${orden.items_ordenes.length} productos</span>
                            </div>
                        </div>

                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Total: S/. ${parseFloat(orden.items_ordenes.reduce((sum, item) => sum + parseFloat(item.monto), 0)).toFixed(2)}</h6>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-outline-primary btn-sm ver-detalles"
                                            data-orden-id="${orden.id}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detalleOrdenModal">
                                        <i class="bi bi-eye me-1"></i>Ver
                                    </button>
                                    ${botonesAccion}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Función para cambio de estado rápido (sin modal)
    window.cambiarEstadoRapido = function(ordenId, nuevoEstado, buttonElement) {
        // Prevenir spam: deshabilitar botón inmediatamente
        if (buttonElement) {
            const originalText = buttonElement.innerHTML;
            buttonElement.disabled = true;
            buttonElement.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                Actualizando...
            `;
        }

        const estadosMap = {
            'En Proceso': 1,
            'Preparada': 2,
            'Servida': 3,
            'Solicitando Pago': 4,
            'Pagada': 5,
            'Cancelada': 6,
            'En disputa': 7,
            'Cerrada': 8,
            'Pendiente': 9,
        };

        const estadoId = estadosMap[nuevoEstado];
        if (!estadoId) {
            console.error('Estado no válido:', nuevoEstado);
            // Restaurar botón en caso de error
            if (buttonElement) {
                buttonElement.disabled = false;
                buttonElement.innerHTML = originalText;
            }
            return;
        }

        fetch(`/cocinero/ordenes/${ordenId}/cambiar-estado`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                estado_orden_id: estadoId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Limpiar caché para esta orden
                ordenCache.delete(ordenId);
                // Actualizar la lista de órdenes
                actualizarOrdenes(document.getElementById('ordenarPor').value);
                // Mostrar mensaje de éxito
                if (window.notificationService) {
                    window.notificationService.handleEstadoActualizado(data);
                }
            } else {
                throw new Error(data.message || 'Error al actualizar el estado');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (window.notificationService) {
                window.notificationService.handleError(error.message, 'Actualización de Estado');
            }
        })
        .finally(() => {
            // Restaurar botón después de completar (éxito o error)
            if (buttonElement) {
                buttonElement.disabled = false;
                buttonElement.innerHTML = originalText;
            }
        });
    };

    // Función para filtrar por estado (accesos rápidos)
    window.filtrarPorEstado = function(estadoNombre) {
        if (filtroEstado) {
            filtroEstado.value = estadoNombre;
            console.log('Filtrando por estado:', estadoNombre);
            // Actualizar desde el servidor para obtener datos frescos
            actualizarOrdenes(document.getElementById('ordenarPor').value);
        }
    };

    // Función para limpiar el contenido del modal
    function limpiarModal() {
        const infoGeneral = document.getElementById('orden-info-general');
        const estadoTiempo = document.getElementById('orden-estado-tiempo');
        const tbody = document.querySelector('#tabla-productos tbody');
        const totalElement = document.getElementById('orden-total');

        if (infoGeneral) infoGeneral.innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2 mb-0">Cargando detalles de la orden...</p>
            </div>`;
        if (estadoTiempo) estadoTiempo.innerHTML = '';
        if (tbody) tbody.innerHTML = '';
        if (totalElement) totalElement.textContent = '';
    }

    // Función para cargar los detalles de una orden
    function cargarDetallesOrden(ordenId) {
        // Limpiar el contenido del modal antes de cargar nuevos datos
        limpiarModal();

        // Verificar si tenemos datos en caché y si no han expirado
        const cachedData = ordenCache.get(ordenId);
        if (cachedData && (Date.now() - cachedData.timestamp) < CACHE_DURATION) {
            actualizarModalDetalles(cachedData.data);
            return;
        }

        console.log('Cargando detalles de la orden:', ordenId);
        fetch(`/cocinero/ordenes/${ordenId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data);
                // Guardar en caché con timestamp
                ordenCache.set(ordenId, {
                    data: data,
                    timestamp: Date.now()
                });
                actualizarModalDetalles(data);
            })
            .catch(error => {
                console.error('Error al cargar los detalles:', error);
                // Usar el sistema de notificaciones para mostrar el error
                if (window.notificationService) {
                    window.notificationService.handleError('Error al cargar los detalles de la orden', 'Carga de Datos');
                }
                const modalBody = document.querySelector('#detalleOrdenModal .modal-body');
                if (modalBody) {
                    modalBody.innerHTML = `
                        <div class="alert alert-danger m-3">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Error al cargar los detalles de la orden. Por favor, intente nuevamente.
                        </div>`;
                }
            });
    }

    // Función para actualizar el modal con los detalles
    function actualizarModalDetalles(data) {
        if (!data || !data.orden) {
            console.error('Datos inválidos recibidos:', data);
            return;
        }

        const orden = data.orden;
        const tiempoTranscurrido = data.tiempo_transcurrido;

        // Crear el HTML una sola vez
        const infoGeneralHTML = `
            <p><strong>Orden #${orden.nro_orden}</strong></p>
            <p><i class="bi bi-table me-2"></i>Mesa ${orden.mesa.nombre}</p>
            <p><i class="bi bi-person me-2"></i>${orden.nombre_cliente || 'Sin especificar'}</p>
            <p><i class="bi bi-cart me-2"></i>${orden.items_ordenes.length} productos</p>
        `;

        const estadoTiempoHTML = `
            <p><strong>Estado:</strong> <span class="badge bg-${obtenerColorEstado(orden.estado_orden.nombre)}">${orden.estado_orden.nombre}</span></p>
            <p><strong>Creada:</strong> ${tiempoTranscurrido.humano}${tiempoTranscurrido.es_hoy ?
            ` (${Math.round(tiempoTranscurrido.minutos)} min)` : ''}</p>
        `;

        // Actualizar información general y estado
        const infoGeneral = document.getElementById('orden-info-general');
        const estadoTiempo = document.getElementById('orden-estado-tiempo');
        if (infoGeneral) infoGeneral.innerHTML = infoGeneralHTML;
        if (estadoTiempo) estadoTiempo.innerHTML = estadoTiempoHTML;

        // --- BLOQUE CAMBIAR ESTADO ---
        const bloqueCambiarEstado = document.getElementById('bloque-cambiar-estado');
        const msgCambiarEstadoPagada = document.getElementById('msg-cambiar-estado-pagada');
        const formCambiarEstado = document.getElementById('formCambiarEstado');
        const estadoSelect = document.getElementById('estado_orden_id');
        const submitButton = formCambiarEstado ? formCambiarEstado.querySelector('button[type="submit"]') : null;

        if (orden.estado_orden.id === 6) { // Pagada
            if (bloqueCambiarEstado) bloqueCambiarEstado.classList.add('position-relative');
            if (formCambiarEstado) formCambiarEstado.classList.add('d-none');
            if (msgCambiarEstadoPagada) msgCambiarEstadoPagada.classList.remove('d-none');
        } else {
            if (formCambiarEstado) formCambiarEstado.classList.remove('d-none');
            if (msgCambiarEstadoPagada) msgCambiarEstadoPagada.classList.add('d-none');
            if (estadoSelect) estadoSelect.disabled = false;
            if (submitButton) submitButton.disabled = false;
        }

        // Actualizar selector de estado si existe y no es pagada
        if (estadoSelect && orden.estado_orden.id !== 6) {
            estadoSelect.value = orden.estado_orden.id;
        }

        // Actualizar tabla de productos
        const tbody = document.querySelector('#tabla-productos tbody');
        if (tbody && orden.items_ordenes) {
            // Crear el HTML de la tabla una sola vez
            const tableHTML = orden.items_ordenes.map(item => `
                <tr>
                    <td>${item.item_menu.nombre}</td>
                    <td class="text-center">${item.cantidad}</td>
                    <td class="text-end">S/. ${parseFloat(item.item_menu.precio).toFixed(2)}</td>
                    <td class="text-end">S/. ${parseFloat(item.monto).toFixed(2)}</td>
                </tr>
            `).join('');

            tbody.innerHTML = tableHTML;

            // Actualizar total
            const total = orden.items_ordenes.reduce((sum, item) => sum + parseFloat(item.monto), 0);
            const totalElement = document.getElementById('orden-total');
            if (totalElement) {
                totalElement.textContent = `S/. ${total.toFixed(2)}`;
            }
        }

        // Actualizar formulario de cambio de estado (solo una vez)
        if (formCambiarEstado) {
            formCambiarEstado.setAttribute('action', `/cocinero/ordenes/${orden.id}/cambiar-estado`);
        }

        // Actualizar formulario de marcar como servida
        const formMarcarServida = document.getElementById('formMarcarServida');
        if (formMarcarServida) {
            formMarcarServida.setAttribute('action', `/cocinero/ordenes/${orden.id}/marcar-servida`);
        }
    }

    // Evento para cuando el modal se muestra
    if (detalleModal) {
        detalleModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (button) {
                const ordenId = button.dataset.ordenId;
                cargarDetallesOrden(ordenId);
            }
        });
    }

    // Evento para el formulario de cambio de estado
    if (formCambiarEstado) {
        const estadoSelect = formCambiarEstado.querySelector('#estado_orden_id');
        const submitButton = formCambiarEstado.querySelector('button[type="submit"]');

        // Función para actualizar el color del botón según el estado seleccionado
        function actualizarColorBoton() {
            const selectedOption = estadoSelect.options[estadoSelect.selectedIndex];
            const color = selectedOption.dataset.color;
            submitButton.className = `btn btn-${color}`;
        }

        // Actualizar color al cargar y cuando cambie la selección
        estadoSelect.addEventListener('change', actualizarColorBoton);

        formCambiarEstado.addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;
            // Obtener el ID de la orden de la URL del formulario
            const ordenId = form.action.split('/ordenes/')[1].split('/')[0];
            const estadoId = form.estado_orden_id.value;

            // Deshabilitar el botón durante el envío
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                Actualizando...
            `;

            fetch(`/cocinero/ordenes/${ordenId}/cambiar-estado`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    estado_orden_id: estadoId
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Limpiar caché para esta orden
                        ordenCache.delete(ordenId);
                        // Recargar los detalles
                        cargarDetallesOrden(ordenId);
                        // Actualizar la lista de órdenes
                        actualizarOrdenes(document.getElementById('ordenarPor').value);
                        // Mostrar mensaje de éxito usando el sistema de notificaciones
                        if (window.notificationService) {
                            window.notificationService.handleEstadoActualizado(data);
                        }
                    } else {
                        throw new Error(data.message || 'Error al actualizar el estado');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Mostrar error usando el sistema de notificaciones
                    if (window.notificationService) {
                        window.notificationService.handleError(error.message, 'Actualización de Estado');
                    }
                })
                .finally(() => {
                    // Restaurar el botón
                    submitButton.disabled = false;
                    submitButton.innerHTML = `<i class="bi bi-check-circle me-1"></i>Actualizar`;
                    actualizarColorBoton();
                });
        });

        // Inicializar el color del botón
        actualizarColorBoton();
    }

    // Evento para el formulario de marcar como servida
    const formMarcarServida = document.getElementById('formMarcarServida');
    if (formMarcarServida) {
        formMarcarServida.addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;
            const ordenId = form.action.split('/ordenes/')[1].split('/')[0];

            fetch(`/cocinero/ordenes/${ordenId}/marcar-servida`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    // Si la respuesta es una redirección, recargamos la página
                    if (response.redirected) {
                        window.location.href = response.url;
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data) {
                        // Limpiar caché para esta orden
                        ordenCache.delete(ordenId);
                        // Cerrar el modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('detalleOrdenModal'));
                        if (modal) {
                            modal.hide();
                        }
                        // Actualizar la lista de órdenes
                        actualizarOrdenes(document.getElementById('ordenarPor').value);
                        // Mostrar mensaje de éxito usando el sistema de notificaciones
                        if (window.notificationService) {
                            window.notificationService.handleNotification('orden.servida', data);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Mostrar error usando el sistema de notificaciones
                    if (window.notificationService) {
                        window.notificationService.handleError(error.message, 'Marcar como Servida');
                    }
                });
        });
    }

    // Función para actualizar órdenes
    function actualizarOrdenes(criterio) {
        console.log('Función actualizarOrdenes llamada con criterio:', criterio);
        const fechaSeleccionada = filtroFecha ? filtroFecha.value : new Date().toISOString().slice(0, 10);
        const estadoSeleccionado = filtroEstado ? filtroEstado.value : '';
        
        fetch('/cocinero/ordenes/ordenar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                criterio: criterio,
                fecha: fechaSeleccionada,
                estado: estadoSeleccionado
            })
        })
            .then(response => {
                console.log('Respuesta del servidor:', response.status, response.statusText);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos del servidor:', data);
                if (data.success) {
                    console.log('Actualizando lista de órdenes con', data.ordenes.length, 'órdenes');
                    listaOrdenes.innerHTML = '';
                    data.ordenes.forEach(orden => {
                        listaOrdenes.innerHTML += generarTarjetaOrden(orden);
                    });
                    // No reasignar eventos a .ver-detalles aquí
                    console.log('Lista de órdenes actualizada exitosamente');
                } else {
                    console.error('Error en la respuesta:', data);
                    // Mostrar error usando el sistema de notificaciones
                    if (window.notificationService) {
                        window.notificationService.handleError(data.message || 'Error desconocido', 'Actualización de Lista');
                    }
                }
            })
            .catch(error => {
                console.error('Error en actualizarOrdenes:', error);
                // Mostrar error usando el sistema de notificaciones
                if (window.notificationService) {
                    window.notificationService.handleError('Error al actualizar la lista de órdenes', 'Actualización');
                }
            });
    }

    // Eventos para los filtros
    if (ordenarSelect) {
        ordenarSelect.addEventListener('change', function () {
            actualizarOrdenes(this.value);
        });
    }

    if (buscarInput) {
        buscarInput.addEventListener('input', filtrarOrdenes);
    }

    if (filtroEstado) {
        filtroEstado.addEventListener('change', filtrarOrdenes);
    }

    if (filtroTiempo) {
        filtroTiempo.addEventListener('change', filtrarOrdenes);
    }

    if (filtroFecha) {
        filtroFecha.addEventListener('change', function() {
            // Cuando cambia la fecha, actualizar desde el servidor
            actualizarOrdenes(document.getElementById('ordenarPor').value);
        });
    }

    // Función para filtrar órdenes
    function filtrarOrdenes() {
        const busqueda = buscarInput.value.toLowerCase();
        const estadoSeleccionado = filtroEstado.value;
        const ordenSeleccionado = ordenarSelect.value;
        const tiempoSeleccionado = filtroTiempo.value;
        const fechaSeleccionada = filtroFecha ? filtroFecha.value : null;

        document.querySelectorAll('.orden-card').forEach(card => {
            // Buscar el texto de mesa y cliente de forma robusta
            let mesa = '';
            let cliente = '';
            const mesaIcon = card.querySelector('.bi-table');
            const clienteIcon = card.querySelector('.bi-person');
            if (mesaIcon && mesaIcon.parentElement) {
                mesa = mesaIcon.parentElement.textContent.replace(/Mesa/i, '').trim().toLowerCase();
            }
            if (clienteIcon && clienteIcon.parentElement) {
                cliente = clienteIcon.parentElement.textContent.replace(/Sin especificar/i, '').trim().toLowerCase();
            }
            const estadoId = card.dataset.estadoId;
            const estadoNombre = card.dataset.estadoNombre;
            const tiempo = parseInt(card.dataset.tiempo) || 0;
            // Manejar la fecha de forma segura
            let fecha;
            try {
                fecha = new Date(card.dataset.fecha);
                if (isNaN(fecha.getTime())) {
                    console.warn('Fecha inválida en card:', card.dataset.fecha);
                    fecha = new Date(); // Fallback a fecha actual
                }
            } catch (error) {
                console.warn('Error al parsear fecha:', card.dataset.fecha, error);
                fecha = new Date(); // Fallback a fecha actual
            }

            const coincideBusqueda = mesa.includes(busqueda) || cliente.includes(busqueda);
            const coincideEstado = !estadoSeleccionado || estadoNombre === estadoSeleccionado;
            const coincideTiempo = !tiempoSeleccionado || tiempo >= parseInt(tiempoSeleccionado);
            const coincideFecha = !fechaSeleccionada || fecha.toISOString().slice(0, 10) === fechaSeleccionada;

            card.parentElement.style.display = coincideBusqueda && coincideEstado && coincideTiempo && coincideFecha ? '' : 'none';
        });

        // Ordenar las cards
        const cards = Array.from(document.querySelectorAll('.orden-card'));
        cards.sort((a, b) => {
            // Manejar fechas de forma segura
            let fechaA, fechaB;
            try {
                fechaA = new Date(a.dataset.fecha);
                fechaB = new Date(b.dataset.fecha);
                if (isNaN(fechaA.getTime())) fechaA = new Date();
                if (isNaN(fechaB.getTime())) fechaB = new Date();
            } catch (error) {
                fechaA = new Date();
                fechaB = new Date();
            }
            const tiempoA = parseInt(a.dataset.tiempo) || 0;
            const tiempoB = parseInt(b.dataset.tiempo) || 0;

            switch (ordenSeleccionado) {
                case 'urgente':
                    // Ordenar por urgencia: primero pendientes con más tiempo, luego en proceso
                    const estadoA = a.dataset.estadoNombre;
                    const estadoB = b.dataset.estadoNombre;

                    // Prioridad: Pendiente > En Proceso > Preparada > Otros
                    const prioridadA = estadoA === 'Pendiente' ? 3 : (estadoA === 'En Proceso' ? 2 : (estadoA === 'Preparada' ? 1 : 0));
                    const prioridadB = estadoB === 'Pendiente' ? 3 : (estadoB === 'En Proceso' ? 2 : (estadoB === 'Preparada' ? 1 : 0));

                    if (prioridadA !== prioridadB) {
                        return prioridadB - prioridadA;
                    }

                    // Si tienen la misma prioridad, ordenar por tiempo (más tiempo = más urgente)
                    return tiempoB - tiempoA;
                case 'reciente':
                    return fechaB - fechaA;
                case 'antiguo':
                    return fechaA - fechaB;
                case 'mesa':
                    // Ordenar por nombre de mesa
                    const mesaA = a.querySelector('.bi-table')?.parentElement?.textContent || '';
                    const mesaB = b.querySelector('.bi-table')?.parentElement?.textContent || '';
                    return mesaA.localeCompare(mesaB);
                default:
                    return 0;
            }
        });

        // Reinsertar las cards ordenadas
        const lista = document.getElementById('lista-ordenes');
        cards.forEach(card => lista.appendChild(card.parentElement));
    }

    // Función para limpiar todos los filtros
    window.limpiarFiltros = function() {
        if (buscarInput) buscarInput.value = '';
        if (filtroEstado) filtroEstado.value = 'Pendiente'; // Resetear a pendiente por defecto
        if (ordenarSelect) ordenarSelect.value = 'reciente'; // Resetear a reciente por defecto
        if (filtroTiempo) filtroTiempo.value = '';
        if (filtroFecha) filtroFecha.value = new Date().toISOString().slice(0, 10); // Resetear a hoy
        // Actualizar desde el servidor con los filtros limpios
        actualizarOrdenes(document.getElementById('ordenarPor').value);
    };

    // Escuchar eventos de órdenes usando el sistema de notificaciones modular
    channel.bind('orden.creada', function (data) {
        console.log('🎉 EVENTO RECIBIDO: orden.creada');
        console.log('📊 Datos completos del evento:', JSON.stringify(data, null, 2));
        console.log('📋 Estructura de datos:', {
            orden: data.orden,
            datos_adicionales: data.datos_adicionales,
            tipo_evento: 'orden.creada'
        });
        console.log('Actualizando lista de órdenes...');

        // Actualizar la lista de órdenes
        actualizarOrdenes(document.getElementById('ordenarPor').value);

        // Usar el sistema de notificaciones modular
        if (window.notificationService) {
            window.notificationService.handleNotification('orden.creada', data);
        }
    });

    channel.bind('orden.estado_actualizado', function (data) {
        console.log('🔄 EVENTO RECIBIDO: orden.estado_actualizado');
        console.log('📊 Datos completos del evento:', JSON.stringify(data, null, 2));
        console.log('📋 Estructura de datos:', {
            orden: data.orden,
            datos_adicionales: data.datos_adicionales,
            tipo_evento: 'orden.estado_actualizado'
        });
        console.log('Actualizando lista de órdenes...');

        // Actualizar la lista de órdenes
        actualizarOrdenes(document.getElementById('ordenarPor').value);

        // Usar el sistema de notificaciones modular
        if (window.notificationService) {
            window.notificationService.handleNotification('orden.estado_actualizado', data);
        }
    });

    channel.bind('orden.servida', function (data) {
        console.log('✅ EVENTO RECIBIDO: orden.servida');
        console.log('📊 Datos completos del evento:', JSON.stringify(data, null, 2));
        console.log('📋 Estructura de datos:', {
            orden: data.orden,
            datos_adicionales: data.datos_adicionales,
            tipo_evento: 'orden.servida'
        });
        console.log('Actualizando lista de órdenes...');

        // Actualizar la lista de órdenes
        actualizarOrdenes(document.getElementById('ordenarPor').value);

        // Usar el sistema de notificaciones modular
        if (window.notificationService) {
            window.notificationService.handleNotification('orden.servida', data);
        }
    });

    // Inicializar con filtro de pendientes por defecto
    if (filtroEstado) {
        filtroEstado.value = 'Pendiente';
        // Los datos ya vienen filtrados del servidor, no necesitamos filtrar aquí
    }
});
