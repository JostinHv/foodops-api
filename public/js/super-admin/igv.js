document.addEventListener('DOMContentLoaded', function() {
        const tasaActual = 18; // Valor inicial, debería venir de la base de datos
        
        // Mostrar/ocultar formulario de edición
        document.getElementById('editarTasaBtn').addEventListener('click', function() {
            document.getElementById('formTasaImpuesto').style.display = 'block';
            document.getElementById('vistaTasaImpuesto').style.display = 'none';
        });
        
        document.getElementById('cancelarEdicionBtn').addEventListener('click', function() {
            document.getElementById('formTasaImpuesto').style.display = 'none';
            document.getElementById('vistaTasaImpuesto').style.display = 'block';
        });
        
        // Guardar nueva tasa
        document.getElementById('formTasaImpuesto').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nuevaTasa = parseFloat(document.getElementById('tasa_impuesto').value);
            
            // Aquí iría la llamada AJAX para guardar la nueva tasa
            console.log('Guardando nueva tasa:', nuevaTasa);
            
            // Simular guardado exitoso
            setTimeout(() => {
                // Actualizar la vista
                document.getElementById('tasaActual').textContent = nuevaTasa;
                document.getElementById('tasaCalculadora').textContent = nuevaTasa;
                
                // Ocultar formulario
                document.getElementById('formTasaImpuesto').style.display = 'none';
                document.getElementById('vistaTasaImpuesto').style.display = 'block';
                
                alert('Tasa de impuesto actualizada correctamente');
            }, 1000);
        });
        
        // Calculadora
        document.getElementById('calcularBtn').addEventListener('click', function() {
            const montoBase = parseFloat(document.getElementById('monto_base').value) || 0;
            const tasaTexto = document.getElementById('pais_calculadora').selectedOptions[0].text;
            const tasaMatch = tasaTexto.match(/\((\d+)%\)/);
            const tasa = tasaMatch ? parseFloat(tasaMatch[1]) : 18;
            
            const impuesto = montoBase * (tasa / 100);
            const total = montoBase + impuesto;
            
            // Mostrar resultados
            document.getElementById('subtotal').textContent = `S/${montoBase.toFixed(2)}`;
            document.getElementById('tasaResultado').textContent = tasa;
            document.getElementById('impuestoCalculado').textContent = `S/${impuesto.toFixed(2)}`;
            document.getElementById('totalCalculado').textContent = `S/${total.toFixed(2)}`;
            
            document.getElementById('resultadosCalculo').style.display = 'block';
        });
        
        // Actualizar tasa en el select de países cuando cambia la tasa configurada
        document.getElementById('tasa_impuesto').addEventListener('change', function() {
            const nuevaTasa = this.value;
            document.getElementById('tasaCalculadora').textContent = nuevaTasa;
            
            // Actualizar la opción de Perú en la calculadora
            const selectPais = document.getElementById('pais_calculadora');
            selectPais.options[0].text = `Perú - IGV (${nuevaTasa}%)`;
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
    // Ocultar resultados inicialmente
    document.getElementById('resultadosCalculo').style.display = 'none';
    
    // Variables para almacenar la tasa actual
    let tasaIGVActual = 18;
    
    // Abrir modal al hacer clic en el botón de editar
    document.getElementById('editarTasaBtn').addEventListener('click', function() {
        // Cargar el valor actual en el modal
        document.getElementById('nuevoPorcentajeIGV').value = tasaIGVActual;
        
        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('editarIGVModal'));
        modal.show();
    });
    
    // Guardar cambios del IGV
    document.getElementById('guardarIGVBtn').addEventListener('click', function() {
        const nuevoValor = parseFloat(document.getElementById('nuevoPorcentajeIGV').value);
        
        if (isNaN(nuevoValor) || nuevoValor < 0 || nuevoValor > 100) {
            alert('Por favor ingrese un valor válido entre 0 y 100');
            return;
        }
        
        // Aquí iría la llamada AJAX para guardar en la base de datos
        // Por ahora simulamos el guardado con éxito
        
        // Actualizar la tasa en todas partes
        tasaIGVActual = nuevoValor;
        actualizarTasaIGV(nuevoValor);
        
        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editarIGVModal'));
        modal.hide();
        
        // Mostrar notificación de éxito
        alert('Tasa de IGV actualizada correctamente a ' + nuevoValor + '%');
    });
    
    // Función para actualizar la tasa en todos los lugares
    function actualizarTasaIGV(nuevaTasa) {
        // Actualizar en la vista principal
        document.getElementById('tasaActual').textContent = nuevaTasa;
        document.getElementById('tasa_impuesto').value = nuevaTasa;
        
        // Actualizar en la calculadora
        document.getElementById('tasaCalculadora').textContent = nuevaTasa;
        document.querySelector('#pais_calculadora option[value="Perú"]').text = 'Perú - IGV (' + nuevaTasa + '%)';
        
        // Si hay un cálculo previo, actualizarlo
        if (document.getElementById('resultadosCalculo').style.display !== 'none') {
            document.getElementById('calcularBtn').click();
        }
    }
    
    // Calculadora de impuestos
    document.getElementById('calcularBtn').addEventListener('click', function() {
        const montoBase = parseFloat(document.getElementById('monto_base').value) || 0;
        const impuesto = montoBase * (tasaIGVActual / 100);
        const total = montoBase + impuesto;
        
        // Mostrar resultados
        document.getElementById('subtotal').textContent = 'S/' + montoBase.toFixed(2);
        document.getElementById('tasaResultado').textContent = tasaIGVActual;
        document.getElementById('impuestoCalculado').textContent = 'S/' + impuesto.toFixed(2);
        document.getElementById('totalCalculado').textContent = 'S/' + total.toFixed(2);
        
        document.getElementById('resultadosCalculo').style.display = 'block';
    });
    
    // Calcular automáticamente al cambiar el monto base
    document.getElementById('monto_base').addEventListener('change', function() {
        if (this.value && document.getElementById('resultadosCalculo').style.display !== 'none') {
            document.getElementById('calcularBtn').click();
        }
    });
});