class OrdenService {
    constructor(tabla) {
        try {
            console.log('Inicializando OrdenService...');
            if (!tabla) {
                throw new Error('La tabla es requerida para inicializar OrdenService');
            }
            this.tabla = tabla;
            this.orden = new Orden();
            console.log('OrdenService inicializado correctamente');
        } catch (error) {
            console.error('Error al inicializar OrdenService:', error);
            throw error;
        }
    }

    agregarProducto(producto) {
        try {
            console.log('Agregando producto a la orden:', producto);
            this.orden.agregarProducto(producto);
            this.actualizarTabla();
            console.log('Producto agregado exitosamente');
        } catch (error) {
            console.error('Error al agregar producto:', error);
            throw error;
        }
    }

    eliminarProducto(productoId) {
        try {
            console.log('Eliminando producto con ID:', productoId);
            this.orden.eliminarProducto(productoId);
            this.actualizarTabla();
            console.log('Producto eliminado exitosamente');
        } catch (error) {
            console.error('Error al eliminar producto:', error);
            throw error;
        }
    }

    actualizarTabla() {
        try {
            console.log('Actualizando tabla de productos...');
            this.tabla.innerHTML = '';
            const productos = this.orden.getProductos();
            console.log('Productos a mostrar:', productos);

            productos.forEach((producto) => {
                const fila = document.createElement('tr');
                fila.setAttribute('data-producto-id', producto.producto_id);
                fila.innerHTML = `
                    <td>${producto.nombre}</td>
                    <td class="text-center">${producto.cantidad}</td>
                    <td class="text-end">S/. ${parseFloat(producto.precioTotal).toFixed(2)}</td>
                    <td class="text-end">
                        <button type="button" class="btn btn-danger btn-sm eliminar-producto" data-producto-id="${producto.producto_id}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;
                this.tabla.appendChild(fila);
            });
            console.log('Tabla actualizada exitosamente');
        } catch (error) {
            console.error('Error al actualizar tabla:', error);
            throw error;
        }
    }

    prepararFormulario(formulario) {
        try {
            console.log('Preparando formulario para envío...');

            if (!formulario) {
                throw new Error('El formulario es requerido');
            }

            this.orden.validar();
            console.log('Orden validada correctamente');

            // Validar que haya al menos un producto
            if (!this.orden.tieneProductos()) {
                throw new Error('Debe agregar al menos un producto a la orden');
            }

            // Limpiar campos ocultos anteriores
            const camposAnteriores = formulario.querySelectorAll('input[name^="productos"]');
            console.log('Eliminando campos anteriores:', camposAnteriores.length);
            camposAnteriores.forEach(campo => campo.remove());

            // Agregar campos ocultos para cada producto
            const productos = this.orden.toRequestFormat().productos;
            console.log('Datos de la orden a enviar:', productos);

            productos.forEach((producto, index) => {
                // Agregar campo para el ID del producto
                const inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = `productos[${index}][producto_id]`;
                inputId.value = producto.producto_id;
                formulario.appendChild(inputId);

                // Agregar campo para la cantidad
                const inputCantidad = document.createElement('input');
                inputCantidad.type = 'hidden';
                inputCantidad.name = `productos[${index}][cantidad]`;
                inputCantidad.value = producto.cantidad;
                formulario.appendChild(inputCantidad);
            });

            console.log('Formulario preparado exitosamente');
            return true;
        } catch (error) {
            console.error('Error al preparar formulario:', error);
            alert(error.message);
            return false;
        }
    }
}
