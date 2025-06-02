document.addEventListener('DOMContentLoaded', function () {
    // Función para cargar los datos del item en el modal de edición
    const editarModal = document.getElementById('editarItemModal');
    editarModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const itemId = button.getAttribute('data-item');
        const form = this.querySelector('form');
        form.action = `/gerente/menu/items/${itemId}`;

        // Cargar datos del item
        fetch(`/gerente/menu/items/${itemId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (!data.item) {
                throw new Error('No se encontraron datos del item');
            }

            const item = data.item;

            // Actualizar los campos con los datos del item
            const campos = {
                'nombre': item.nombre || '',
                'descripcion': item.descripcion || '',
                'precio': item.precio || '',
                'categoria_menu_id': item.categoria_menu_id || '',
                'disponible': item.disponible,
                'activo': item.activo
            };

            // Llenar cada campo del formulario
            Object.entries(campos).forEach(([name, value]) => {
                const input = form.querySelector(`[name="${name}"]`);
                if (input) {
                    if (input.type === 'checkbox') {
                        input.checked = value;
                    } else {
                        input.value = value;
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error al cargar los datos del item:', error);
            mostrarNotificacion('Error al cargar los datos del item: ' + error.message, 'danger');
        });
    });

    // Función para cargar los detalles del item en el modal de visualización
    const verModal = document.getElementById('verItemModal');
    verModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const itemId = button.getAttribute('data-item');

        // Cargar datos del item
        fetch(`/gerente/menu/items/${itemId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(data => {
            if (!data.item) {
                throw new Error('No se encontraron datos del item');
            }

            const item = data.item;

            // Actualizar los campos con los datos del item
            const elementos = {
                'item-nombre': item.nombre || 'No especificado',
                'item-categoria': item.categoria_menu?.nombre || 'No especificada',
                'item-precio': item.precio ? `S/ ${parseFloat(item.precio).toFixed(2)}` : 'No especificado',
                'item-descripcion': item.descripcion || 'No especificada',
                'item-estado': item.activo ? 'Activo' : 'Inactivo',
                'item-disponible': item.disponible ? 'Disponible' : 'No disponible'
            };

            // Actualizar cada elemento
            Object.entries(elementos).forEach(([id, value]) => {
                const elemento = document.getElementById(id);
                if (elemento) {
                    elemento.textContent = value;
                }
            });

            // Actualizar el estado con el color correspondiente
            const estadoElement = document.getElementById('item-estado');
            if (estadoElement) {
                estadoElement.className = `badge ${item.activo ? 'bg-success' : 'bg-warning'}`;
            }

            // Actualizar la disponibilidad con el color correspondiente
            const disponibleElement = document.getElementById('item-disponible');
            if (disponibleElement) {
                disponibleElement.className = `badge ${item.disponible ? 'bg-success' : 'bg-warning'}`;
            }
        })
        .catch(error => {
            console.error('Error al cargar los detalles del item:', error);
            mostrarNotificacion('Error al cargar los detalles del item: ' + error.message, 'danger');
        });
    });

    // Manejar el envío del formulario de edición
    const formEditarItem = document.getElementById('formEditarItem');
    if (formEditarItem) {
        formEditarItem.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            // Convertir los datos del formulario a un objeto
            const data = {};
            formData.forEach((value, key) => {
                // Manejar campos booleanos
                if (key === 'disponible' || key === 'activo') {
                    data[key] = value === 'on' || value === 'true';
                } else {
                    data[key] = value;
                }
            });

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                // Cerrar el modal
                cerrarModal('editarItemModal');
                // Mostrar notificación de éxito
                mostrarNotificacion(data.message, 'success');
                // Recargar la página después de un breve delay
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                let errorMessage = 'Error al actualizar el item';
                if (error.errors) {
                    errorMessage = Object.values(error.errors).flat().join('\n');
                } else if (error.error) {
                    errorMessage = error.error;
                }
                mostrarNotificacion(errorMessage, 'danger');
            });
        });
    }

    // Manejar el cambio de estado (activo/inactivo)
    const toggleActivoButtons = document.querySelectorAll('.toggle-activo');
    toggleActivoButtons.forEach(button => {
        button.addEventListener('click', function () {
            const itemId = this.getAttribute('data-item');
            fetch(`/gerente/menu/items/${itemId}/toggle-activo`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cambiar el estado: ' + error.message);
            });
        });
    });

    // Manejar el cambio de disponibilidad
    const toggleDisponibleButtons = document.querySelectorAll('.toggle-disponible');
    toggleDisponibleButtons.forEach(button => {
        button.addEventListener('click', function () {
            const itemId = this.getAttribute('data-item');
            fetch(`/gerente/menu/items/${itemId}/toggle-disponible`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cambiar la disponibilidad: ' + error.message);
            });
        });
    });

    // Función para mostrar notificaciones toast
    function mostrarNotificacion(mensaje, tipo = 'success') {
        const toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            // Crear el contenedor de toasts si no existe
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.style.position = 'fixed';
            container.style.top = '20px';
            container.style.right = '20px';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${tipo} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi ${tipo === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle'} me-2"></i>
                    ${mensaje}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        document.getElementById('toast-container').appendChild(toast);
        const bsToast = new bootstrap.Toast(toast, {
            animation: true,
            autohide: true,
            delay: 3000
        });
        bsToast.show();

        // Eliminar el toast del DOM después de que se oculte
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }

    // Función para cerrar un modal de Bootstrap
    function cerrarModal(modalId) {
        const modal = bootstrap.Modal.getInstance(document.getElementById(modalId));
        if (modal) {
            modal.hide();
        }
    }

    // Manejar el envío del formulario de nueva categoría
    const formNuevaCategoria = document.getElementById('formNuevaCategoria');
    if (formNuevaCategoria) {
        formNuevaCategoria.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            // Convertir los datos del formulario a un objeto
            const data = {};
            formData.forEach((value, key) => {
                // Manejar campos booleanos
                if (key === 'activo') {
                    data[key] = value === 'on' || value === 'true';
                } else {
                    data[key] = value;
                }
            });

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                // Cerrar el modal
                cerrarModal('nuevaCategoriaModal');
                // Mostrar notificación de éxito
                mostrarNotificacion(data.message, 'success');
                // Recargar la página después de un breve delay
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                let errorMessage = 'Error al crear la categoría';
                if (error.errors) {
                    errorMessage = Object.values(error.errors).flat().join('\n');
                } else if (error.error) {
                    errorMessage = error.error;
                }
                mostrarNotificacion(errorMessage, 'danger');
            });
        });
    }

    // Cargar datos de categoría en el modal de edición
    const editarCategoriaModal = document.getElementById('editarCategoriaModal');
    if (editarCategoriaModal) {
        editarCategoriaModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const categoriaId = button.getAttribute('data-categoria');
            const form = this.querySelector('form');
            form.action = `/gerente/menu/categorias/${categoriaId}`;

            // Cargar datos de la categoría
            fetch(`/gerente/menu/categorias/${categoriaId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al cargar los datos de la categoría');
                }
                return response.json();
            })
            .then(data => {
                if (!data.categoria) {
                    throw new Error('No se encontraron datos de la categoría');
                }

                const categoria = data.categoria;

                // Actualizar los campos con los datos de la categoría
                const campos = {
                    'nombre': categoria.nombre || '',
                    'descripcion': categoria.descripcion || '',
                    'activo': categoria.activo
                };

                // Llenar cada campo del formulario
                Object.entries(campos).forEach(([name, value]) => {
                    const input = form.querySelector(`[name="${name}"]`);
                    if (input) {
                        if (input.type === 'checkbox') {
                            input.checked = value;
                        } else {
                            input.value = value;
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error al cargar los datos de la categoría:', error);
                mostrarNotificacion('Error al cargar los datos de la categoría: ' + error.message, 'danger');
            });
        });
    }

    // Manejar el envío del formulario de editar categoría
    const formEditarCategoria = document.getElementById('formEditarCategoria');
    if (formEditarCategoria) {
        formEditarCategoria.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            // Convertir los datos del formulario a un objeto
            const data = {};
            formData.forEach((value, key) => {
                // Manejar campos booleanos
                if (key === 'activo') {
                    data[key] = value === 'on' || value === 'true';
                } else {
                    data[key] = value;
                }
            });

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                // Cerrar el modal
                cerrarModal('editarCategoriaModal');
                // Mostrar notificación de éxito
                mostrarNotificacion(data.message, 'success');
                // Recargar la página después de un breve delay
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                let errorMessage = 'Error al actualizar la categoría';
                if (error.errors) {
                    errorMessage = Object.values(error.errors).flat().join('\n');
                } else if (error.error) {
                    errorMessage = error.error;
                }
                mostrarNotificacion(errorMessage, 'danger');
            });
        });
    }

    // Manejar el cambio de estado de categoría
    const toggleCategoriaActivoButtons = document.querySelectorAll('.toggle-categoria-activo');
    toggleCategoriaActivoButtons.forEach(button => {
        button.addEventListener('click', function () {
            const categoriaId = this.getAttribute('data-categoria');
            fetch(`/gerente/menu/categorias/${categoriaId}/toggle-activo`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cambiar el estado de la categoría: ' + error.message);
            });
        });
    });

    // Manejar el envío del formulario de nuevo item
    const formNuevoItem = document.getElementById('formNuevoItem');
    if (formNuevoItem) {
        formNuevoItem.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            // Convertir los datos del formulario a un objeto
            const data = {};
            formData.forEach((value, key) => {
                // Manejar campos booleanos
                if (key === 'disponible' || key === 'activo') {
                    data[key] = value === 'on' || value === 'true';
                } else {
                    data[key] = value;
                }
            });

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }
                // Cerrar el modal
                cerrarModal('nuevoItemModal');
                // Mostrar notificación de éxito
                mostrarNotificacion(data.message, 'success');
                // Recargar la página después de un breve delay
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                let errorMessage = 'Error al crear el item';
                if (error.errors) {
                    errorMessage = Object.values(error.errors).flat().join('\n');
                } else if (error.error) {
                    errorMessage = error.error;
                }
                mostrarNotificacion(errorMessage, 'danger');
            });
        });
    }
}); 