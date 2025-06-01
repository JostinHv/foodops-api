function cargarDatosUsuario(usuario) {
    $('#usuario_id').val(usuario.id);
    $('#nombres').val(usuario.nombres);
    $('#apellidos').val(usuario.apellidos);
    $('#email').val(usuario.email);
    $('#celular').val(usuario.celular);
    $('#rol_id').val(usuario.roles[0]?.id);
    
    // Cargar datos de asignación
    if (usuario.asignaciones_personal && usuario.asignaciones_personal.length > 0) {
        const asignacion = usuario.asignaciones_personal[0];
        $('#sucursal_id').val(asignacion.sucursal_id);
        $('#tipo_asignacion').val(asignacion.tipo);
        $('#notas_asignacion').val(asignacion.notas);
    } else {
        $('#sucursal_id').val('');
        $('#tipo_asignacion').val('permanente');
        $('#notas_asignacion').val('');
    }

    // Cambiar el título del modal y el texto del botón
    $('#usuarioModalLabel').text('Editar Usuario');
    $('#btnGuardar').text('Actualizar');
}

function limpiarFormulario() {
    $('#usuarioForm')[0].reset();
    $('#usuario_id').val('');
    $('#usuarioModalLabel').text('Nuevo Usuario');
    $('#btnGuardar').text('Guardar');
}

// Actualizar la función de guardar para incluir los nuevos campos
$('#usuarioForm').on('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const usuarioId = $('#usuario_id').val();
    const url = usuarioId ? `/tenant/usuarios/${usuarioId}` : '/tenant/usuarios';
    const method = usuarioId ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        method: method,
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#usuarioModal').modal('hide');
            mostrarNotificacion('success', 'Usuario guardado exitosamente');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        },
        error: function(xhr) {
            const error = xhr.responseJSON?.error || 'Error al guardar el usuario';
            mostrarNotificacion('error', error);
        }
    });
}); 