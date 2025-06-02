class Orden {
    constructor() {
        this.productos = [];
    }

    agregarProducto(producto) {
        const productoExistente = this.productos.findIndex(p => p.producto_id === producto.producto_id);

        if (productoExistente !== -1) {
            this.productos[productoExistente].incrementarCantidad(producto.cantidad);
        } else {
            this.productos.push(producto);
        }
    }

    eliminarProducto(productoId) {
        const index = this.productos.findIndex(p => p.producto_id === productoId);
        if (index !== -1) {
            this.productos.splice(index, 1);
        }
    }

    getProductos() {
        return this.productos;
    }

    tieneProductos() {
        return this.productos.length > 0;
    }

    toRequestFormat() {
        return {
            productos: this.productos.map(producto => producto.toRequestFormat())
        };
    }

    validar() {
        if (this.productos.length === 0) {
            throw new Error('Debe agregar al menos un producto a la orden');
        }
        return true;
    }
}
