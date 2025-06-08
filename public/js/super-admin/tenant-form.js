// Clase para validar el dominio
class TenantFormValidator {
    constructor() {
        this.domainInput = document.getElementById('dominio');
        this.domainFeedback = document.getElementById('dominio-feedback');
        this.submitButton = document.querySelector('button[type="submit"]');
        this.nextButton = document.getElementById('nextStep');
        this.isDomainValid = false;
        this.debounceTimer = null;
        this.tenantId = this.domainInput?.dataset.tenantId;

        this.init();
    }

    init() {
        if (this.domainInput) {
            this.domainInput.addEventListener('input', () => {
                // Transformar a minúsculas mientras se escribe
                this.domainInput.value = this.domainInput.value.toLowerCase();
                this.validateDomain();
            });
            this.domainInput.addEventListener('blur', () => {
                // Asegurar minúsculas al perder el foco
                this.domainInput.value = this.domainInput.value.toLowerCase();
                this.validateDomain();
            });
        }
    }

    async validateDomain() {
        clearTimeout(this.debounceTimer);
        
        const domain = this.domainInput.value.trim().toLowerCase();
        
        if (!domain) {
            this.showFeedback('El dominio es requerido', false);
            this.isDomainValid = false;
            this.updateNextButton();
            return false;
        }

        // Validación básica de formato
        if (!this.isValidDomainFormat(domain)) {
            this.showFeedback('Formato de dominio inválido. Use solo letras minúsculas, números y guiones', false);
            this.isDomainValid = false;
            this.updateNextButton();
            return false;
        }

        // Debounce para evitar muchas peticiones
        return new Promise((resolve) => {
            this.debounceTimer = setTimeout(async () => {
                try {
                    const response = await fetch('/superadmin/tenant/check-domain', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            dominio: domain,
                            id: this.tenantId
                        })
                    });

                    const data = await response.json();
                    this.showFeedback(data.message, data.valid);
                    this.isDomainValid = data.valid;
                    this.updateNextButton();
                    resolve(data.valid);
                } catch (error) {
                    console.error('Error al validar el dominio:', error);
                    this.showFeedback('Error al validar el dominio', false);
                    this.isDomainValid = false;
                    this.updateNextButton();
                    resolve(false);
                }
            }, 500);
        });
    }

    isValidDomainFormat(domain) {
        // Expresión regular para validar formato de dominio
        // Solo permite letras minúsculas, números y guiones
        const domainRegex = /^[a-z0-9][a-z0-9-]{1,61}[a-z0-9]\.[a-z]{2,}$/;
        return domainRegex.test(domain);
    }

    showFeedback(message, isValid) {
        if (!this.domainFeedback) {
            this.domainFeedback = document.createElement('div');
            this.domainFeedback.id = 'dominio-feedback';
            this.domainInput.parentNode.appendChild(this.domainFeedback);
        }

        this.domainFeedback.textContent = message;
        this.domainFeedback.className = `form-text ${isValid ? 'text-success' : 'text-danger'}`;
        this.domainInput.classList.toggle('is-invalid', !isValid);
        this.domainInput.classList.toggle('is-valid', isValid);
    }

    updateNextButton() {
        if (this.nextButton) {
            this.nextButton.disabled = !this.isDomainValid;
        }
    }
}

class ImageValidator {
    constructor() {
        this.maxFileSize = 2 * 1024 * 1024; // 2MB
        this.allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        this.maxWidth = 1200;
        this.maxHeight = 1200;
    }

    validateFile(file) {
        return new Promise((resolve, reject) => {
            // Validar tamaño
            if (file.size > this.maxFileSize) {
                reject({
                    valid: false,
                    message: `El archivo excede el tamaño máximo permitido (${this.formatFileSize(this.maxFileSize)})`
                });
                return;
            }

            // Validar tipo
            if (!this.allowedTypes.includes(file.type)) {
                reject({
                    valid: false,
                    message: 'Solo se permiten archivos de imagen (JPEG, PNG, GIF)'
                });
                return;
            }

            // Validar dimensiones
            const img = new Image();
            img.onload = () => {
                if (img.width > this.maxWidth || img.height > this.maxHeight) {
                    reject({
                        valid: false,
                        message: `La imagen excede las dimensiones máximas permitidas (${this.maxWidth}x${this.maxHeight}px)`
                    });
                } else {
                    resolve({
                        valid: true,
                        file: file
                    });
                }
            };
            img.onerror = () => {
                reject({
                    valid: false,
                    message: 'Error al cargar la imagen'
                });
            };
            img.src = URL.createObjectURL(file);
        });
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
}

// Clase principal del wizard
class TenantFormWizard {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 4;
        this.formData = new FormData();
        this.domainValidator = null;
        this.imageValidator = new ImageValidator();
        this.init();
    }

    init() {
        this.initializeElements();
        this.initializeEventListeners();
        this.initializeDomainValidation();
        this.updateNavigationButtons();
        this.initializeLogoPreview();
        this.initializePlanPreview();
    }

    initializeElements() {
        // Elementos de navegación
        this.prevButton = document.getElementById('prevStep');
        this.nextButton = document.getElementById('nextStep');
        this.submitButton = document.getElementById('submitForm');
        this.progressBar = document.querySelector('.progress-bar');
        this.steps = document.querySelectorAll('.step');

        // Asegurar que el botón Siguiente esté habilitado por defecto
        if (this.nextButton) {
            this.nextButton.disabled = false;
        }

        // Elementos de formulario
        this.form = document.getElementById('formNuevoTenant');
        this.domainInput = document.getElementById('dominio');
        this.domainFeedback = document.getElementById('dominio-feedback');
        this.logoInput = document.getElementById('logo');
        this.logoPreview = document.getElementById('logo-preview');
        this.planSelect = document.getElementById('plan_suscripcion_id');
        this.summaryContainer = document.querySelector('.summary-container');
    }

    initializeEventListeners() {
        // Navegación
        this.prevButton.addEventListener('click', () => this.previousStep());
        this.nextButton.addEventListener('click', () => this.nextStep());
        this.submitButton.addEventListener('click', (e) => this.submitForm(e));

        // Validación de dominio
        this.domainInput.addEventListener('input', () => this.validateDomain());
        this.domainInput.addEventListener('blur', () => this.validateDomain());

        // Previsualización de logo
        if (this.logoInput) {
            this.logoInput.addEventListener('change', (e) => this.handleLogoPreview(e));
        }

        // Previsualización del plan
        if (this.planSelect) {
            this.planSelect.addEventListener('change', () => this.updatePlanPreview());
        }
    }

    initializeDomainValidation() {
        this.domainValidator = new TenantFormValidator();
    }

    async validateDomain() {
        if (this.domainValidator) {
            return await this.domainValidator.validateDomain();
        }
        return false;
    }

    handleLogoPreview(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Mostrar progreso
        this.showImageProgress();

        this.imageValidator.validateFile(file)
            .then(result => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    if (this.logoPreview) {
                        this.logoPreview.innerHTML = `
                            <div class="image-upload-preview">
                                <img src="${e.target.result}" alt="Logo preview">
                                <div class="image-upload-remove" onclick="this.removeLogo()">
                                    <i class="bi bi-x"></i>
                                </div>
                            </div>
                            <div class="image-upload-info">
                                <div class="image-limit-info">
                                    <i class="bi bi-check-circle"></i>
                                    <span>Imagen válida (${this.imageValidator.formatFileSize(file.size)})</span>
                                </div>
                            </div>`;
                    }
                    this.hideImageProgress();
                };
                reader.readAsDataURL(file);
            })
            .catch(error => {
                this.logoPreview.innerHTML = `
                    <div class="image-upload-error show">
                        <i class="bi bi-exclamation-circle"></i>
                        ${error.message}
                    </div>`;
                this.logoInput.value = '';
                this.hideImageProgress();
            });
    }

    showImageProgress() {
        if (!this.logoPreview.querySelector('.image-upload-progress')) {
            this.logoPreview.innerHTML = `
                <div class="image-upload-progress show">
                    <div class="image-upload-progress-bar"></div>
                </div>`;
        }
    }

    hideImageProgress() {
        const progress = this.logoPreview.querySelector('.image-upload-progress');
        if (progress) {
            progress.remove();
        }
    }

    removeLogo() {
        this.logoInput.value = '';
        this.logoPreview.innerHTML = '';
    }

    previousStep() {
        if (this.currentStep > 1) {
            this.showStep(this.currentStep - 1);
        }
    }

    async nextStep() {
        if (await this.validateCurrentStep()) {
            if (this.currentStep < this.totalSteps) {
                this.showStep(this.currentStep + 1);
            }
        }
    }

    async validateCurrentStep() {
        const currentStepElement = document.getElementById(`step${this.currentStep}`);
        const requiredFields = currentStepElement.querySelectorAll('[required]');
        let isValid = true;

        for (const field of requiredFields) {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        }

        if (this.currentStep === 1) {
            isValid = await this.validateDomain();
        }

        return isValid;
    }

    showStep(step) {
        // Ocultar todos los pasos
        document.querySelectorAll('.step-content').forEach(el => el.classList.add('d-none'));
        
        // Mostrar el paso actual
        document.getElementById(`step${step}`).classList.remove('d-none');
        
        // Actualizar estado
        this.currentStep = step;
        
        // Actualizar navegación
        this.updateNavigationButtons();
        
        // Actualizar barra de progreso
        this.updateProgress();
        
        // Actualizar pasos
        this.updateSteps();
        
        // Si es el último paso, actualizar el resumen
        if (step === this.totalSteps) {
            this.updateSummary();
        }
    }

    updateNavigationButtons() {
        this.prevButton.style.display = this.currentStep > 1 ? 'inline-block' : 'none';
        this.nextButton.style.display = this.currentStep < this.totalSteps ? 'inline-block' : 'none';
        this.submitButton.style.display = this.currentStep === this.totalSteps ? 'inline-block' : 'none';
    }

    updateProgress() {
        const progress = ((this.currentStep - 1) / (this.totalSteps - 1)) * 100;
        this.progressBar.style.width = `${progress}%`;
    }

    updateSteps() {
        this.steps.forEach((step, index) => {
            const stepNumber = index + 1;
            step.classList.remove('active', 'completed');
            
            if (stepNumber === this.currentStep) {
                step.classList.add('active');
            } else if (stepNumber < this.currentStep) {
                step.classList.add('completed');
            }
        });
    }

    updateSummary() {
        const logoPreview = document.querySelector('#logo-preview img');
        const logoUrl = logoPreview ? logoPreview.src : null;

        const summary = {
            'Información Básica': {
                'Logo': logoUrl ? `<img src="${logoUrl}" class="img-thumbnail" style="max-height: 50px" alt="Logo">` : 'No se ha seleccionado logo',
                'Dominio': this.domainInput.value,
                'Estado': document.getElementById('estado-texto').textContent
            },
            'Plan de Suscripción': {
                'Plan': this.planSelect.options[this.planSelect.selectedIndex].text,
                'Método de Pago': document.getElementById('metodo_pago_id').options[document.getElementById('metodo_pago_id').selectedIndex].text,
                'Fecha de Inicio': document.getElementById('fecha_inicio').value,
                'Renovación Automática': document.getElementById('renovacion_automatica').checked ? 'Sí' : 'No'
            },
            'Datos de Contacto': {
                'Empresa': document.getElementById('datos_contacto_nombre_empresa').value,
                'Email': document.getElementById('datos_contacto_email').value,
                'Teléfono': document.getElementById('datos_contacto_telefono').value || 'No especificado',
                'Dirección': document.getElementById('datos_contacto_direccion').value || 'No especificada'
            }
        };

        this.summaryContainer.innerHTML = Object.entries(summary).map(([section, items]) => `
            <div class="mb-4">
                <h6 class="mb-3">${section}</h6>
                ${Object.entries(items).map(([label, value]) => `
                    <div class="summary-item">
                        <span class="summary-label">${label}:</span>
                        <span class="summary-value">${value}</span>
                    </div>
                `).join('')}
            </div>
        `).join('');
    }

    async submitForm(e) {
        e.preventDefault();
        
        if (await this.validateCurrentStep()) {
            // Crear un nuevo FormData con todos los campos del formulario
            const formData = new FormData(this.form);
            
            try {
                const response = await fetch(this.form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    // Si la respuesta es exitosa, redirigir
                    window.location.href = response.url;
                } else {
                    // Si hay un error, mostrar mensaje
                    const data = await response.json();
                    alert(data.message || 'Error al crear el tenant');
                }
            } catch (error) {
                console.error('Error al enviar el formulario:', error);
                alert('Error al crear el tenant. Por favor, intente nuevamente.');
            }
        }
    }

    initializeLogoPreview() {
        if (this.logoInput && this.logoPreview) {
            this.logoInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                    reader.onload = (e) => {
                        this.logoPreview.innerHTML = `
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

    initializePlanPreview() {
        if (this.planSelect) {
            this.planSelect.addEventListener('change', () => {
                const selectedOption = this.planSelect.options[this.planSelect.selectedIndex];
                if (selectedOption.value) {
                    this.updatePlanPreview();
                }
            });
        }
    }

    updatePlanPreview() {
        const selectedOption = this.planSelect.options[this.planSelect.selectedIndex];
        if (!selectedOption.value) return;

        const precio = selectedOption.dataset.precio;
        const caracteristicas = JSON.parse(selectedOption.dataset.caracteristicas || '{}');
        const descripcion = selectedOption.dataset.descripcion;
        const intervalo = selectedOption.dataset.intervalo;

        // Actualizar elementos de la previsualización
        document.getElementById('precio-plan').textContent = new Intl.NumberFormat('es-PE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(precio);

        document.getElementById('descripcion-plan').textContent = descripcion;
        document.getElementById('intervalo-plan').textContent = intervalo;

        // Actualizar límites
        if (caracteristicas.limites) {
            document.getElementById('limite-usuarios').textContent = caracteristicas.limites.usuarios || 0;
            document.getElementById('limite-restaurantes').textContent = caracteristicas.limites.restaurantes || 0;
            document.getElementById('limite-sucursales').textContent = caracteristicas.limites.sucursales || 0;
        }

        // Actualizar características
        const caracteristicasList = document.getElementById('caracteristicas-plan');
        caracteristicasList.innerHTML = '';
        if (caracteristicas.adicionales && Array.isArray(caracteristicas.adicionales)) {
            caracteristicas.adicionales.forEach(caracteristica => {
                const li = document.createElement('li');
                li.innerHTML = `<i class="bi bi-check-circle-fill text-success me-2"></i>${caracteristica}`;
                caracteristicasList.appendChild(li);
            });
        }

        document.getElementById('plan-preview').classList.remove('d-none');
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new TenantFormWizard();
});
