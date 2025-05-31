document.addEventListener('DOMContentLoaded', function() {
    const formNuevaMesa = document.getElementById('formNuevaMesa');

    if (formNuevaMesa) {
        formNuevaMesa.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            const capacidad = document.getElementById('capacidad');

            // Deshabilitar el botón al enviar
            submitButton.disabled = true;

            // Validaciones adicionales del lado del cliente
            if (capacidad.value < 1 || capacidad.value > 20) {
                e.preventDefault();
                alert('La capacidad debe estar entre 1 y 20 personas');
                submitButton.disabled = false;
                return false;
            }
        });
    }

    // Cerrar automáticamente los mensajes de alerta después de 5 segundos
    const alerts = document.querySelectorAll('.alert.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 150);
        }, 5000);
    });
});
