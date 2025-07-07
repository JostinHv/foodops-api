document.addEventListener('DOMContentLoaded', function () {
    // Placeholder: lógica para actualizar dinámicamente el estado de la caja, mostrar notificaciones, etc.
    // Puedes agregar aquí la lógica de AJAX para apertura/cierre, recarga de movimientos, etc.
    // Ejemplo de notificación:
    if (window.notificationService && window.cajaSuccessMessage) {
        window.notificationService.success('Caja', window.cajaSuccessMessage);
    }
}); 