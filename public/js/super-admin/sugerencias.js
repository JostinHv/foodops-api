document.addEventListener('DOMContentLoaded', function () {
    // Ver detalle sugerencia
    document.querySelectorAll('.ver-sugerencia-btn').forEach(btn => {
        btn.addEventListener('click', async function () {
            const id = this.getAttribute('data-id');
            const modal = new bootstrap.Modal(document.getElementById('verSugerenciaModal'));
            const detalle = document.getElementById('detalleSugerencia');
            detalle.textContent = 'Cargando...';
            try {
                const response = await fetch(`/superadmin/sugerencias/${id}`);
                const data = await response.json();
                detalle.textContent = data.sugerencia.sugerencia;
            } catch (e) {
                detalle.textContent = 'Error al cargar la sugerencia';
            }
            modal.show();
        });
    });

    // Cambiar estado sugerencia
    let sugerenciaIdEstado = null;
    document.querySelectorAll('.cambiar-estado-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            sugerenciaIdEstado = this.getAttribute('data-id');
            document.getElementById('sugerenciaIdEstado').value = sugerenciaIdEstado;
            const estadoActual = this.getAttribute('data-estado');
            document.getElementById('nuevoEstado').value = estadoActual;
            const modal = new bootstrap.Modal(document.getElementById('cambiarEstadoModal'));
            modal.show();
        });
    });
    document.getElementById('confirmarCambioEstado').addEventListener('click', async function () {
        const id = document.getElementById('sugerenciaIdEstado').value;
        const estado = document.getElementById('nuevoEstado').value;
        try {
            const response = await fetch(`/superadmin/sugerencias/${id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ estado })
            });
            if (!response.ok) throw new Error('Error al actualizar el estado');
            window.location.reload();
        } catch (e) {
            alert(e.message);
        }
    });

    // Eliminar sugerencia
    let sugerenciaIdEliminar = null;
    document.querySelectorAll('.eliminar-sugerencia-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            sugerenciaIdEliminar = this.getAttribute('data-id');
            const modal = new bootstrap.Modal(document.getElementById('eliminarSugerenciaModal'));
            modal.show();
        });
    });
    document.getElementById('confirmarEliminarSugerencia').addEventListener('click', async function () {
        try {
            const response = await fetch(`/superadmin/sugerencias/${sugerenciaIdEliminar}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            if (!response.ok) throw new Error('Error al eliminar la sugerencia');
            window.location.reload();
        } catch (e) {
            alert(e.message);
        }
    });
}); 