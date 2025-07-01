/**
 * NotificationService - Servicio específico para notificaciones de órdenes
 * Implementa el patrón Strategy y extiende NotificationManager
 * Maneja notificaciones específicas del dominio de órdenes
 */
class NotificationService {
    constructor(notificationManager) {
        this.notificationManager = notificationManager;
        this.userId = this.getCurrentUserId();
        this.userRole = this.getCurrentUserRole();
        this.tenantId = this.getCurrentTenantId();
        this.sucursalId = this.getCurrentSucursalId();
    }

    /**
     * Obtiene el ID del usuario actual desde meta tags
     */
    getCurrentUserId() {
        const meta = document.querySelector('meta[name="user-id"]');
        return meta ? meta.content : null;
    }

    /**
     * Obtiene el rol del usuario actual
     */
    getCurrentUserRole() {
        const meta = document.querySelector('meta[name="user-role"]');
        return meta ? meta.content : null;
    }

    /**
     * Obtiene el tenant ID del usuario actual
     */
    getCurrentTenantId() {
        const meta = document.querySelector('meta[name="tenant-id"]');
        return meta ? meta.content : null;
    }

    /**
     * Obtiene el sucursal ID del usuario actual
     */
    getCurrentSucursalId() {
        const meta = document.querySelector('meta[name="sucursal-id"]');
        return meta ? meta.content : null;
    }

    /**
     * Verifica si el usuario puede recibir la notificación
     * Implementa restricciones basadas en rol y ubicación
     */
    canReceiveNotification(notificationData) {
        // Si no hay datos de usuario, no mostrar notificación
        if (!this.userId || !this.tenantId) {
            return false;
        }

        // Verificar que la notificación sea del mismo tenant
        if (notificationData.orden && notificationData.orden.tenant_id) {
            if (parseInt(notificationData.orden.tenant_id) !== parseInt(this.tenantId)) {
                return false;
            }
        }

        // Verificar que la notificación sea de la misma sucursal (si aplica)
        if (this.sucursalId && notificationData.orden && notificationData.orden.sucursal_id) {
            if (parseInt(notificationData.orden.sucursal_id) !== parseInt(this.sucursalId)) {
                return false;
            }
        }

        // Restricciones específicas por rol
        switch (this.userRole) {
            case 'mesero':
                // Los meseros solo ven notificaciones de su sucursal
                return this.sucursalId && notificationData.orden && 
                       parseInt(notificationData.orden.sucursal_id) === parseInt(this.sucursalId);
            
            case 'gerente':
                // Los gerentes ven notificaciones de su sucursal
                return this.sucursalId && notificationData.orden && 
                       parseInt(notificationData.orden.sucursal_id) === parseInt(this.sucursalId);
            
            case 'administrador':
                // Los administradores ven notificaciones de su tenant
                return true;
            
            case 'superadmin':
                // Los superadmin ven todas las notificaciones
                return true;
            
            default:
                return false;
        }
    }

    /**
     * Maneja notificación de orden creada
     */
    handleOrdenCreada(data) {
        if (!this.canReceiveNotification(data)) {
            return;
        }

        const title = 'Nueva Orden';
        const message = `Se ha creado una nueva orden #${data.orden.nro_orden} para la mesa ${data.orden.mesa}`;
      

        this.notificationManager.success(title, message, {
            actions,
            duration: 8000 // Más tiempo para órdenes importantes
        });
    }

    /**
     * Maneja notificación de estado actualizado
     */
    handleEstadoActualizado(data) {
        if (!this.canReceiveNotification(data)) {
            return;
        }

        const title = 'Estado Actualizado';
        const message = `La orden #${data.orden.nro_orden} ha cambiado a ${data.orden.estado}`;
        
        this.notificationManager.info(title, message, {
            duration: 6000
        });
    }

    /**
     * Maneja notificación de orden servida
     */
    handleOrdenServida(data) {
        if (!this.canReceiveNotification(data)) {
            return;
        }

        const title = 'Orden Servida';
        const message = `La orden #${data.orden.nro_orden} ha sido servida`;
        
        this.notificationManager.success(title, message, {
            duration: 5000
        });
    }

    /**
     * Maneja notificación de orden cancelada
     */
    handleOrdenCancelada(data) {
        if (!this.canReceiveNotification(data)) {
            return;
        }

        const title = 'Orden Cancelada';
        const message = `La orden #${data.orden.nro_orden} ha sido cancelada`;
        
        this.notificationManager.warning(title, message, {
            duration: 7000
        });
    }

    /**
     * Maneja notificación de error en el sistema
     */
    handleError(error, context = '') {
        const title = 'Error del Sistema';
        const message = context ? `${context}: ${error}` : error;
        
        this.notificationManager.error(title, message, {
            persistent: true, // Los errores son persistentes
            actions: [{
                text: 'Reintentar',
                type: 'outline-primary',
                icon: 'bi-arrow-clockwise',
                onClick: 'window.location.reload()'
            }]
        });
    }

    /**
     * Maneja notificación de conexión perdida
     */
    handleConnectionLost() {
        this.notificationManager.warning(
            'Conexión Perdida',
            'Se ha perdido la conexión con el servidor. Intentando reconectar...',
            {
                persistent: true,
                actions: [{
                    text: 'Reconectar',
                    type: 'outline-warning',
                    icon: 'bi-wifi',
                    onClick: 'window.location.reload()'
                }]
            }
        );
    }

    /**
     * Maneja notificación de conexión restaurada
     */
    handleConnectionRestored() {
        this.notificationManager.success(
            'Conexión Restaurada',
            'La conexión con el servidor se ha restaurado correctamente.',
            { duration: 3000 }
        );
    }

    /**
     * Método genérico para manejar cualquier tipo de notificación
     */
    handleNotification(eventType, data) {
        const handlers = {
            'orden.creada': this.handleOrdenCreada.bind(this),
            'orden.estado_actualizado': this.handleEstadoActualizado.bind(this),
            'orden.servida': this.handleOrdenServida.bind(this),
            'orden.cancelada': this.handleOrdenCancelada.bind(this),
            'connection.lost': this.handleConnectionLost.bind(this),
            'connection.restored': this.handleConnectionRestored.bind(this)
        };

        const handler = handlers[eventType];
        if (handler) {
            handler(data);
        } else {
            console.warn(`No hay manejador para el evento: ${eventType}`);
        }
    }
} 