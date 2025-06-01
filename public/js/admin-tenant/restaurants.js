document.addEventListener('DOMContentLoaded', function() {
    // Función para cargar los datos del restaurante en el modal de edición
    const editarModal = document.getElementById('editarRestauranteModal');
    editarModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const restauranteId = button.getAttribute('data-restaurante');
        const form = this.querySelector('form');
        form.action = `/tenant/restaurantes/${restauranteId}`;
        
        // Cargar datos del restaurante
        fetch(`/tenant/restaurantes/${restauranteId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (!data.restaurante) {
                    throw new Error('No se encontraron datos del restaurante');
                }

                const restaurante = data.restaurante;
                
                // Actualizar los campos con los datos del restaurante
                const campos = {
                    'nombre_legal': restaurante.nombre_legal || '',
                    'nro_ruc': restaurante.nro_ruc || '',
                    'grupo_restaurant_id': restaurante.grupo_restaurant_id || '',
                    'tipo_negocio': restaurante.tipo_negocio || '',
                    'email': restaurante.email || '',
                    'telefono': restaurante.telefono || '',
                    'direccion': restaurante.direccion || '',
                    'latitud': restaurante.latitud || '',
                    'longitud': restaurante.longitud || '',
                    'sitio_web_url': restaurante.sitio_web_url || ''
                };

                // Llenar cada campo del formulario
                Object.entries(campos).forEach(([name, value]) => {
                    const input = form.querySelector(`[name="${name}"]`);
                    if (input) {
                        input.value = value;
                    }
                });
                
                // Mostrar el logo actual si existe
                const currentLogoDiv = form.querySelector('#current-logo');
                if (restaurante.logo && restaurante.logo.url) {
                    currentLogoDiv.innerHTML = `
                        <div class="logo-container">
                            <img src="/storage/${restaurante.logo.url}" alt="Logo actual" class="img-thumbnail">
                        </div>
                    `;
                } else {
                    currentLogoDiv.innerHTML = '';
                }
            })
            .catch(error => {
                console.error('Error al cargar los datos del restaurante:', error);
                alert('Error al cargar los datos del restaurante: ' + error.message);
            });
    });

    // Función para cargar los detalles del restaurante en el modal de visualización
    const verModal = document.getElementById('verRestauranteModal');
    verModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const restauranteId = button.getAttribute('data-restaurante');
        
        // Cargar datos del restaurante
        fetch(`/tenant/restaurantes/${restauranteId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (!data.restaurante) {
                    throw new Error('No se encontraron datos del restaurante');
                }

                const restaurante = data.restaurante;
                
                // Actualizar los campos con los datos del restaurante
                const elementos = {
                    'restaurante-nombre': restaurante.nombre_legal || 'No especificado',
                    'restaurante-estado': restaurante.activo ? 'Activo' : 'Inactivo',
                    'restaurante-grupo': restaurante.grupo_restaurantes?.nombre || 'Sin grupo',
                    'restaurante-tipo': restaurante.tipo_negocio || 'No especificado',
                    'restaurante-ruc': restaurante.nro_ruc || 'No especificado',
                    'restaurante-direccion': restaurante.direccion || 'No especificada',
                    'restaurante-telefono': restaurante.telefono || 'No especificado',
                    'restaurante-email': restaurante.email || 'No especificado'
                };

                // Actualizar cada elemento
                Object.entries(elementos).forEach(([id, value]) => {
                    const elemento = document.getElementById(id);
                    if (elemento) {
                        elemento.textContent = value;
                    }
                });

                // Actualizar el estado con el color correspondiente
                const estadoElement = document.getElementById('restaurante-estado');
                if (estadoElement) {
                    estadoElement.className = `badge ${restaurante.activo ? 'bg-success' : 'bg-warning'}`;
                }

                // Actualizar la ubicación
                const ubicacionElement = document.getElementById('restaurante-ubicacion');
                if (ubicacionElement) {
                    ubicacionElement.textContent = (restaurante.latitud && restaurante.longitud) ? 
                        `${restaurante.latitud}, ${restaurante.longitud}` : 
                        'No especificada';
                }

                // Actualizar el sitio web
                const webElement = document.getElementById('restaurante-web');
                if (webElement) {
                    webElement.innerHTML = restaurante.sitio_web_url ? 
                        `<a href="${restaurante.sitio_web_url}" target="_blank">${restaurante.sitio_web_url}</a>` : 
                        'No especificado';
                }
                
                // Mostrar el logo si existe
                const logoDiv = document.getElementById('restaurante-logo');
                if (logoDiv) {
                    if (restaurante.logo && restaurante.logo.url) {
                        logoDiv.innerHTML = `
                            <h6>Logo</h6>
                            <img src="/storage/${restaurante.logo.url}" alt="Logo" class="img-thumbnail" style="max-width: 200px;">
                        `;
                    } else {
                        logoDiv.innerHTML = '';
                    }
                }
            })
            .catch(error => {
                console.error('Error al cargar los detalles del restaurante:', error);
                alert('Error al cargar los detalles del restaurante: ' + error.message);
            });
    });

    // Confirmación de eliminación
    const deleteButtons = document.querySelectorAll('form[action*="destroy"] button');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de eliminar este restaurante? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });
}); 