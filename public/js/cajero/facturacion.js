// Protección global contra errores externos
(function () {
    // Interceptar errores de scripts externos antes de que se ejecuten
    const originalError = window.onerror;
    window.onerror = function (message, source, lineno, colno, error) {
        if (message && (message.includes('dw_scrollObj') ||
            message.includes('Cannot read properties of null') ||
            message.includes('style') ||
            message.includes('kernel.js'))) {
            console.warn('Error externo interceptado y prevenido:', message);
            return true; // Prevenir que el error se propague
        }
        if (originalError) {
            return originalError(message, source, lineno, colno, error);
        }
        return false;
    };

    // Crear una función dummy para dw_scrollObj que no cause errores
    const dummyScrollObj = function () {
        return {
            load: function () {
                // Función silenciosa que no hace nada
                return true;
            },
            init: function () {
                return true;
            },
            setup: function () {
                return true;
            }
        };
    };

    // Definir dw_scrollObj como una propiedad no configurable para evitar que se sobrescriba
    Object.defineProperty(window, 'dw_scrollObj', {
        value: dummyScrollObj,
        writable: false,
        configurable: false
    });

    // Interceptar eval para prevenir scripts problemáticos
    const originalEval = window.eval;
    window.eval = function (code) {
        if (code && (code.includes('dw_scrollObj') || code.includes('setupHelpSelectedScroll'))) {
            console.warn('Eval problemático interceptado:', code.substring(0, 100));
            return;
        }
        return originalEval.call(this, code);
    };

    // Interceptar la creación de scripts dinámicos
    const originalCreateElement = document.createElement;
    document.createElement = function (tagName) {
        const element = originalCreateElement.call(this, tagName);
        if (tagName.toLowerCase() === 'script') {
            const originalSetAttribute = element.setAttribute;
            element.setAttribute = function (name, value) {
                if (name === 'src' && (value.includes('kernel.js') || value.includes('dw_scrollObj'))) {
                    console.warn('Script problemático interceptado:', value);
                    return;
                }
                return originalSetAttribute.call(this, name, value);
            };
        }
        return element;
    };

    // Interceptar appendChild para scripts
    const originalAppendChild = Node.prototype.appendChild;
    Node.prototype.appendChild = function (child) {
        if (child.tagName === 'SCRIPT' && child.src && (child.src.includes('kernel.js') || child.src.includes('dw_scrollObj'))) {
            console.warn('AppendChild de script problemático interceptado:', child.src);
            return child;
        }
        return originalAppendChild.call(this, child);
    };
})();

document.addEventListener('DOMContentLoaded', function () {
    // Variables globales
    let ordenActual = null;
    let facturaActual = null;
    let pusher = null;
    let channel = null;

    // Elementos del DOM
    const buscarOrdenInput = document.getElementById('buscarOrden');
    const filtroEstadoSelect = document.getElementById('filtroEstado');
    const ordenarPorSelect = document.getElementById('ordenarPor');
    const listaOrdenes = document.getElementById('lista-ordenes');
    const facturarForm = document.getElementById('facturarForm');
    const confirmarFacturaBtn = document.getElementById('confirmarFactura');
    const ordenIdSelect = document.getElementById('orden_id');
    const igvIdSelect = document.getElementById('igv_id');
    const formCambiarEstado = document.getElementById('formCambiarEstado');

    // Configurar CSRF token para todas las peticiones AJAX
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const headers = {
        'X-CSRF-TOKEN': token,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };

    // Inicialización
    init();

    // Función de debug para verificar configuración
    function debugVerFacturaButtons() {
        console.log('🔍 Debug: Verificando botones de ver factura...');
        const buttons = document.querySelectorAll('.ver-factura');
        console.log(`📊 Encontrados ${buttons.length} botones de ver factura`);

        buttons.forEach((btn, index) => {
            const facturaId = btn.dataset.factura;
            console.log(`🔘 Botón ${index + 1}: ID=${facturaId}, Clases=${btn.className}`);
        });
    }

    // Ejecutar debug después de un delay para asegurar que el DOM esté cargado
    setTimeout(debugVerFacturaButtons, 1000);

    function init() {
        setupEventListeners();
        setupFormValidation();
        setupPusherNotifications();
        setupTabProtection();
    }

    function setupEventListeners() {
        // Búsqueda y filtros
        if (buscarOrdenInput) {
            buscarOrdenInput.addEventListener('input', debounce(handleBusqueda, 300));
        }

        // Formulario de facturación
        if (ordenIdSelect) {
            ordenIdSelect.addEventListener('change', handleOrdenChange);
        }

        if (igvIdSelect) {
            igvIdSelect.addEventListener('change', handleIgvChange);
        }

        if (confirmarFacturaBtn) {
            confirmarFacturaBtn.addEventListener('click', handleConfirmarFactura);
        }

        // Cambio de estado de orden
        if (formCambiarEstado) {
            formCambiarEstado.addEventListener('submit', handleCambiarEstado);

            // Agregar funcionalidad para cambiar color del botón
            const estadoSelect = formCambiarEstado.querySelector('#estado_orden_id');
            const submitButton = formCambiarEstado.querySelector('button[type="submit"]');

            if (estadoSelect && submitButton) {
                // Función para actualizar el color del botón según el estado seleccionado
                function actualizarColorBoton() {
                    const selectedOption = estadoSelect.options[estadoSelect.selectedIndex];
                    const color = selectedOption.dataset.color;
                    submitButton.className = `btn btn-${color}`;
                }

                // Actualizar color al cargar y cuando cambie la selección
                estadoSelect.addEventListener('change', actualizarColorBoton);

                // Inicializar el color del botón
                actualizarColorBoton();
            }
        }

        // Botones de facturar orden
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('facturar-orden')) {
                const ordenId = e.target.dataset.ordenId;
                handleFacturarOrden(ordenId);
            }
        });

        // Botones de ver detalles
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('ver-detalles') || e.target.closest('.ver-detalles')) {
                const ordenId = e.target.dataset.ordenId || e.target.closest('.ver-detalles').dataset.ordenId;
                handleVerDetalles(ordenId);
            }
        });

        // Protección específica para eventos de Bootstrap tabs
        document.addEventListener('show.bs.tab', function (e) {
            if (e.target.id === 'facturas-tab') {
                console.log('🔍 Activando pestaña facturas - protección aplicada');

                // Protección inmediata y silenciosa
                if (typeof window.dw_scrollObj === 'undefined') {
                    Object.defineProperty(window, 'dw_scrollObj', {
                        value: function () {
                            return {
                                load: function () {
                                    return true;
                                },
                                init: function () {
                                    return true;
                                },
                                setup: function () {
                                    return true;
                                }
                            };
                        },
                        writable: false,
                        configurable: false
                    });
                }
            }
        });
    }

    function setupFormValidation() {
        if (facturarForm) {
            facturarForm.addEventListener('submit', function (e) {
                e.preventDefault();
                handleConfirmarFactura();
            });
        }
    }

    // Funciones de búsqueda y filtros
    function handleBusqueda() {
        const searchTerm = buscarOrdenInput.value.toLowerCase();
        const ordenCards = document.querySelectorAll('.orden-card');

        ordenCards.forEach(card => {
            const ordenText = card.textContent.toLowerCase();
            const shouldShow = ordenText.includes(searchTerm);
            card.closest('.col-12').style.display = shouldShow ? 'block' : 'none';
        });
    }


    // Funciones de facturación
    function handleOrdenChange() {
        const ordenId = ordenIdSelect.value;
        const igvId = igvIdSelect.value;

        if (ordenId && igvId) {
            calcularTotales(ordenId, igvId);
        }
    }

    function handleIgvChange() {
        const ordenId = ordenIdSelect.value;
        const igvId = igvIdSelect.value;

        if (ordenId && igvId) {
            calcularTotales(ordenId, igvId);
        }
    }

    function calcularTotales(ordenId, igvId) {
        fetch('/cajero/facturacion/calcular-totales', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({
                orden_id: ordenId,
                igv_id: igvId
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.subtotal !== undefined) {
                    document.getElementById('subtotal').textContent = `S/ ${data.subtotal.toFixed(2)}`;
                    document.getElementById('monto_igv').textContent = `S/ ${data.monto_igv.toFixed(2)}`;
                    document.getElementById('total').textContent = `S/ ${data.total.toFixed(2)}`;
                    document.getElementById('igv_porcentaje').textContent = data.igv_porcentaje;
                }
            })
            .catch(error => {
                console.error('Error al calcular totales:', error);
            });
    }

    function handleConfirmarFactura() {
        if (!facturarForm.checkValidity()) {
            facturarForm.reportValidity();
            return;
        }

        const formData = new FormData(facturarForm);
        const data = Object.fromEntries(formData.entries());

        confirmarFacturaBtn.disabled = true;
        confirmarFacturaBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Procesando...';

        fetch('/cajero/facturacion', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    // Cerrar modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('facturarModal'));
                    modal.hide();

                    // Actualizar la lista de órdenes dinámicamente
                    // actualizarListaOrdenes();
                }
            })
            .catch(error => {
                console.error('Error al crear factura:', error);
                if (window.notificationService) {
                    window.notificationService.handleError('Error al crear la factura', 'Facturación');
                }
            })
            .finally(() => {
                confirmarFacturaBtn.disabled = false;
                confirmarFacturaBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Generar Comprobante';
            });
    }

    function handleFacturarOrden(ordenId) {
        // Pre-seleccionar la orden en el modal
        if (ordenIdSelect) {
            ordenIdSelect.value = ordenId;
            handleOrdenChange();
        }
    }

    // Funciones de detalles de orden
    function handleVerDetalles(ordenId) {
        // Guardar el ordenId en el modal para usarlo en el formulario
        const modal = document.getElementById('detalleComprobanteModal');
        modal.dataset.ordenId = ordenId;

        fetch(`/cajero/facturacion/orden/${ordenId}`)
            .then(response => response.json())
            .then(data => {
                if (data.orden) {
                    mostrarDetallesOrden(data.orden, data.tiempo_transcurrido);
                }
            })
            .catch(error => {
                console.error('Error al obtener detalles de la orden:', error);
            });
    }


    function mostrarDetallesOrden(orden, tiempoTranscurrido) {
        // Debug: Ver qué datos llegan
        console.log('Datos de la orden:', orden);
        console.log('Items de la orden:', orden.items_ordenes);

        // Información general
        const infoGeneral = document.getElementById('orden-info-general');
        infoGeneral.innerHTML = `
            <p><strong>Número de Orden:</strong> ${orden.nro_orden}</p>
            <p><strong>Cliente:</strong> ${orden.nombre_cliente || 'Sin especificar'}</p>
            <p><strong>Mesa:</strong> ${orden.mesa.nombre}</p>
            <p><strong>Fecha:</strong> ${new Date(orden.created_at).toLocaleString('es-ES')}</p>
        `;

        // Estado y tiempo
        const estadoTiempo = document.getElementById('orden-estado-tiempo');
        estadoTiempo.innerHTML = `
            <p><strong>Estado:</strong> <span class="badge bg-${getEstadoColor(orden.estado_orden.nombre)}">${orden.estado_orden.nombre}</span></p>
            <p><strong>Tiempo transcurrido:</strong> ${tiempoTranscurrido.humano}</p>
            ${tiempoTranscurrido.es_hoy ? `<p><strong>Minutos:</strong> ${tiempoTranscurrido.minutos} min</p>` : ''}
        `;

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

        // Productos
        const tbody = document.querySelector('#tabla-productos tbody');
        tbody.innerHTML = '';

        orden.items_ordenes.forEach(item => {
            // Convertir precio a número y manejar casos donde sea null o undefined
            const precio = parseFloat(item.item_menu.precio) || 0;
            const monto = parseFloat(item.monto) || 0;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.item_menu.nombre}</td>
                <td class="text-center">${item.cantidad}</td>
                <td class="text-end">S/. ${precio.toFixed(2)}</td>
                <td class="text-end">S/. ${monto.toFixed(2)}</td>
            `;
            tbody.appendChild(row);
        });

        // Total
        const total = orden.items_ordenes.reduce((sum, item) => {
            const monto = parseFloat(item.monto) || 0;
            return sum + monto;
        }, 0);
        document.getElementById('orden-total').textContent = `S/. ${total.toFixed(2)}`;

        // Configurar formulario de cambio de estado SOLO si no es pagada
        if (formCambiarEstado && orden.estado_orden.id !== 6) {
            if (estadoSelect) estadoSelect.value = orden.estado_orden.id;
        }

        // Configurar formulario de marcar como servida
        const formMarcarServida = document.getElementById('formMarcarServida');
        if (formMarcarServida) {
            formMarcarServida.action = `/cajero/facturacion/${orden.id}/cambiar-estado`;
        }
    }

    function handleCambiarEstado(e) {
        e.preventDefault();

        const ordenId = document.querySelector('#detalleComprobanteModal').dataset.ordenId;
        const estadoId = document.getElementById('estado_orden_id').value;
        const submitButton = e.target.querySelector('button[type="submit"]');

        // Deshabilitar el botón durante el envío
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
            Actualizando...
        `;

        fetch(`/cajero/facturacion/${ordenId}/cambiar-estado`, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify({
                estado_orden_id: estadoId
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recargar los detalles completos de la orden en el modal
                    handleVerDetalles(ordenId);
                } else {
                    throw new Error(data.message || 'Error al actualizar el estado');
                }
            })
            .catch(error => {
                console.error('Error al cambiar estado:', error);
            })
            .finally(() => {
                // Restaurar el botón
                submitButton.disabled = false;
                submitButton.innerHTML = `<i class="bi bi-check-circle me-1"></i>Actualizar`;
            });
    }

    // Funciones auxiliares
    function getEstadoColor(estadoNombre) {
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

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }


    // Configurar notificaciones de Pusher
    function setupPusherNotifications() {
        try {
            // Obtener configuración de Pusher desde meta tags
            const pusherKey = document.querySelector('meta[name="pusher-app-key"]')?.content;
            const pusherCluster = document.querySelector('meta[name="pusher-app-cluster"]')?.content;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const tenantId = document.querySelector('meta[name="tenant-id"]')?.content;
            const sucursalId = document.querySelector('meta[name="sucursal-id"]')?.content;

            if (!pusherKey || !pusherCluster) {
                console.warn('Configuración de Pusher no encontrada');
                return;
            }

            console.log('Configuración de Pusher para Cajero:', {
                key: pusherKey,
                cluster: pusherCluster,
                tenantId: tenantId,
                sucursalId: sucursalId
            });

            // Inicializar Pusher con la misma configuración que el mesero
            pusher = new Pusher(pusherKey, {
                cluster: pusherCluster,
                authEndpoint: "/broadcasting/auth",
                auth: {
                    headers: {
                        'X-CSRF-Token': csrfToken,
                    }
                },
                logToConsole: true
            });

            // Suscribirse al canal de órdenes usando el mismo formato que el mesero
            if (tenantId && sucursalId) {
                const channelName = `private-tenant.${tenantId}.sucursal.${sucursalId}.ordenes`;
                console.log('Intentando suscribirse al canal:', channelName);
                channel = pusher.subscribe(channelName);
            }

            // Logs de conexión
            pusher.connection.bind('connected', function () {
                console.log('✅ Pusher conectado exitosamente para Cajero');
                console.log('Estado de conexión:', pusher.connection.state);
            });

            pusher.connection.bind('error', function (err) {
                console.error('❌ Error de conexión Pusher:', err);
                if (window.notificationService) {
                    window.notificationService.handleError('Error de conexión con Pusher', 'Conexión');
                }
            });

            channel.bind('pusher:subscription_succeeded', function () {
                console.log('✅ Suscripción exitosa al canal de órdenes para Cajero');
                console.log('Canal suscrito:', channel.name);
            });

            channel.bind('pusher:subscription_error', function (status) {
                console.error('❌ Error de suscripción:', status);
                console.error('Detalles del error:', {
                    status: status.status,
                    data: status.data
                });
                if (window.notificationService) {
                    window.notificationService.handleError('Error al suscribirse al canal de órdenes', 'Suscripción');
                }
            });

        } catch (error) {
            console.error('Error al configurar notificaciones de Pusher:', error);
        }
    }

    // Limpiar conexión de Pusher al salir de la página
    window.addEventListener('beforeunload', function () {
        if (pusher) {
            pusher.disconnect();
        }
    });

    // Función para actualizar la lista de órdenes dinámicamente
    function actualizarListaOrdenes() {
        console.log('🔄 Actualizando lista de órdenes...');

        // Hacer petición AJAX para obtener las órdenes actualizadas
        fetch('/cajero/facturacion/api/ordenes', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.ordenes) {
                    console.log('🔄 Actualizando lista de órdenes:', data.ordenes.length);
                    // Actualizar la lista de órdenes
                    const listaOrdenesContainer = document.getElementById('lista-ordenes');
                    if (listaOrdenesContainer) {
                        listaOrdenesContainer.innerHTML = '';
                        data.ordenes.forEach(orden => {
                            const ordenCard = generarTarjetaOrden(orden);
                            listaOrdenesContainer.appendChild(ordenCard);
                        });
                    }

                    // Reasignar eventos a los nuevos botones
                    document.querySelectorAll('.ver-detalles').forEach(btn => {
                        btn.addEventListener('click', function (e) {
                            e.preventDefault();
                            const ordenId = this.dataset.ordenId;
                            handleVerDetalles(ordenId);
                        });
                    });

                    document.querySelectorAll('.facturar-orden').forEach(btn => {
                        btn.addEventListener('click', function (e) {
                            e.preventDefault();
                            const ordenId = this.dataset.ordenId;
                            handleFacturarOrden(ordenId);
                        });
                    });
                } else {
                    console.error('Error en la respuesta del servidor:', data.message);
                }
            })
            .catch(error => {
                console.error('Error al actualizar lista de órdenes:', error);
            });
    }

    // Función para generar tarjeta de orden (similar al mesero)
    function generarTarjetaOrden(orden) {
        const div = document.createElement('div');
        div.className = 'col-12 col-md-6 col-lg-4 col-xl-3';
        div.innerHTML = `
            <div class="card h-100 orden-card"
                 data-orden-id="${orden.id}"
                 data-estado-id="${orden.estado_orden.id}"
                 data-fecha="${orden.created_at}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">Orden #${orden.nro_orden}</h5>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-clock me-1"></i>
                                ${orden.tiempo_transcurrido.humano}
                                ${orden.tiempo_transcurrido.es_hoy ? `(${orden.tiempo_transcurrido.minutos} min)` : ''}
                            </p>
                        </div>
                        <span class="badge bg-${getEstadoColor(orden.estado_orden.nombre)}">
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
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-cart me-2 text-primary"></i>
                            <span>${orden.items_ordenes.length} productos</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-wallet2 me-2 text-primary"></i>
                            <span>${orden.estado_orden.nombre === 'Pagada' ? 'Pagado' : 'Pendiente'}</span>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Total: S/. ${parseFloat(orden.items_ordenes.reduce((sum, item) => sum + parseFloat(item.monto), 0)).toFixed(2)}</h6>
                            <div class="btn-group">
                                <button class="btn btn-outline-primary btn-sm ver-detalles d-none d-md-block"
                                        data-orden-id="${orden.id}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detalleComprobanteModal">
                                    <i class="bi bi-eye me-1"></i>Ver Detalles
                                </button>
                                ${[1, 2, 3, 4, 5, 7, 8, 9, 10].includes(orden.estado_orden.id) ?
            `<button class="btn btn-outline-success btn-sm facturar-orden"
                                             data-orden-id="${orden.id}"
                                             data-bs-toggle="modal"
                                             data-bs-target="#facturarModal">
                                        <i class="bi bi-receipt me-1"></i>Facturar
                                    </button>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        return div;
    }

    // Eventos específicos para facturación - usando el mismo formato que el mesero
    channel.bind('orden.creada', function (data) {
        console.log('🎉 EVENTO RECIBIDO: orden.creada en Cajero');
        console.log('📊 Datos completos del evento:', JSON.stringify(data, null, 2));

        actualizarListaOrdenes();

        if (window.notificationService) {
            window.notificationService.handleNotification('orden.creada', data);
        }
    });

    channel.bind('orden.estado_actualizado', function (data) {
        console.log('🔄 EVENTO RECIBIDO: orden.estado_actualizado en Cajero');
        console.log('📊 Datos completos del evento:', JSON.stringify(data, null, 2));

        actualizarListaOrdenes();

        if (window.notificationService) {
            window.notificationService.handleNotification('orden.estado_actualizado', data);
        }
    });

    channel.bind('orden.servida', function (data) {
        console.log('✅ EVENTO RECIBIDO: orden.servida en Cajero');
        console.log('📊 Datos completos del evento:', JSON.stringify(data, null, 2));

        actualizarListaOrdenes();

        if (window.notificationService) {
            window.notificationService.handleNotification('orden.servida', data);
        }
    });

    // Eventos para facturas
    channel.bind('orden.factura_creada', function (data) {
        console.log('💰 EVENTO RECIBIDO: orden.factura_creada en Cajero');
        console.log('📊 Datos completos del evento:', JSON.stringify(data, null, 2));

        // Actualizar ambas pestañas
        actualizarListaOrdenes();
        actualizarListaFacturas().then(r => {
        });

        if (window.notificationService) {
            window.notificationService.handleNotification('factura.creada', data);
        }

    });

    channel.bind('orden.factura_actualizada', function (data) {
        console.log('📝 EVENTO RECIBIDO: orden.factura_actualizada en Cajero');
        console.log('📊 Datos completos del evento:', JSON.stringify(data, null, 2));

        if (window.notificationService) {
            window.notificationService.handleNotification('factura.actualizada', data);
        }
        actualizarListaFacturas().then(r => {
        });
    });

    // Event listener único para botones de ver factura (delegación de eventos)
    document.addEventListener('click', function (e) {
        // Detectar clicks en botones de ver factura (delegación de eventos)
        if (e.target.classList.contains('ver-factura') ||
            e.target.closest('.ver-factura') ||
            (e.target.tagName === 'I' && e.target.closest('.ver-factura'))) {

            e.preventDefault();
            e.stopPropagation();

            const button = e.target.classList.contains('ver-factura') ? e.target : e.target.closest('.ver-factura');
            const facturaId = button.dataset.factura;

            console.log('🔍 Click detectado en botón ver factura:', facturaId);

            if (facturaId) {
                handleVerFactura(facturaId);
            } else {
                console.error('❌ No se encontró el ID de factura en el botón');
                if (window.notificationService) {
                    window.notificationService.handleError('Error: No se pudo identificar la factura', 'Error de Datos');
                }
            }
        }
    });

    // Función de protección para clicks en números de factura y orden
    function setupSafeClicks() {
        // Remover completamente la funcionalidad de clicks en números
        document.addEventListener('click', function (e) {
            // Prevenir cualquier click en elementos con clases específicas
            if (e.target.classList.contains('factura-numero') ||
                e.target.classList.contains('orden-numero') ||
                e.target.closest('.factura-numero') ||
                e.target.closest('.orden-numero')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });

        // Prevenir eventos problemáticos en elementos clickeables
        document.addEventListener('mousedown', function (e) {
            if (e.target.classList.contains('factura-numero') ||
                e.target.classList.contains('orden-numero') ||
                e.target.closest('.factura-numero') ||
                e.target.closest('.orden-numero')) {
                e.stopPropagation();
                return false;
            }
        });

        document.addEventListener('mouseup', function (e) {
            if (e.target.classList.contains('factura-numero') ||
                e.target.classList.contains('orden-numero') ||
                e.target.closest('.factura-numero') ||
                e.target.closest('.orden-numero')) {
                e.stopPropagation();
                return false;
            }
        });

        // Prevenir también eventos de hover y focus
        document.addEventListener('mouseenter', function (e) {
            if (e.target.classList.contains('factura-numero') ||
                e.target.classList.contains('orden-numero')) {
                e.stopPropagation();
            }
        });

        document.addEventListener('focus', function (e) {
            if (e.target.classList.contains('factura-numero') ||
                e.target.classList.contains('orden-numero')) {
                e.target.blur();
                e.stopPropagation();
            }
        });
    }

    // Función de protección contra errores externos
    function setupModalProtection() {
        // Prevenir errores de scripts externos
        window.addEventListener('error', function (e) {
            if (e.message && (e.message.includes('dw_scrollObj') || e.message.includes('Cannot read properties of null'))) {
                console.warn('Error de script externo interceptado y prevenido:', e.message);
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });

        // Protección específica para la pestaña de facturas
        const facturasTab = document.getElementById('facturas-tab');
        const facturasContent = document.getElementById('facturas');

        if (facturasTab) {
            facturasTab.addEventListener('click', function (e) {
                console.log('🔍 Click en pestaña facturas - protección aplicada');

                // Protección silenciosa
                setTimeout(() => {
                    if (facturasContent) {
                        // Remover cualquier script problemático que se haya agregado
                        const scripts = facturasContent.querySelectorAll('script');
                        scripts.forEach(script => {
                            if (script.textContent && (script.textContent.includes('dw_scrollObj') || script.textContent.includes('setupHelpSelectedScroll'))) {
                                console.warn('Script problemático removido de facturas');
                                script.remove();
                            }
                        });
                    }
                }, 50);
            });
        }

        // Proteger contra errores de jQuery si existe
        if (window.jQuery) {
            jQuery(document).ready(function ($) {
                // Prevenir conflictos con otros scripts - ya no necesitamos jQuery para ver-factura
                $(document).off('click', '.ver-factura');
            });
        }
    }

    // Configurar eventos del modal de factura
    document.addEventListener('DOMContentLoaded', function () {
        setupModalProtection();
        setupSafeClicks();

        const verFacturaModal = document.getElementById('verFacturaModal');
        if (verFacturaModal) {
            // Limpiar modal cuando se cierre
            verFacturaModal.addEventListener('hidden.bs.modal', function () {
                limpiarModalFactura();
            });

            // Limpiar modal cuando se abra
            verFacturaModal.addEventListener('show.bs.modal', function () {
                limpiarModalFactura();
            });

            // Cargar datos cuando se muestre el modal
            verFacturaModal.addEventListener('shown.bs.modal', function (event) {
                const button = event.relatedTarget;
                if (button && button.classList.contains('ver-factura')) {
                    const facturaId = button.dataset.factura;
                    console.log('🔍 Modal abierto, cargando factura:', facturaId);
                    if (facturaId) {
                        // Pequeño delay para asegurar que el modal esté completamente visible
                        setTimeout(() => {
                            handleVerFactura(facturaId);
                        }, 100);
                    }
                }
            });

            // Prevenir propagación de eventos problemáticos
            verFacturaModal.addEventListener('click', function (e) {
                if (e.target.classList.contains('ver-factura') ||
                    e.target.closest('.ver-factura')) {
                    e.stopPropagation();
                }
            });
        }
    });

    // Función para limpiar el modal de factura
    function limpiarModalFactura() {
        const modalBody = document.querySelector('#verFacturaModal #factura-detalles-contenido');
        const loadingPlaceholder = modalBody.querySelector('#loading-placeholder');
        const loadedContent = modalBody.querySelector('#factura-loaded-content');

        // Mostrar loading y ocultar contenido cargado
        if (loadingPlaceholder) loadingPlaceholder.style.display = 'block';
        if (loadedContent) loadedContent.style.display = 'none';

        // Limpiar todos los campos con placeholders apropiados
        const campos = [
            {id: 'detalle-factura-numero', placeholder: '#F-00001'},
            {id: 'detalle-factura-fecha', placeholder: '01/01/2024 12:00'},
            {id: 'detalle-orden-numero', placeholder: '#001'},
            {id: 'detalle-orden-cliente', placeholder: 'Cliente General'},
            {id: 'detalle-orden-mesa', placeholder: 'Mesa 1'},
            {id: 'detalle-orden-fecha', placeholder: '01/01/2024 12:00'},
            {id: 'detalle-subtotal', placeholder: '0.00'},
            {id: 'detalle-igv-porcentaje', placeholder: '0'},
            {id: 'detalle-igv', placeholder: '0.00'},
            {id: 'detalle-total', placeholder: '0.00'},
            {id: 'detalle-metodo-pago', placeholder: 'Efectivo'},
            {id: 'detalle-notas', placeholder: 'Ninguna'}
        ];

        campos.forEach(campo => {
            const elemento = document.getElementById(campo.id);
            if (elemento) {
                elemento.textContent = campo.placeholder;
                // Agregar clase de placeholder temporal
                elemento.classList.add('placeholder-glow');
            }
        });

        // Limpiar tabla de items
        const itemsList = document.getElementById('detalle-items-list');
        if (itemsList) {
            itemsList.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-4">
                        <div class="text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            <p class="mb-0">Cargando items...</p>
                        </div>
                    </td>
                </tr>
            `;
        }

        // Limpiar enlaces de botones
        const btnDescargarPDF = document.getElementById('btnDescargarPDF');
        const btnImprimirPOS = document.getElementById('btnImprimirPOS');

        if (btnDescargarPDF) {
            btnDescargarPDF.href = '#';
            btnDescargarPDF.onclick = null;
            btnDescargarPDF.classList.add('disabled');
        }
        if (btnImprimirPOS) {
            btnImprimirPOS.href = '#';
            btnImprimirPOS.onclick = null;
            btnImprimirPOS.classList.add('disabled');
        }

        // Resetear el badge de estado
        const estadoBadge = document.getElementById('detalle-estado-pago');
        if (estadoBadge) {
            estadoBadge.textContent = 'Pagado';
            estadoBadge.className = 'badge bg-success fs-6 px-3 py-2 mb-2';
        }
    }

    // Contador para evitar múltiples requests
    let requestCounter = 0;
    let currentRequest = null;

    // Función para ver detalles de factura
    async function handleVerFactura(facturaId) {
        requestCounter++;
        console.log(`🚀 Iniciando handleVerFactura con ID: ${facturaId} (Request #${requestCounter})`);

        // Evitar múltiples requests simultáneos
        if (currentRequest && currentRequest.facturaId === facturaId) {
            console.log(`⚠️ Request ya en progreso para factura ${facturaId}, ignorando...`);
            return;
        }

        if (!facturaId) {
            console.error('❌ ID de factura no proporcionado');
            if (window.notificationService) {
                window.notificationService.handleError('ID de factura no válido', 'Error de Validación');
            }
            return;
        }

        // Marcar que hay un request en progreso
        currentRequest = { facturaId, timestamp: Date.now() };

        const modal = document.getElementById('verFacturaModal');
        const modalBody = modal.querySelector('#factura-detalles-contenido');
        const loadingPlaceholder = modalBody.querySelector('#loading-placeholder');
        const loadedContent = modalBody.querySelector('#factura-loaded-content');

        console.log('🔍 Elementos del modal encontrados:', {
            modal: !!modal,
            modalBody: !!modalBody,
            loadingPlaceholder: !!loadingPlaceholder,
            loadedContent: !!loadedContent
        });

        // Mostrar loading y ocultar contenido cargado
        if (loadingPlaceholder) loadingPlaceholder.style.display = 'block';
        if (loadedContent) loadedContent.style.display = 'none';

        console.log('📡 Haciendo petición a:', `/cajero/facturacion/${facturaId}`);

        try {
            const response = await fetch(`/cajero/facturacion/${facturaId}`);

            console.log('📥 Respuesta recibida:', {
                status: response.status,
                ok: response.ok,
                statusText: response.statusText
            });

            if (!response.ok) {
                let errorMessage = 'Error al cargar los detalles de la factura';

                if (response.status === 404) {
                    errorMessage = 'La factura no fue encontrada';
                } else if (response.status === 403) {
                    errorMessage = 'No tienes permisos para ver esta factura';
                } else if (response.status >= 500) {
                    errorMessage = 'Error del servidor. Intente nuevamente más tarde';
                }

                throw new Error(errorMessage);
            }

            const data = await response.json();
            console.log('📊 Datos recibidos:', data);

            if (data.factura) {
                const factura = data.factura;
                console.log('✅ Factura encontrada, poblando detalles...');
                await poblarDetallesFactura(factura, facturaId);

                // Ocultar loading y mostrar contenido cargado
                if (loadingPlaceholder) loadingPlaceholder.style.display = 'none';
                if (loadedContent) loadedContent.style.display = 'block';

                console.log('✅ Detalles de factura cargados exitosamente');
            } else {
                throw new Error('No se encontraron datos de la factura en la respuesta del servidor');
            }
        } catch (error) {
            console.error('❌ Error al cargar detalles de factura:', error);

            // Ocultar loading y mostrar mensaje de error
            if (loadingPlaceholder) loadingPlaceholder.style.display = 'none';
            if (loadedContent) loadedContent.style.display = 'none';

            modalBody.innerHTML = `
                <div class="alert alert-danger">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Error al cargar los detalles de la factura</strong>
                    </div>
                    <p class="mb-2">${error.message || 'Intente nuevamente más tarde.'}</p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-danger btn-sm" onclick="handleVerFactura('${facturaId}')">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reintentar
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Cerrar
                        </button>
                    </div>
                </div>
            `;

            // Mostrar notificación de error si está disponible
            if (window.notificationService) {
                window.notificationService.handleError(
                    error.message || 'Error al cargar detalles de factura',
                    'Error de Carga'
                );
            }
        } finally {
            // Limpiar el request actual
            if (currentRequest && currentRequest.facturaId === facturaId) {
                currentRequest = null;
                console.log(`✅ Request completado para factura ${facturaId}`);
            }
        }
    }

    // Función auxiliar para poblar los detalles de la factura
    async function poblarDetallesFactura(factura, facturaId) {
        try {
            // Remover clases de placeholder de todos los elementos
            document.querySelectorAll('.placeholder-glow').forEach(element => {
                element.classList.remove('placeholder-glow');
            });

            // Poblar Detalles de la Factura
            document.getElementById('detalle-factura-numero').textContent = factura.nro_factura || 'N/A';
            document.getElementById('detalle-factura-fecha').textContent = factura.created_at ?
                new Date(factura.created_at).toLocaleString('es-ES', {
                    dateStyle: 'short',
                    timeStyle: 'short'
                }) : 'N/A';

            // Poblar Detalles de la Orden
            document.getElementById('detalle-orden-numero').textContent = factura.orden?.nro_orden || 'N/A';
            document.getElementById('detalle-orden-cliente').textContent = factura.orden?.nombre_cliente || 'Cliente General';
            document.getElementById('detalle-orden-mesa').textContent = factura.orden?.mesa?.nombre || 'N/A';
            document.getElementById('detalle-orden-fecha').textContent = factura.orden?.created_at ?
                new Date(factura.orden.created_at).toLocaleString('es-ES', {
                    dateStyle: 'short',
                    timeStyle: 'short'
                }) : 'N/A';

            // Poblar Items de la Orden
            await poblarItemsOrden(factura);

            // Poblar Resumen de Totales
            await poblarResumenTotales(factura);

            // Poblar Información de Pago
            await poblarInformacionPago(factura);

            // Configurar los botones de descarga/impresión
            await configurarBotonesDescarga(facturaId);

            // Habilitar los botones
            const btnDescargarPDF = document.getElementById('btnDescargarPDF');
            const btnImprimirPOS = document.getElementById('btnImprimirPOS');

            if (btnDescargarPDF) {
                btnDescargarPDF.classList.remove('disabled');
            }
            if (btnImprimirPOS) {
                btnImprimirPOS.classList.remove('disabled');
            }

        } catch (error) {
            console.error('Error al poblar detalles de factura:', error);
            throw error;
        }
    }

    // Función auxiliar para poblar items de la orden
    async function poblarItemsOrden(factura) {
        const itemsList = document.getElementById('detalle-items-list');
        itemsList.innerHTML = '';

        if (factura.orden?.items_ordenes && factura.orden.items_ordenes.length > 0) {
            factura.orden.items_ordenes.forEach(item => {
                const itemName = item.item_menu?.nombre || 'Producto Desconocido';
                const itemUnitPrice = parseFloat(item.monto / item.cantidad || 0).toFixed(2);
                const itemTotal = parseFloat(item.monto || 0).toFixed(2);

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="ps-3">
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">${itemName}</span>
                            <small class="text-muted">P.U.: S/ ${itemUnitPrice}</small>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-primary rounded-pill">${item.cantidad}</span>
                    </td>
                    <td class="text-end">S/ ${itemUnitPrice}</td>
                    <td class="text-end pe-3 fw-bold">S/ ${itemTotal}</td>
                `;
                itemsList.appendChild(row);
            });
        } else {
            itemsList.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-4">
                        <div class="text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            <p class="mb-0">No hay items asociados a esta orden</p>
                        </div>
                    </td>
                </tr>
            `;
        }
    }

    // Función auxiliar para poblar resumen de totales
    async function poblarResumenTotales(factura) {
        const subtotal = parseFloat(factura.monto_total || 0).toFixed(2);
        const igvPorcentaje = factura.igv?.valor_porcentaje || '0';
        const igvMonto = parseFloat(factura.monto_total_igv || 0).toFixed(2);
        const total = parseFloat(factura.monto_total_igv || 0).toFixed(2);

        document.getElementById('detalle-subtotal').textContent = subtotal;
        document.getElementById('detalle-igv-porcentaje').textContent = igvPorcentaje;
        document.getElementById('detalle-igv').textContent = igvMonto;
        document.getElementById('detalle-total').textContent = total;
    }

    // Función auxiliar para poblar información de pago
    async function poblarInformacionPago(factura) {
        const metodoPago = factura.metodo_pago?.nombre || 'N/A';
        const estadoPago = (factura.estado_pago || 'N/A').charAt(0).toUpperCase() + (factura.estado_pago || '').slice(1);
        const notas = factura.notas || 'Ninguna';

        document.getElementById('detalle-metodo-pago').textContent = metodoPago;
        document.getElementById('detalle-notas').textContent = notas;

        // Actualizar el badge de estado en el header
        const estadoBadge = document.getElementById('detalle-estado-pago');
        estadoBadge.textContent = estadoPago;

        // Cambiar el color del badge según el estado
        estadoBadge.className = `badge fs-6 px-3 py-2 mb-2 ${
            factura.estado_pago === 'pagado' ? 'bg-success' :
                factura.estado_pago === 'pendiente' ? 'bg-warning' : 'bg-danger'
        }`;
    }

    // Función auxiliar para configurar botones de descarga
    async function configurarBotonesDescarga(facturaId) {
        const btnDescargarPDF = document.getElementById('btnDescargarPDF');
        const btnImprimirPOS = document.getElementById('btnImprimirPOS');

        if (btnDescargarPDF) {
            btnDescargarPDF.href = `/cajero/facturacion/${facturaId}/pdf`;
            btnDescargarPDF.onclick = function (e) {
                e.preventDefault();
                window.open(this.href, '_blank');
            };
        }

        if (btnImprimirPOS) {
            btnImprimirPOS.href = `/cajero/facturacion/${facturaId}/pdf-pos`;
            btnImprimirPOS.onclick = function (e) {
                e.preventDefault();
                window.open(this.href, '_blank');
            };
        }
    }

    // Función para actualizar la lista de facturas dinámicamente
    async function actualizarListaFacturas() {
        try {
            console.log('🔄 Actualizando lista de facturas...');

            // Mostrar indicador de carga
            const tablaFacturas = document.querySelector('#facturas table tbody');
            if (tablaFacturas) {
                const loadingRow = document.createElement('tr');
                loadingRow.innerHTML = `
                    <td colspan="7" class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                            <span class="visually-hidden">Actualizando...</span>
                        </div>
                        Actualizando lista de facturas...
                    </td>
                `;
                tablaFacturas.appendChild(loadingRow);
            }

            // Hacer petición AJAX para obtener las facturas actualizadas
            const response = await fetch('/cajero/facturacion/api/facturas', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success && data.facturas) {
                console.log('🔄 Facturas actualizadas:', data.facturas.length);
                // Actualizar la tabla de facturas
                actualizarTablaFacturas(data.facturas);

                // Actualizar el contador en la pestaña
                const facturasTabButton = document.getElementById('facturas-tab');
                if (facturasTabButton) {
                    facturasTabButton.innerHTML = `<i class="bi bi-receipt me-2"></i>Facturas Recientes (${data.facturas.length})`;
                }
            } else {
                console.error('Error en la respuesta del servidor:', data.message);
            }
        } catch (error) {
            console.error('Error al actualizar lista de facturas:', error);

            // Mostrar notificación de error si está disponible
            if (window.notificationService) {
                window.notificationService.handleError('Error al actualizar la lista de facturas', 'Actualización');
            }
        }
    }

        // Función auxiliar para actualizar la tabla de facturas
    function actualizarTablaFacturas(facturas) {
        const tablaFacturas = document.querySelector('#facturas table tbody');
        if (!tablaFacturas) return;

        // Limpiar tabla
        tablaFacturas.innerHTML = '';

        if (facturas.length === 0) {
            tablaFacturas.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="text-muted">
                            <i class="bi bi-receipt fs-3 d-block mb-2"></i>
                            <p class="mb-0">No hay facturas registradas</p>
                            <small>Genera una nueva factura usando el botón superior</small>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        // Poblar tabla con las facturas actualizadas
        facturas.forEach(factura => {
            console.log('📊 Procesando factura:', factura);

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <strong>${factura.nro_factura || 'N/A'}</strong>
                    <div class="text-muted small">
                        ${factura.created_at ? new Date(factura.created_at).toLocaleString('es-ES', {
                            dateStyle: 'short',
                            timeStyle: 'short'
                        }) : 'N/A'}
                    </div>
                </td>
                <td>#${factura.orden?.nro_orden || 'N/A'}</td>
                <td>${factura.orden?.nombre_cliente || 'Cliente General'}</td>
                <td>${factura.orden?.mesa?.nombre || 'N/A'}</td>
                <td>
                    <strong>S/ ${parseFloat(factura.monto_total_igv || 0).toFixed(2)}</strong>
                </td>
                <td>
                    <span class="badge bg-${factura.estado_pago === 'pagado' ? 'success' : (factura.estado_pago === 'pendiente' ? 'warning' : 'danger')}">
                        ${(factura.estado_pago || 'N/A').charAt(0).toUpperCase() + (factura.estado_pago || '').slice(1)}
                    </span>
                </td>
                <td>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary ver-factura"
                                title="Ver detalles"
                                data-bs-toggle="modal"
                                data-bs-target="#verFacturaModal"
                                data-factura="${factura.id}">
                            <i class="bi bi-eye"></i>
                        </button>
                        <a href="/cajero/facturacion/${factura.id}/pdf"
                           class="btn btn-sm btn-outline-secondary" title="Descargar PDF"
                           target="_blank">
                            <i class="bi bi-file-pdf"></i>
                        </a>
                        <a href="/cajero/facturacion/${factura.id}/pdf-pos"
                           class="btn btn-sm btn-outline-info" title="Imprimir Ticket"
                           target="_blank">
                            <i class="bi bi-printer"></i>
                        </a>
                    </div>
                </td>
            `;
            tablaFacturas.appendChild(row);
        });

        // No necesitamos reasignar eventos porque usamos delegación de eventos
        console.log('✅ Tabla de facturas actualizada con delegación de eventos');
    }

    // Función para proteger las pestañas de errores externos
    function setupTabProtection() {
        // Interceptar eventos de Bootstrap para pestañas
        document.addEventListener('show.bs.tab', function (e) {
            if (e.target.id === 'facturas-tab') {
                // Protección silenciosa
                setTimeout(() => {
                    if (typeof window.dw_scrollObj === 'undefined') {
                        Object.defineProperty(window, 'dw_scrollObj', {
                            value: function () {
                                return {
                                    load: function () {
                                        return true;
                                    },
                                    init: function () {
                                        return true;
                                    },
                                    setup: function () {
                                        return true;
                                    }
                                };
                            },
                            writable: false,
                            configurable: false
                        });
                    }
                }, 10);
            }
        });

        // Protección adicional para el contenido de la pestaña
        const facturasContent = document.getElementById('facturas');
        if (facturasContent) {
            // Observar cambios en el contenido
            const observer = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    if (mutation.type === 'childList') {
                        // Verificar si se agregaron scripts problemáticos
                        mutation.addedNodes.forEach(function (node) {
                            if (node.tagName === 'SCRIPT' && node.textContent) {
                                if (node.textContent.includes('dw_scrollObj') || node.textContent.includes('setupHelpSelectedScroll')) {
                                    console.warn('Script problemático detectado y removido');
                                    node.remove();
                                }
                            }
                        });
                    }
                });
            });

            observer.observe(facturasContent, {
                childList: true,
                subtree: true
            });
        }
    }

});

