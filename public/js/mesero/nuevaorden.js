document.addEventListener('DOMContentLoaded', function () {
    const productoSelect = document.getElementById('producto_id');
    const cantidadInput = document.getElementById('cantidad');
    const agregarBtn = document.getElementById('agregar-producto');
    const tabla = document.getElementById('detalle-tabla').getElementsByTagName('tbody')[0];
    const formulario = document.querySelector('form');

    let productos = [];

    agregarBtn.addEventListener('click', function () {
        const producto = productoSelect.options[productoSelect.selectedIndex];
        if (!producto.value) return;

        const cantidad = parseInt(cantidadInput.value);
        const precio = parseFloat(producto.text.split('S/. ')[1]);
        const productoId = producto.value;
        const nombreProducto = producto.text.split(' - ')[0];

        // Buscar si el producto ya existe en la tabla
        const productoExistente = productos.findIndex(p => p.producto_id === productoId);

        if (productoExistente !== -1) {
            // Actualizar cantidad y precio del producto existente
            const nuevaCantidad = productos[productoExistente].cantidad + cantidad;
            const nuevoPrecioTotal = (nuevaCantidad * precio).toFixed(2);

            productos[productoExistente].cantidad = nuevaCantidad;
            productos[productoExistente].precio_total = nuevoPrecioTotal;

            // Actualizar la fila en la tabla
            const filas = tabla.getElementsByTagName('tr');
            filas[productoExistente].children[1].textContent = nuevaCantidad;
            filas[productoExistente].children[2].textContent = `S/. ${nuevoPrecioTotal}`;
        } else {
            // Agregar nuevo producto
            const precioTotal = (cantidad * precio).toFixed(2);
            const fila = tabla.insertRow();
            fila.innerHTML = `
                            <td>${nombreProducto}</td>
                            <td>${cantidad}</td>
                            <td>S/. ${precioTotal}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm eliminar-fila">
                                    Eliminar
                                </button>
                            </td>
                        `;

            productos.push({
                producto_id: productoId,
                cantidad: cantidad,
                precio_total: precioTotal
            });

            // Agregar evento para eliminar fila
            fila.querySelector('.eliminar-fila').addEventListener('click', function () {
                const index = Array.from(tabla.children).indexOf(fila);
                productos.splice(index, 1);
                fila.remove();
            });
        }

        // Limpiar selección
        productoSelect.value = '';
        cantidadInput.value = '1';
    });

    formulario.addEventListener('submit', function (e) {
        e.preventDefault();

        if (productos.length === 0) {
            alert('Debe agregar al menos un producto a la orden');
            return;
        }

        // Limpiar campos ocultos anteriores
        const camposOcultos = formulario.querySelectorAll('input[name^="productos"]');
        camposOcultos.forEach(campo => campo.remove());

        // Agregar campos ocultos para cada producto
        productos.forEach((producto, index) => {
            const productoId = document.createElement('input');
            productoId.type = 'hidden';
            productoId.name = `productos[${index}][producto_id]`;
            productoId.value = producto.producto_id;

            const cantidad = document.createElement('input');
            cantidad.type = 'hidden';
            cantidad.name = `productos[${index}][cantidad]`;
            cantidad.value = producto.cantidad;

            formulario.appendChild(productoId);
            formulario.appendChild(cantidad);
        });

        this.submit();
    });
});
