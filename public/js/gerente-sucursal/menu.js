document.addEventListener('DOMContentLoaded', function () {
    // Clase para validar imágenes
    class ImageValidator {
        constructor() {
            this.maxFileSize = 2 * 1024 * 1024; // 2MB
            this.allowedTypes = ['image/jpg','image/jpeg', 'image/png', 'image/gif'];
            this.maxWidth = 1200;
            this.maxHeight = 1200;
        }

        validateFile(file) {
            return new Promise((resolve, reject) => {
                // Validar tamaño
                if (file.size > this.maxFileSize) {
                    reject({
                        valid: false,
                        message: `El archivo excede el tamaño máximo permitido (${this.formatFileSize(this.maxFileSize)})`
                    });
                    return;
                }

                // Validar tipo
                if (!this.allowedTypes.includes(file.type)) {
                    reject({
                        valid: false,
                        message: 'Solo se permiten archivos de imagen (JPEG, PNG, GIF)'
                    });
                    return;
                }

                // Validar dimensiones
                const img = new Image();
                img.onload = () => {
                    if (img.width > this.maxWidth || img.height > this.maxHeight) {
                        reject({
                            valid: false,
                            message: `La imagen excede las dimensiones máximas permitidas (${this.maxWidth}x${this.maxHeight}px)`
                        });
                    } else {
                        resolve({
                            valid: true,
                            file: file
                        });
                    }
                };
                img.onerror = () => {
                    reject({
                        valid: false,
                        message: 'Error al cargar la imagen'
                    });
                };
                img.src = URL.createObjectURL(file);
            });
        }

        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    }

    const imageValidator = new ImageValidator();

    // Función para manejar la previsualización de imágenes
    function handleImagePreview(input, previewContainer) {
        const file = input.files[0];
        if (!file) return;

        // Mostrar progreso
        showImageProgress(previewContainer);

        imageValidator.validateFile(file)
            .then(result => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewContainer.innerHTML = `
                        <div class="image-upload-preview">
                            <img src="${e.target.result}" alt="Preview">
                            <div class="image-upload-remove" onclick="window.removeImage(this)">
                                <i class="bi bi-x"></i>
                            </div>
                        </div>
                        <div class="image-upload-info">
                            <div class="image-limit-info">
                                <i class="bi bi-check-circle"></i>
                                <span>Imagen válida (${imageValidator.formatFileSize(file.size)})</span>
                            </div>
                        </div>`;
                    hideImageProgress(previewContainer);
                };
                reader.readAsDataURL(file);
            })
            .catch(error => {
                previewContainer.innerHTML = `
                    <div class="image-upload-error show">
                        <i class="bi bi-exclamation-circle"></i>
                        ${error.message}
                    </div>`;
                input.value = '';
                hideImageProgress(previewContainer);
            });
    }

    function showImageProgress(container) {
        if (!container) return;
        if (!container.querySelector('.image-upload-progress')) {
            container.innerHTML = `
                <div class="image-upload-progress show">
                    <div class="image-upload-progress-bar"></div>
                </div>`;
        }
    }

    function hideImageProgress(container) {
        if (!container) return;
        const progress = container.querySelector('.image-upload-progress');
        if (progress) {
            progress.remove();
        }
    }

    // Hacer la función removeImage global
    window.removeImage = function(button) {
        const container = button.closest('.image-upload-container');
        const input = container.querySelector('input[type="file"]');
        const preview = container.querySelector('.image-upload-preview');
        if (input) input.value = '';
        if (preview) preview.remove();
    };

    // Configurar los inputs de imagen
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            const container = this.closest('.image-upload-container');
            const previewContainer = container.querySelector('.image-upload-preview-container');
            if (previewContainer) {
                handleImagePreview(this, previewContainer);
            }
        });
    });

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

            // Mostrar la imagen actual si existe
            if (item.imagen) {
                const imageContainer = form.querySelector('.image-upload-preview-container');
                if (imageContainer) {
                    imageContainer.innerHTML = `
                        <div class="image-upload-preview">
                            <img src="${item.imagen.url}" alt="Preview">
                            <div class="image-upload-remove" onclick="removeImage(this)">
                                <i class="bi bi-x"></i>
                            </div>
                        </div>`;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error al cargar los datos del item', 'error');
        });
    });

    // Función para mostrar los detalles del item
    const verItemModal = document.getElementById('verItemModal');
    verItemModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const itemId = button.getAttribute('data-item');

        fetch(`/gerente/menu/items/${itemId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            const item = data.item;
            document.getElementById('item-nombre').textContent = item.nombre;
            document.getElementById('item-descripcion').textContent = item.descripcion || 'Sin descripción';
            document.getElementById('item-precio').textContent = `S/ ${parseFloat(item.precio).toFixed(2)}`;
            document.getElementById('item-categoria').textContent = item.categoriaMenu?.nombre || 'Sin categoría';
            
            // Actualizar badges de estado
            const estadoBadge = document.getElementById('item-estado');
            estadoBadge.textContent = item.activo ? 'Activo' : 'Inactivo';
            estadoBadge.className = `badge ${item.activo ? 'bg-success' : 'bg-warning'}`;
            
            const disponibleBadge = document.getElementById('item-disponible');
            disponibleBadge.textContent = item.disponible ? 'Disponible' : 'No disponible';
            disponibleBadge.className = `badge ${item.disponible ? 'bg-success' : 'bg-warning'}`;

            // Mostrar la imagen si existe
            const modalBody = this.querySelector('.modal-body');
            const imageContainer = modalBody.querySelector('.item-image-container');
            if (item.imagen && imageContainer) {
                imageContainer.innerHTML = `
                    <div class="item-image">
                        <img src="${item.imagen.url}" alt="${item.nombre}" class="img-fluid rounded">
                    </div>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarNotificacion('Error al cargar los detalles del item', 'error');
        });
    });

    // Función para crear un nuevo item
    document.getElementById('formNuevoItem').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Obtener el archivo de imagen
        const imagenInput = this.querySelector('input[type="file"]');
        let imagenId = null;

        // Si hay una imagen, subirla primero
        if (imagenInput && imagenInput.files.length > 0) {
            const imagenFormData = new FormData();
            imagenFormData.append('imagen', imagenInput.files[0]);
            imagenFormData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            try {
                const imagenResponse = await fetch('/gerente/menu/upload-imagen', {
                    method: 'POST',
                    body: imagenFormData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!imagenResponse.ok) {
                    throw new Error('Error al subir la imagen');
                }

                const imagenData = await imagenResponse.json();
                imagenId = imagenData.imagen_id;
            } catch (error) {
                mostrarNotificacion('Error al subir la imagen', 'error');
                return;
            }
        }

        // Preparar los datos del item
        const itemData = {
            nombre: this.querySelector('[name="nombre"]').value,
            descripcion: this.querySelector('[name="descripcion"]').value,
            precio: this.querySelector('[name="precio"]').value,
            categoria_menu_id: this.querySelector('[name="categoria_menu_id"]').value,
            orden_visualizacion: this.querySelector('[name="orden_visualizacion"]').value,
            disponible: this.querySelector('[name="disponible"]').checked,
            activo: this.querySelector('[name="activo"]').checked,
            _token: document.querySelector('meta[name="csrf-token"]').content
        };

        // Si se subió una imagen, agregar el ID
        if (imagenId) {
            itemData.imagen_id = imagenId;
        }

        // Enviar los datos del item
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(itemData)
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(JSON.stringify(data));
            }

            mostrarNotificacion('Item creado exitosamente', 'success');
            cerrarModal('nuevoItemModal');
            setTimeout(() => window.location.reload(), 1000);
        } catch (error) {
            console.error('Error:', error);
            try {
                const errorData = JSON.parse(error.message);
                if (errorData.errors) {
                    Object.values(errorData.errors).forEach(messages => {
                        messages.forEach(message => {
                            mostrarNotificacion(message, 'error');
                        });
                    });
                } else {
                    mostrarNotificacion(errorData.error || 'Error al crear el item', 'error');
                }
            } catch (e) {
                mostrarNotificacion('Error al crear el item', 'error');
            }
        }
    });

    // Función para actualizar un item
    document.getElementById('formEditarItem').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Obtener el archivo de imagen
        const imagenInput = this.querySelector('input[type="file"]');
        let imagenId = null;

        // Si hay una imagen, subirla primero
        if (imagenInput && imagenInput.files.length > 0) {
            const imagenFormData = new FormData();
            imagenFormData.append('imagen', imagenInput.files[0]);
            imagenFormData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            try {
                const imagenResponse = await fetch('/gerente/menu/upload-imagen', {
                    method: 'POST',
                    body: imagenFormData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!imagenResponse.ok) {
                    throw new Error('Error al subir la imagen');
                }

                const imagenData = await imagenResponse.json();
                imagenId = imagenData.imagen_id;
            } catch (error) {
                mostrarNotificacion('Error al subir la imagen', 'error');
                return;
            }
        }

        // Preparar los datos del item
        const itemData = {
            nombre: this.querySelector('[name="nombre"]').value,
            descripcion: this.querySelector('[name="descripcion"]').value,
            precio: this.querySelector('[name="precio"]').value,
            categoria_menu_id: this.querySelector('[name="categoria_menu_id"]').value,
            orden_visualizacion: this.querySelector('[name="orden_visualizacion"]').value,
            disponible: this.querySelector('[name="disponible"]').checked,
            activo: this.querySelector('[name="activo"]').checked,
            _token: document.querySelector('meta[name="csrf-token"]').content
        };

        // Si se subió una imagen, agregar el ID
        if (imagenId) {
            itemData.imagen_id = imagenId;
        }

        // Enviar los datos del item
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(itemData)
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(JSON.stringify(data));
            }

            mostrarNotificacion('Item actualizado exitosamente', 'success');
            cerrarModal('editarItemModal');
            setTimeout(() => window.location.reload(), 1000);
        } catch (error) {
            console.error('Error:', error);
            try {
                const errorData = JSON.parse(error.message);
                if (errorData.errors) {
                    Object.values(errorData.errors).forEach(messages => {
                        messages.forEach(message => {
                            mostrarNotificacion(message, 'error');
                        });
                    });
                } else {
                    mostrarNotificacion(errorData.error || 'Error al actualizar el item', 'error');
                }
            } catch (e) {
                mostrarNotificacion('Error al actualizar el item', 'error');
            }
        }
    });

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
}); 