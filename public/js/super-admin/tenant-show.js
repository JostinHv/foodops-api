/**
 * Clase para manejar la lógica de la vista de detalles del tenant
 */
class TenantShowManager {
    constructor() {
        this.initializeEventListeners();
    }

    /**
     * Inicializa todos los event listeners necesarios
     */
    initializeEventListeners() {
        this.initializePasswordToggle();
        this.initializeTenantEditModal();
        this.initializeLogoPreview();
        this.initializeAlertAutoClose();
        this.initializeUserStateButtons();
    }

    /**
     * Maneja la funcionalidad de mostrar/ocultar contraseña
     */
    initializePasswordToggle() {
        const togglePassword = () => {
            const passwordInput = document.getElementById('password');
            const icon = document.querySelector('.input-group button i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        };

        const toggleButton = document.querySelector('.input-group button');
        if (toggleButton) {
            toggleButton.addEventListener('click', togglePassword);
        }
    }

    /**
     * Maneja la funcionalidad del modal de edición del tenant
     */
    initializeTenantEditModal() {
        const editarTenantModal = document.getElementById('editarTenantModal');
        if (editarTenantModal) {
            // Inicializar el modal de Bootstrap
            const modal = new bootstrap.Modal(editarTenantModal);

            // Evento que se dispara justo antes de que se muestre el modal
            editarTenantModal.addEventListener('show.bs.modal', (event) => {
                // Botón que activó el modal
                const button = event.relatedTarget;

                // Obtener el tenantId del atributo data-tenant-id del botón
                // Usamos Number() para asegurarnos de que sea un número o NaN si no es válido
                const tenantId = button ? Number(button.getAttribute('data-tenant-id')) : NaN;
                const form = editarTenantModal.querySelector('form');

                // Verificar si el tenantId es un número válido
                if (!isNaN(tenantId) && tenantId > 0) {
                    console.log(`Cargando datos para Tenant ID: ${tenantId}`);

                    // Actualizar la URL del formulario
                    form.action = `/superadmin/tenant/${tenantId}`;

                    // Llenar los campos básicos del formulario con datos del botón
                    this.fillTenantFormFields(button);

                    // Cargar datos adicionales como el plan actual via fetch
                    this.loadTenantPlan(tenantId);
                } else {
                    console.error('Error: No se pudo obtener un ID de Tenant válido del botón que activó el modal.', button);
                    // Opcional: Cerrar el modal o mostrar un mensaje de error al usuario
                    modal.hide(); // Ocultar el modal si no hay un ID válido
                }
            });

            // Evento que se dispara cuando el modal ha terminado de mostrarse (CSS transitions completadas)
            // Este es un buen momento para manipular el foco si es necesario y puede ayudar con aria-hidden
            editarTenantModal.addEventListener('shown.bs.modal', () => {
                console.log('Modal de edición de tenant mostrado completamente.');
                // Intentar enfocar el primer campo input para mejorar la accesibilidad
                // Un pequeño retraso a veces ayuda a que Bootstrap termine sus tareas
                setTimeout(() => {
                    const firstInput = editarTenantModal.querySelector('input:not([type="hidden"]), select, textarea');
                    if (firstInput) {
                        firstInput.focus();
                    }
                }, 100);
            });

            // Evento que se dispara cuando el modal ha terminado de ocultarse
            editarTenantModal.addEventListener('hidden.bs.modal', () => {
                console.log('Modal de edición de tenant oculto.');
                // Opcional: Resetear el formulario para la próxima vez que se abra
                // editarTenantModal.querySelector('form').reset();
                // Asegurarse de que el preview del logo se limpie
                const logoPreview = document.getElementById('logo-preview');
                if (logoPreview) {
                    logoPreview.innerHTML = '';
                }
            });
        }
    }

    /**
     * Llena los campos del formulario con los datos del tenant obtenidos del botón que activó el modal.
     * Utiliza atributos data-* del botón.
     * @param {HTMLElement} button - El botón que activó el modal.
     */
    fillTenantFormFields(button) {
        // Asegurarse de que el botón existe antes de intentar leer atributos
        if (!button) {
            console.error('fillTenantFormFields llamado sin un botón válido.');
            return;
        }

        // Obtener elementos del formulario
        const dominioInput = document.getElementById('dominio');
        const nombreEmpresaInput = document.getElementById('nombre_empresa');
        const emailInput = document.getElementById('editEmail'); // Usar el ID único
        const telefonoInput = document.getElementById('telefono');
        const direccionInput = document.getElementById('direccion');
        const activoCheckbox = document.getElementById('activo');
        const planSuscripcionSelect = document.getElementById('plan_suscripcion_id'); // También llenar este aquí si es posible del botón

        // Llenar campos si existen
        if (dominioInput) dominioInput.value = button.getAttribute('data-tenant-dominio') || '';
        if (nombreEmpresaInput) nombreEmpresaInput.value = button.getAttribute('data-tenant-nombre') || '';
        if (emailInput) emailInput.value = button.getAttribute('data-tenant-email') || '';
        if (telefonoInput) telefonoInput.value = button.getAttribute('data-tenant-telefono') || '';
        if (direccionInput) direccionInput.value = button.getAttribute('data-tenant-direccion') || '';

        // Llenar estado activo del checkbox
        if (activoCheckbox) {
            activoCheckbox.checked = button.getAttribute('data-tenant-activo') === '1';
        }

        // El plan de suscripción se carga via fetch en loadTenantPlan para obtener el valor actual del backend,
        // pero si tuvieras el plan_suscripcion_id en un data-atributo, podrías llenarlo aquí también.
        // Ejemplo: if (planSuscripcionSelect) planSuscripcionSelect.value = button.getAttribute('data-tenant-plan-id') || '';
    }

    /**
     * Carga el plan de suscripción actual del tenant haciendo una solicitud fetch al backend.
     * @param {number} tenantId - El ID del tenant para el que se cargará el plan.
     */
    loadTenantPlan(tenantId) {
        // Asegurarse de que tenantId es un número válido antes de la solicitud
        if (isNaN(tenantId) || tenantId <= 0) {
            console.error('loadTenantPlan llamado con un tenantId inválido:', tenantId);
            return;
        }
        const planSuscripcionSelect = document.getElementById('plan_suscripcion_id');
        if (!planSuscripcionSelect) {
            console.warn('Elemento select para plan de suscripción no encontrado.');
            return;
        }

        fetch(`/superadmin/tenant/detalles/${tenantId}`)
            .then(response => {
                console.log('Response from /superadmin/tenant/:', response);
                if (!response.ok) {
                    // Si la respuesta no es OK (ej. 404, 500), lanzar un error con el estado y texto
                    return response.text().then(text => {
                        console.error('HTTP Error Response Text:', text);
                        throw new Error(`HTTP status ${response.status}: ${text.substring(0, 200)}...`);
                    });
                }
                // Clonar la respuesta antes de intentar json() y text()
                const clonedResponse = response.clone();
                return response.json().catch(jsonError => {
                    // Si falla el parseo a JSON, leer como texto para depurar
                    clonedResponse.text().then(text => {
                        console.error('JSON parsing failed. Response text:', text);
                    });
                    throw jsonError; // Re-lanzar el error original de JSON
                });
            })
            .then(data => {
                console.log('Datos del tenant cargados para plan:', data);
                if (data.tenant && data.tenant.suscripcion.plan_suscripcion_id) {
                    // Establecer el valor seleccionado en el select
                    planSuscripcionSelect.value = data.tenant.suscripcion.plan_suscripcion_id;
                    // Trigger change event si es necesario para alguna otra lógica
                    // planSuscripcionSelect.dispatchEvent(new Event('change'));
                } else {
                    console.warn('No se encontró el plan de suscripción en los datos del tenant o no se proporcionó.', data);
                    // Opcional: Resetear el select si no hay plan
                    planSuscripcionSelect.value = '';
                }
            })
            .catch(error => console.error('Error al cargar el plan del tenant:', error));
    }

    /**
     * Maneja la previsualización del logo
     */
    initializeLogoPreview() {
        const logoInput = document.getElementById('logo');
        const logoPreview = document.getElementById('logo-preview');

        if (logoInput && logoPreview) {
            logoInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        logoPreview.innerHTML = `
                            <img src="${e.target.result}"
                                class="img-thumbnail"
                                style="max-height: 100px"
                                alt="Logo preview">`;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    /**
     * Maneja el cierre automático de las alertas
     */
    initializeAlertAutoClose() {
        const alerts = document.querySelectorAll('.alert.alert-dismissible');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 150);
            }, 5000);
        });
    }

    /**
     * Maneja los clics en los botones de cambio de estado de usuario (toggle).
     */
    initializeUserStateButtons() {
        document.querySelectorAll('.estado-usuario-btn').forEach(button => {
            // Verificar si el event listener ya fue añadido para evitar duplicados
            // (Esto es útil si el contenido de la tabla se recarga via AJAX u otra forma)
            if (button.dataset.listenerAttached) {
                return;
            }

            button.addEventListener('click', (e) => {
                e.preventDefault(); // Evitar el envío de formulario por defecto
                const form = button.closest('form');

                if (form) {
                    // La lógica de actualización de la apariencia se deja en updateButtonAppearance
                    // Solo necesitamos enviar el formulario que ya está en el HTML con la acción correcta
                    form.submit(); // Enviar el formulario para cambiar el estado
                } else {
                    console.error('Formulario no encontrado para el botón de estado.', button);
                }
            });
            button.dataset.listenerAttached = 'true'; // Marcar que el listener ha sido adjuntado
        });
    }

    /**
     * Actualiza visualmente la apariencia del botón de estado (color, icono, texto).
     * NOTA: La lógica de cambio de estado real la maneja el backend al enviar el formulario.
     * Esta función solo actualiza la UI después de la interacción o si los datos iniciales lo indican.
     * @param {HTMLElement} button - El botón a actualizar.
     * @param {boolean} nuevoEstado - El estado al que cambiar visualmente (true para Activo, false para Inactivo).
     */
    updateButtonAppearance(button, nuevoEstado) {
        // Remover clases de color existentes y añadir la nueva
        button.classList.remove('btn-success', 'btn-danger');
        button.classList.add(nuevoEstado ? 'btn-success' : 'btn-danger');

        // Actualizar icono y texto
        button.innerHTML = `
            <i class="bi ${nuevoEstado ? 'bi-person-check-fill' : 'bi-person-x-fill'} me-1"></i>
            ${nuevoEstado ? 'Activo' : 'Inactivo'}
        `;

        // Actualizar el atributo de datos para reflejar el nuevo estado visual
        button.dataset.estadoActual = nuevoEstado ? '1' : '0';
    }
}

// Inicializar el manager cuando el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {
    new TenantShowManager();
});
