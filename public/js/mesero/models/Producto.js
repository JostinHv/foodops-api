class Producto {
    constructor(id, nombre, precio, cantidad = 1) {
        this.producto_id = id;
        this.nombre = nombre;
        this.precio = precio;
        this.cantidad = cantidad;
    }

    get precioTotal() {
        return (this.cantidad * this.precio).toFixed(2);
    }

    incrementarCantidad(cantidad) {
        this.cantidad += cantidad;
    }

    toRequestFormat() {
        return {
            producto_id: this.producto_id,
            cantidad: this.cantidad
        };
    }
}
