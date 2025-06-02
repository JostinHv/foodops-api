// Hacer las funciones accesibles globalmente
window.agregarCaracteristica = function (valor = '') {
    const container = document.getElementById('caracteristicas-container');
    if (!container) {
        console.error('No se encontró el contenedor de características');
        return;
    }

    const div = document.createElement('div');
    div.className = 'input-group mb-2';

    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'caracteristicas[]';
    input.className = 'form-control';
    input.required = true;
    input.value = valor;

    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'btn btn-outline-danger';
    button.innerHTML = '<i class="bi bi-trash"></i>';
    button.onclick = function () {
        eliminarCaracteristica(this);
    };

    div.appendChild(input);
    div.appendChild(button);
    container.appendChild(div);
    console.log('Característica agregada:', valor);
};

window.eliminarCaracteristica = function (button) {
    const container = document.getElementById('caracteristicas-container');
    if (container && container.children.length > 1) {
        button.closest('.input-group').remove();
    }
};

window.toggleCaracteristicas = function (planId) {
    const container = document.getElementById('caracteristicas-container');
    const toggleButton = document.getElementById('toggle-caracteristicas');

    if (container.style.display === 'none') {
        // Si está oculto, cargar y mostrar las características
        fetch(`/superadmin/planes/${planId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (!data.success || !data.plan) {
                    throw new Error('Datos del plan no encontrados');
                }

                const plan = data.plan;
                const textarea = container.querySelector('textarea[name="caracteristicas"]');

                // Convertir el array de características en una cadena separada por comas
                if (plan.caracteristicas && plan.caracteristicas.adicionales) {
                    textarea.value = plan.caracteristicas.adicionales.join(', ');
                }

                container.style.display = 'block';
                toggleButton.innerHTML = '<i class="bi bi-eye-slash me-2"></i>Ocultar Características';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cargar las características: ' + error.message);
            });
    } else {
        // Si está visible, ocultar
        container.style.display = 'none';
        toggleButton.innerHTML = '<i class="bi bi-eye me-2"></i>Mostrar Características';
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const editarModal = document.getElementById('editarPlanModal');
    if (editarModal) {
        editarModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const planId = button.getAttribute('data-plan');
            if (!planId) return;

            // Cargar los datos del plan
            fetch(`/superadmin/planes/${planId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.success || !data.plan) {
                        throw new Error('Datos del plan no encontrados');
                    }

                    const plan = data.plan;
                    const form = document.getElementById('formEditarPlan');
                    if (!form) return;

                    // Actualizar la acción del formulario
                    form.action = `/superadmin/planes/${planId}`;

                    // Llenar los campos básicos
                    form.querySelector('[name="nombre"]').value = plan.nombre || '';
                    form.querySelector('[name="descripcion"]').value = plan.descripcion || '';
                    form.querySelector('[name="precio"]').value = plan.precio || '';
                    form.querySelector('[name="intervalo"]').value = plan.intervalo || '';
                    form.querySelector('[name="limite_usuarios"]').value = plan.limite_usuarios || '';
                    form.querySelector('[name="limite_restaurantes"]').value = plan.limite_restaurantes || '';
                    form.querySelector('[name="limite_sucursales"]').value = plan.limite_sucursales || '';
                    form.querySelector('[name="caracteristicas"]').value = plan.caracteristicas || '';

                    // Llenar las características
                    // const textarea = form.querySelector('[name="caracteristicas"]');
                    // if (textarea && plan.caracteristicas && plan.caracteristicas.adicionales) {
                    //     textarea.value = plan.caracteristicas.adicionales.join(', ');
                    // }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cargar los datos del plan: ' + error.message);
                });
        });

        // Limpiar el modal cuando se cierra
        editarModal.addEventListener('hidden.bs.modal', function () {
            const form = this.querySelector('form');
            if (form) {
                form.reset();
            }
        });
    }

    // Validación de formularios
    document.querySelectorAll('#nuevoPlanModal form, #editarPlanModal form').forEach(form => {
        form.addEventListener('submit', function (e) {
            const caracteristicasInput = form.querySelector('[name="caracteristicas"]');
            const caracteristicas = caracteristicasInput.value.trim();
            let isValid = true;

            // Remover clases de validación previas
            caracteristicasInput.classList.remove('is-invalid');
            const existingFeedback = caracteristicasInput.nextElementSibling;
            if (existingFeedback && existingFeedback.classList.contains('invalid-feedback')) {
                existingFeedback.remove();
            }

            if (!caracteristicas) {
                e.preventDefault();
                isValid = false;
                caracteristicasInput.classList.add('is-invalid');
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = 'Debe agregar al menos una característica al plan';
                caracteristicasInput.parentNode.appendChild(feedback);
            }

            // Validar que haya al menos una característica
            const caracteristicasArray = caracteristicas.split(',').map(c => c.trim()).filter(c => c);
            if (caracteristicasArray.length === 0) {
                e.preventDefault();
                isValid = false;
                caracteristicasInput.classList.add('is-invalid');
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = 'Debe agregar al menos una característica al plan';
                caracteristicasInput.parentNode.appendChild(feedback);
            }

            // Agregar clase de validación si es válido
            if (isValid) {
                caracteristicasInput.classList.add('is-valid');
            }

            return isValid;
        });

        // Limpiar validación al cambiar el valor
        const caracteristicasInput = form.querySelector('[name="caracteristicas"]');
        caracteristicasInput.addEventListener('input', function() {
            this.classList.remove('is-invalid', 'is-valid');
            const feedback = this.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.remove();
            }
        });
    });

    // Manejar el cambio de estado de los planes
    document.querySelectorAll('form[action*="toggle-activo"]').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const button = form.querySelector('button[type="submit"]');
            const planId = form.action.split('/').pop();

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(Object.fromEntries(new FormData(form)))
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const cardHeader = button.closest('.card-header');
                        if (data.activo) {
                            cardHeader.classList.remove('bg-secondary');
                            cardHeader.classList.add('bg-success');
                            button.classList.remove('btn-info');
                            button.classList.add('btn-warning');
                            button.innerHTML = '<i class="bi bi-toggle-on"></i>';
                        } else {
                            cardHeader.classList.remove('bg-success');
                            cardHeader.classList.add('bg-secondary');
                            button.classList.remove('btn-warning');
                            button.classList.add('btn-info');
                            button.innerHTML = '<i class="bi bi-toggle-off"></i>';
                        }
                    } else {
                        throw new Error(data.message || 'Error al cambiar el estado del plan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al cambiar el estado del plan: ' + error.message);
                });
        });
    });

    // Función para cargar los detalles del plan en el modal de visualización
    const verModal = document.getElementById('verPlanModal');
    if (verModal) {
        verModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const planId = button.getAttribute('data-plan');

            // Cargar datos del plan
            fetch(`/planes/${planId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.plan) {
                        throw new Error('No se encontraron datos del plan');
                    }

                    const plan = data.plan;

                    // Actualizar los campos con los datos del plan
                    const elementos = {
                        'plan-nombre': plan.nombre || 'No especificado',
                        'plan-descripcion': plan.descripcion || 'No especificada',
                        'plan-precio': plan.precio ? `S/ ${plan.precio}` : 'No especificado',
                        'plan-intervalo': plan.intervalo === 'mes' ? 'Mensual' : 'Anual',
                        'plan-limites': `
                            <li>Restaurantes: ${plan.limite_restaurantes || 'Sin límite'}</li>
                            <li>Usuarios: ${plan.limite_usuarios || 'Sin límite'}</li>
                            <li>Sucursales: ${plan.limite_sucursales || 'Sin límite'}</li>
                        `
                    };

                    // Actualizar cada elemento
                    Object.entries(elementos).forEach(([id, value]) => {
                        const elemento = document.getElementById(id);
                        if (elemento) {
                            elemento.innerHTML = value;
                        }
                    });

                    // Actualizar el estado con el color correspondiente
                    const estadoElement = document.getElementById('plan-estado');
                    if (estadoElement) {
                        estadoElement.className = `badge ${plan.activo ? 'bg-success' : 'bg-warning'}`;
                        estadoElement.textContent = plan.activo ? 'Activo' : 'Inactivo';
                    }

                    // Actualizar características
                    const caracteristicasElement = document.getElementById('plan-caracteristicas');
                    if (caracteristicasElement) {
                        if (plan.caracteristicas && plan.caracteristicas.length > 0) {
                            caracteristicasElement.innerHTML = `
                                <ul class="list-unstyled">
                                    ${plan.caracteristicas.map(c => `<li><i class="bi bi-check-circle-fill text-success me-2"></i>${c}</li>`).join('')}
                                </ul>
                            `;
                        } else {
                            caracteristicasElement.innerHTML = '<p class="text-muted">No hay características definidas</p>';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error al cargar los detalles del plan:', error);
                    alert('Error al cargar los detalles del plan: ' + error.message);
                });
        });
    }

    // Confirmación de eliminación
    const deleteButtons = document.querySelectorAll('form[action*="destroy"] button');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            if (!confirm('¿Estás seguro de eliminar este plan? Esta acción no se puede deshacer.')) {
                e.preventDefault();
            }
        });
    });

    // Inicialización
    if (document.getElementById('caracteristicas-container')) {
        agregarCaracteristica();
    }
});




 