let grupoSeleccionadoId = null;

    function setGrupoId(id) {
        grupoSeleccionadoId = id;
        // Aquí podrías precargar datos del grupo si lo deseas
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("btnEditarGrupo").addEventListener("click", function () {
            // Aquí puedes hacer que se cargue el modal de editar con datos del grupo
            const modalNuevo = new bootstrap.Modal(document.getElementById('nuevoGrupoModal'));
            modalNuevo.show();

            // Cambia botón y título
            document.querySelector('#nuevoGrupoModalLabel').textContent = "Editar Grupo";
            document.querySelector('#nuevoGrupoModal button[type="submit"]').textContent = "Guardar Cambios";

            // Puedes cargar datos vía AJAX si lo deseas (opcional)
            // Ejemplo ficticio:
            // fetch(`/grupos/${grupoSeleccionadoId}/edit`)
        });

        document.getElementById("btnEliminarGrupo").addEventListener("click", function () {
            if (confirm("¿Estás seguro de eliminar este grupo?")) {
                // Redirigir o usar fetch/AJAX para eliminar
                window.location.href = `/grupos/${grupoSeleccionadoId}/delete`; // O una ruta POST con form
            }
        });

        document.getElementById("btnVerDetalle").addEventListener("click", function () {
            window.location.href = `/grupos/${grupoSeleccionadoId}`;
        });
    });
