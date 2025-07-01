/**
 * Ejemplo de uso del sistema de notificaciones modular
 * Este archivo muestra cómo usar las notificaciones en otras vistas
 */

document.addEventListener('DOMContentLoaded', function () {
    // Verificar si el servicio de notificaciones está disponible
    if (!window.notificationService) {
        console.warn('El servicio de notificaciones no está disponible');
        return;
    }

    // Ejemplo 1: Notificación de éxito
    function mostrarNotificacionExito() {
        window.notificationService.success(
            'Operación Exitosa',
            'La operación se completó correctamente'
        );
    }

    // Ejemplo 2: Notificación de error
    function mostrarNotificacionError() {
        window.notificationService.handleError(
            'Ha ocurrido un error inesperado',
            'Error del Sistema'
        );
    }

    // Ejemplo 3: Notificación de advertencia
    function mostrarNotificacionAdvertencia() {
        window.notificationService.warning(
            'Advertencia',
            'Esta acción puede tener consecuencias importantes'
        );
    }

    // Ejemplo 4: Notificación informativa
    function mostrarNotificacionInfo() {
        window.notificationService.info(
            'Información',
            'Este es un mensaje informativo para el usuario'
        );
    }

    // Ejemplo 5: Notificación con acciones personalizadas
    function mostrarNotificacionConAcciones() {
        window.notificationService.success(
            'Archivo Subido',
            'El archivo se ha subido correctamente',
            {
                actions: [
                    {
                        text: 'Ver Archivo',
                        type: 'primary',
                        icon: 'bi-eye',
                        onClick: 'window.open("/archivos/123", "_blank")'
                    },
                    {
                        text: 'Descargar',
                        type: 'outline-secondary',
                        icon: 'bi-download',
                        onClick: 'window.location.href="/archivos/123/descargar"'
                    }
                ],
                duration: 10000 // 10 segundos
            }
        );
    }

    // Ejemplo 6: Notificación persistente
    function mostrarNotificacionPersistente() {
        window.notificationService.warning(
            'Mantenimiento Programado',
            'El sistema estará en mantenimiento mañana de 2:00 AM a 4:00 AM',
            {
                persistent: true, // No se auto-elimina
                actions: [
                    {
                        text: 'Entendido',
                        type: 'outline-warning',
                        icon: 'bi-check',
                        onClick: 'window.notificationManager.remove(this.closest(".notification").id)'
                    }
                ]
            }
        );
    }

    // Ejemplo 7: Manejo de errores de API
    function manejarErrorAPI(error) {
        if (error.status === 401) {
            window.notificationService.handleError(
                'Su sesión ha expirado. Por favor, inicie sesión nuevamente.',
                'Sesión Expirada'
            );
        } else if (error.status === 403) {
            window.notificationService.handleError(
                'No tiene permisos para realizar esta acción.',
                'Acceso Denegado'
            );
        } else if (error.status === 404) {
            window.notificationService.handleError(
                'El recurso solicitado no fue encontrado.',
                'Recurso No Encontrado'
            );
        } else if (error.status >= 500) {
            window.notificationService.handleError(
                'Error interno del servidor. Por favor, intente más tarde.',
                'Error del Servidor'
            );
        } else {
            window.notificationService.handleError(
                error.message || 'Ha ocurrido un error inesperado.',
                'Error'
            );
        }
    }

    // Ejemplo 8: Notificación de conexión
    function manejarCambioConexion(estaConectado) {
        if (estaConectado) {
            window.notificationService.handleConnectionRestored();
        } else {
            window.notificationService.handleConnectionLost();
        }
    }

    // Ejemplo 9: Notificación personalizada para diferentes roles
    function mostrarNotificacionPorRol(mensaje, tipo = 'info') {
        const userRole = document.querySelector('meta[name="user-role"]')?.content;
        
        let titulo = 'Notificación';
        let duracion = 5000;
        
        switch (userRole) {
            case 'mesero':
                titulo = 'Notificación de Mesero';
                duracion = 3000; // Los meseros necesitan notificaciones más rápidas
                break;
            case 'gerente':
                titulo = 'Notificación de Gerente';
                duracion = 7000; // Los gerentes pueden tener más tiempo para leer
                break;
            case 'administrador':
                titulo = 'Notificación de Administrador';
                duracion = 6000;
                break;
            case 'superadmin':
                titulo = 'Notificación de Super Administrador';
                duracion = 8000;
                break;
        }

        window.notificationService[tipo](titulo, mensaje, { duration: duracion });
    }

    // Ejemplo 10: Limpiar todas las notificaciones
    function limpiarTodasLasNotificaciones() {
        window.notificationManager.clearAll();
    }

    // Exponer funciones para uso global (opcional)
    window.ejemploNotificaciones = {
        mostrarExito: mostrarNotificacionExito,
        mostrarError: mostrarNotificacionError,
        mostrarAdvertencia: mostrarNotificacionAdvertencia,
        mostrarInfo: mostrarNotificacionInfo,
        mostrarConAcciones: mostrarNotificacionConAcciones,
        mostrarPersistente: mostrarNotificacionPersistente,
        manejarErrorAPI: manejarErrorAPI,
        manejarConexion: manejarCambioConexion,
        mostrarPorRol: mostrarNotificacionPorRol,
        limpiarTodas: limpiarTodasLasNotificaciones
    };

    console.log('Sistema de notificaciones de ejemplo cargado correctamente');
    console.log('Funciones disponibles en window.ejemploNotificaciones');
}); 