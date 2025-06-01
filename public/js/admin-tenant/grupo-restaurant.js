document.addEventListener('DOMContentLoaded', function() {
    // Función para cargar los datos del grupo en el modal de edición
    const editarModal = document.getElementById('editarGrupoModal');
    editarModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const grupoId = button.getAttribute('data-grupo');
        const form = this.querySelector('form');
        form.action = `/tenant/grupos/${grupoId}`;

        // Cargar datos del grupo
        fetch(`/tenant/grupos/${grupoId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (!data.grupo) {
                    throw new Error('No se encontraron datos del grupo');
                }

                const grupo = data.grupo;

                // Actualizar los campos con los datos del grupo
                form.querySelector('[name="nombre"]').value = grupo.nombre || '';
                form.querySelector('[name="descripcion"]').value = grupo.descripcion || '';
            })
            .catch(error => {
                console.error('Error al cargar los datos del grupo:', error);
                alert('Error al cargar los datos del grupo: ' + error.message);
            });
    });

    // Función para cargar los detalles del grupo en el modal de visualización
    const verModal = document.getElementById('verGrupoModal');
    verModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const grupoId = button.getAttribute('data-grupo');

        // Cargar datos del grupo
        fetch(`/tenant/grupos/${grupoId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (!data.grupo) {
                    throw new Error('No se encontraron datos del grupo');
                }

                const grupo = data.grupo;

                // Actualizar los campos con los datos del grupo
                document.getElementById('grupo-nombre').textContent = grupo.nombre || 'No especificado';
                document.getElementById('grupo-descripcion').textContent = grupo.descripcion || 'Sin descripción';
            })
            .catch(error => {
                console.error('Error al cargar los detalles del grupo:', error);
                alert('Error al cargar los detalles del grupo: ' + error.message);
            });
    });
});
