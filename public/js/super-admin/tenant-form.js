document.addEventListener('DOMContentLoaded', function() {
    // Manejo de la selección del plan
    const planSelect = document.getElementById('plan_suscripcion_id');
    const planPreview = document.getElementById('plan-preview');
    const precioPlan = document.getElementById('precio-plan');
    const intervaloPlan = document.getElementById('intervalo-plan');
    const caracteristicasPlan = document.getElementById('caracteristicas-plan');

    if (planSelect) {
        planSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];

            if (selectedOption.value) {
                // Mostrar la previsualización
                planPreview.classList.remove('d-none');

                // Actualizar precio
                precioPlan.textContent = new Intl.NumberFormat('es-PE', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(selectedOption.dataset.precio);

                // Obtener y mostrar características
                const caracteristicas = JSON.parse(selectedOption.dataset.caracteristicas || '[]');
                caracteristicasPlan.innerHTML = caracteristicas.map(c =>
                    `<li><i class="bi bi-check2 text-success me-2"></i>${c}</li>`
                ).join('');

                // Actualizar intervalo
                const intervalText = selectedOption.textContent.split('/')[1] || 'mes';
                intervaloPlan.textContent = intervalText;
            } else {
                // Ocultar la previsualización si no hay plan seleccionado
                planPreview.classList.add('d-none');
            }
        });

        // Trigger el evento change si hay un plan seleccionado inicialmente
        if (planSelect.value) {
            planSelect.dispatchEvent(new Event('change'));
        }
    }

    // Previsualización del logo
    const logoInput = document.getElementById('logo');
    const logoPreview = document.getElementById('logo-preview');

    if (logoInput && logoPreview) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.innerHTML = `
                        <img src="${e.target.result}"
                            class="img-thumbnail mt-2"
                            style="max-height: 100px"
                            alt="Logo preview">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Manejo del switch de estado
    const estadoSwitch = document.getElementById('activo');
    const estadoTexto = document.getElementById('estado-texto');

    if (estadoSwitch && estadoTexto) {
        estadoSwitch.addEventListener('change', function() {
            if (this.checked) {
                estadoTexto.textContent = 'Tenant Activo';
                estadoTexto.className = 'text-success';
            } else {
                estadoTexto.textContent = 'Tenant Inactivo';
                estadoTexto.className = 'text-danger';
            }
        });

        // Trigger inicial para establecer el texto correcto
        estadoSwitch.dispatchEvent(new Event('change'));
    }
});
