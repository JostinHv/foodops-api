document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Manejar la búsqueda y filtros
    const searchInput = document.querySelector('input[type="search"]');
    const groupFilter = document.querySelector('select:nth-of-type(1)');
    const statusFilter = document.querySelector('select:nth-of-type(2)');
    
    if (searchInput && groupFilter && statusFilter) {
        [searchInput, groupFilter, statusFilter].forEach(element => {
            element.addEventListener('change', filterRestaurants);
        });
    }

    function filterRestaurants() {
        // Aquí iría la lógica para filtrar los restaurantes
        console.log('Filtrando restaurantes...');
        // En una implementación real, esto podría hacer una llamada AJAX o filtrar client-side
    }

    // Manejar eventos de los botones de acción
    document.querySelectorAll('.btn-outline-primary').forEach(btn => {
        btn.addEventListener('click', function() {
            console.log('Ver detalles del restaurante');
        });
    });

    document.querySelectorAll('.btn-outline-secondary').forEach(btn => {
        btn.addEventListener('click', function() {
            console.log('Editar restaurante');
        });
    });

    document.querySelectorAll('.btn-outline-danger').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('¿Estás seguro de que deseas eliminar este restaurante?')) {
                console.log('Eliminar restaurante');
            }
        });
    });
});

// Manejar el modal de nuevo restaurante
document.getElementById('btn-nuevo-restaurante').addEventListener('click', function() {
    const modal = new bootstrap.Modal(document.getElementById('nuevoRestauranteModal'));
    modal.show();
});

// Manejar el modal de ver detalles
document.querySelectorAll('.btn-ver-detalles').forEach(btn => {
    btn.addEventListener('click', function() {
        // Obtener datos del restaurante (en una implementación real, estos datos vendrían de una llamada AJAX)
        const restaurante = {
            nombre: this.closest('tr').querySelector('td:nth-child(1)').textContent.trim(),
            grupo: this.closest('tr').querySelector('td:nth-child(2)').textContent.trim(),
            // ... otros datos ...
        };

        // Llenar el modal de detalles
        document.getElementById('detalle-nombre').textContent = restaurante.nombre;
        document.getElementById('detalle-grupo').textContent = restaurante.grupo;
        // ... llenar otros campos ...

        const modal = new bootstrap.Modal(document.getElementById('detalleRestauranteModal'));
        modal.show();
    });
});

// Manejar el modal de editar
document.querySelectorAll('.btn-editar').forEach(btn => {
    btn.addEventListener('click', function() {
        // Obtener datos del restaurante (en una implementación real, estos datos vendrían de una llamada AJAX)
        const restaurante = {
            id: this.dataset.id,
            nombre: this.closest('tr').querySelector('td:nth-child(1)').textContent.trim(),
            grupo_id: this.dataset.grupoId,
            // ... otros datos ...
        };

        // Llenar el formulario de edición
        document.getElementById('editar-nombre').value = restaurante.nombre;
        document.getElementById('editar-grupo_id').value = restaurante.grupo_id;
        // ... llenar otros campos ...

        // Actualizar la acción del formulario
        document.getElementById('form-editar-restaurante').action = `/tenant/restaurantes/${restaurante.id}`;

        const modal = new bootstrap.Modal(document.getElementById('editarRestauranteModal'));
        modal.show();
    });
});