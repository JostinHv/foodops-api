document.addEventListener('DOMContentLoaded', function () {
    try {
        console.log('Inicializando nuevaorden.js...');

        // Elementos del DOM
        const ordenForm = document.getElementById('ordenForm');
    const tabla = document.getElementById('detalle-tabla').getElementsByTagName('tbody')[0];
        const totalProductosBadge = document.getElementById('total-productos');
        const totalOrdenElement = document.getElementById('total-orden');
        const buscarProductoInput = document.getElementById('buscarProducto');
        const mesaInput = document.getElementById('mesa_id');

        if (!ordenForm || !tabla || !totalProductosBadge || !totalOrdenElement) {
            throw new Error('No se pudieron encontrar todos los elementos necesarios en el DOM');
        }

        console.log('Elementos del DOM encontrados correctamente');

        // Inicializar el servicio de orden
        window.ordenService = new OrdenService(tabla);
        console.log('OrdenService inicializado');

        // Función para actualizar totales
        function actualizarTotales() {
            const productos = ordenService.orden.getProductos();
            const total = productos.reduce((sum, producto) => sum + parseFloat(producto.precioTotal), 0);

            totalProductosBadge.textContent = `${productos.length} productos`;
            totalOrdenElement.textContent = `S/. ${total.toFixed(2)}`;

            console.log('Totales actualizados:', {
                cantidadProductos: productos.length,
                total: total
            });
        }

        // Función para actualizar cantidad de producto
        function actualizarCantidadProducto(card, cantidad) {
            try {
                const productoId = parseInt(card.dataset.id);
                if (isNaN(productoId)) {
                    throw new Error('ID de producto inválido');
                }

                console.log('Actualizando cantidad de producto:', {
                    id: productoId,
                    cantidad: cantidad
                });

                const nombreProducto = card.dataset.nombre;
                const precio = parseFloat(card.dataset.precio);

                if (cantidad > 0) {
                    const producto = new Producto(productoId, nombreProducto, precio, cantidad);
                    ordenService.agregarProducto(producto);
        } else {
                    ordenService.eliminarProducto(productoId);
                }

                actualizarTotales();
                console.log('Cantidad actualizada exitosamente');
            } catch (error) {
                console.error('Error al actualizar cantidad:', error);
                alert('Error al actualizar la cantidad: ' + error.message);
            }
        }

        // Evento para buscar productos
        buscarProductoInput.addEventListener('input', function(e) {
            const busqueda = e.target.value.toLowerCase();
            document.querySelectorAll('.producto-item').forEach(item => {
                const nombre = item.dataset.nombre;
                item.style.display = nombre.includes(busqueda) ? '' : 'none';
            });
        });

        // Eventos para los botones de cantidad
        document.querySelectorAll('.producto-card').forEach(card => {
            const input = card.querySelector('.cantidad-input');
            const btnMenos = card.querySelector('.btn-cantidad-menos');
            const btnMas = card.querySelector('.btn-cantidad-mas');

            btnMenos.addEventListener('click', () => {
                const nuevaCantidad = Math.max(0, parseInt(input.value) - 1);
                input.value = nuevaCantidad;
                actualizarCantidadProducto(card, nuevaCantidad);
            });

            btnMas.addEventListener('click', () => {
                const nuevaCantidad = parseInt(input.value) + 1;
                input.value = nuevaCantidad;
                actualizarCantidadProducto(card, nuevaCantidad);
            });

            input.addEventListener('change', () => {
                const nuevaCantidad = Math.max(0, parseInt(input.value) || 0);
                input.value = nuevaCantidad;
                actualizarCantidadProducto(card, nuevaCantidad);
            });
        });

        // Evento para eliminar producto
        tabla.addEventListener('click', function(e) {
            const btnEliminar = e.target.closest('.eliminar-producto');
            if (btnEliminar) {
                try {
                    const productoId = parseInt(btnEliminar.getAttribute('data-producto-id'));
                    console.log('Intentando eliminar producto con ID:', productoId);

                    if (isNaN(productoId)) {
                        throw new Error('ID de producto inválido');
                    }

                    // Encontrar y resetear la tarjeta correspondiente
                    const card = document.querySelector(`.producto-card[data-id="${productoId}"]`);
                    if (card) {
                        const input = card.querySelector('.cantidad-input');
                        input.value = 0;
                    }

                    // Eliminar el producto
                    ordenService.eliminarProducto(productoId);
                    actualizarTotales();
                    console.log('Producto eliminado exitosamente');
                } catch (error) {
                    console.error('Error al eliminar producto:', error);
                    alert('Error al eliminar el producto: ' + error.message);
                }
            }
        });

        // Manejo de selección de mesas
        document.querySelectorAll('.mesa-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remover selección previa
                document.querySelectorAll('.mesa-card').forEach(c => {
                    c.classList.remove('selected');
            });

                // Agregar selección a la mesa actual
                this.classList.add('selected');

                // Actualizar el input oculto con el ID de la mesa
                const mesaId = this.dataset.id;
                mesaInput.value = mesaId;

                console.log('Mesa seleccionada:', mesaId);
    });
        });

        // Evento para enviar formulario
        ordenForm.addEventListener('submit', function (e) {
            try {
                console.log('Iniciando envío del formulario...');
        e.preventDefault();

                // Validar que se haya seleccionado una mesa
                if (!mesaInput.value) {
                    alert('Por favor, seleccione una mesa');
            return;
        }

                // Preparar el formulario con los productos
                if (ordenService.prepararFormulario(this)) {
                    const datosFormulario = {
                        cliente: this.querySelector('[name="cliente"]').value,
                        mesa_id: mesaInput.value,
                        productos: ordenService.orden.toRequestFormat().productos
                    };

                    console.log('Datos a enviar:', datosFormulario);
                    console.log('Formulario preparado correctamente, enviando...');

                    // Verificar que los productos se hayan agregado correctamente al formulario
                    const productosInputs = this.querySelectorAll('input[name^="productos"]');
                    console.log('Campos de productos en el formulario:', productosInputs.length);
                    productosInputs.forEach(input => {
                        console.log('Campo:', input.name, 'Valor:', input.value);
        });

        this.submit();
                }
            } catch (error) {
                console.error('Error al enviar el formulario:', error);
                alert('Error al enviar el formulario: ' + error.message);
            }
        });

        console.log('Inicialización completada exitosamente');
    } catch (error) {
        console.error('Error en la inicialización:', error);
        alert('Error al inicializar la página: ' + error.message);
    }
});
