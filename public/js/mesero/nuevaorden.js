document.addEventListener('DOMContentLoaded', () => {
    const agregarBtn = document.getElementById('agregar-producto');
    const productoSelect = document.getElementById('producto_id');
    const cantidadInput = document.getElementById('cantidad');
    const tabla = document.querySelector('#detalle-tabla tbody');

    agregarBtn.addEventListener('click', () => {
        const productoId = productoSelect.value;
        const productoText = productoSelect.options[productoSelect.selectedIndex].text;
        const cantidad = cantidadInput.value;

        if (!productoId || cantidad < 1) return;

        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${productoText}</td>
            <td class="text-success text-center">${cantidad}</td>
            <td class="text-center">S/. --</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger">Eliminar</button>
            </td>
        `;

        fila.querySelector('button').addEventListener('click', () => {
            fila.remove();
        });

        tabla.appendChild(fila);
    });
});


    const toggleBtn = document.getElementById('toggleMenuBtn');
    const toggleIcon = document.getElementById('toggleIcon');

    toggleBtn.addEventListener('click', () => {
        toggleIcon.classList.toggle('bi-list');
        toggleIcon.classList.toggle('bi-x');
        // Aquí puedes agregar lógica para mostrar/ocultar el menú lateral
    });


