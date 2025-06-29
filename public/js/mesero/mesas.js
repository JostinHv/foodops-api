document.addEventListener('DOMContentLoaded', function() {
    // Buscar mesas
    const searchInput = document.getElementById('searchMesa');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const mesas = document.querySelectorAll('.mesa-card');
            
            mesas.forEach(mesa => {
                const mesaText = mesa.textContent.toLowerCase();
                if (mesaText.includes(searchTerm)) {
                    mesa.closest('.col-md-6').style.display = 'block';
                } else {
                    mesa.closest('.col-md-6').style.display = 'none';
                }
            });
        });
    }

    // Cambiar estado de mesa
    const cambiarEstadoBtns = document.querySelectorAll('.cambiar-estado-btn');
    const estadoModal = new bootstrap.Modal(document.getElementById('estadoModal'));
    const nuevoEstadoSelect = document.getElementById('nuevoEstado');
    const reservaFields = document.getElementById('reservaFields');
    
    cambiarEstadoBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const mesaId = this.getAttribute('data-mesa');
            document.getElementById('mesaId').value = mesaId;
            estadoModal.show();
        });
    });

    // Mostrar/ocultar campos de reserva según selección
    nuevoEstadoSelect.addEventListener('change', function() {
        if (this.value === 'reservada') {
            reservaFields.style.display = 'block';
        } else {
            reservaFields.style.display = 'none';
        }
    });

    // Confirmar cambio de estado
    document.getElementById('confirmarCambio').addEventListener('click', function() {
        const form = document.getElementById('cambiarEstadoForm');
        const formData = new FormData(form);
        
        // Aquí iría la llamada AJAX para guardar los cambios
        fetch('/mesero/mesas/cambiar-estado', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar la interfaz según el nuevo estado
                actualizarInterfazMesa(data.mesa);
                estadoModal.hide();
                showToast('Estado actualizado correctamente', 'success');
            } else {
                showToast('Error al actualizar el estado', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error en la conexión', 'error');
        });
    });

    // Función para actualizar la interfaz después de cambiar estado
    function actualizarInterfazMesa(data) {
        const mesaElement = document.querySelector(`.cambiar-estado-btn[data-mesa="${data.id}"]`).closest('.mesa-card');
        
        // Actualizar badge de estado
        const badge = mesaElement.querySelector('.badge');
        badge.textContent = data.estado.charAt(0).toUpperCase() + data.estado.slice(1);
        
        // Cambiar clases según estado
        mesaElement.classList.remove('border-danger', 'border-success', 'border-warning');
        badge.classList.remove('bg-danger', 'bg-success', 'bg-warning');
        
        switch(data.estado) {
            case 'ocupada':
                mesaElement.classList.add('border-danger');
                badge.classList.add('bg-danger');
                // Aquí podrías actualizar la info de la orden si es necesario
                break;
            case 'libre':
                mesaElement.classList.add('border-success');
                badge.classList.add('bg-success');
                break;
            case 'reservada':
                mesaElement.classList.add('border-warning');
                badge.classList.add('bg-warning', 'text-dark');
                break;
        }
    }

    // Función para mostrar notificaciones
    function showToast(message, type) {
        // Implementación de toast notifications (puedes usar Bootstrap Toast)
        console.log(`${type}: ${message}`);
        // Aquí iría el código para mostrar un toast real
    }
});