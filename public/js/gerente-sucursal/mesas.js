document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Manejo del formulario de nueva mesa
    const formNuevaMesa = document.getElementById('formNuevaMesa');
    if (formNuevaMesa) {
        formNuevaMesa.addEventListener('submit', function(e) {
            e.preventDefault();
            // Aquí iría la lógica para crear una nueva mesa
            console.log('Creando nueva mesa:', {
                numero: this.numero.value,
                zona: this.zona.value,
                capacidad: this.capacidad.value
            });
            
            // Cerrar el modal después de crear
            const modal = bootstrap.Modal.getInstance(document.getElementById('nuevaMesaModal'));
            modal.hide();
            
            // Mostrar notificación de éxito
            alert('Mesa creada correctamente');
        });
    }

    // Manejo del formulario de edición de mesa
    const formEditarMesa = document.getElementById('formEditarMesa');
    if (formEditarMesa) {
        formEditarMesa.addEventListener('submit', function(e) {
            e.preventDefault();
            // Aquí iría la lógica para guardar los cambios
            console.log('Actualizando mesa:', {
                id: this.id.value,
                numero: this.numero.value,
                zona: this.zona.value,
                capacidad: this.capacidad.value,
                estado: this.estado.value
            });
            
            // Cerrar el modal después de guardar
            const modal = bootstrap.Modal.getInstance(document.getElementById('editarMesaModal'));
            modal.hide();
            
            // Mostrar notificación de éxito
            alert('Cambios guardados correctamente');
        });
    }

    // Función para cargar datos en el modal de edición (ejemplo)
    function cargarDatosEdicion(mesaId) {
        // Simulación de datos - en una aplicación real harías una petición AJAX
        const mesaData = {
            id: mesaId,
            numero: 'Mesa ' + mesaId,
            zona: 'Terraza',
            capacidad: '4 personas',
            estado: 'Libre'
        };
        
        document.getElementById('editMesaId').value = mesaData.id;
        document.getElementById('editMesaNumero').value = mesaData.numero;
        document.getElementById('editMesaZona').value = mesaData.zona;
        document.getElementById('editMesaCapacidad').value = mesaData.capacidad;
        document.getElementById('editMesaEstado').value = mesaData.estado;
    }

    // Asignar eventos a los botones de edición
    document.querySelectorAll('[data-bs-target="#editarMesaModal"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const mesaId = this.closest('tr').querySelector('td:first-child').textContent.trim().split(' ')[1];
            cargarDatosEdicion(mesaId);
        });
    });
});

// Función para editar mesa desde el modal de ver detalles
function editarMesa() {
    const verModal = bootstrap.Modal.getInstance(document.getElementById('verMesaModal'));
    verModal.hide();
    
    const mesaId = document.getElementById('mesaNombre').textContent.trim().split(' ')[1];
    
    // Simulación de datos - en una aplicación real harías una petición AJAX
    const mesaData = {
        id: mesaId,
        numero: document.getElementById('mesaNombre').textContent.trim(),
        zona: document.getElementById('mesaZona').textContent.replace('Zona: ', ''),
        capacidad: document.getElementById('mesaCapacidad').textContent.replace('Capacidad: ', ''),
        estado: document.getElementById('mesaEstado').textContent.trim()
    };
    
    document.getElementById('editMesaId').value = mesaData.id;
    document.getElementById('editMesaNumero').value = mesaData.numero;
    document.getElementById('editMesaZona').value = mesaData.zona;
    document.getElementById('editMesaCapacidad').value = mesaData.capacidad;
    document.getElementById('editMesaEstado').value = mesaData.estado;
    
    const editModal = new bootstrap.Modal(document.getElementById('editarMesaModal'));
    editModal.show();
}