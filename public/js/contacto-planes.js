class ContactoPlanes {
    constructor() {
        this.form = document.getElementById('contactForm');
        this.planSelect = document.getElementById('plan');
        this.planPreview = document.getElementById('planPreview');
        this.submitButton = this.form.querySelector('button[type="submit"]');
        this.currentStep = 1;
        this.totalSteps = 3;
        this.formSections = [
            document.getElementById('step1'),
            document.getElementById('step2'),
            document.getElementById('step3')
        ];

        this.initializeEventListeners();
        this.initializeStepper();
        this.showCurrentStep();
    }

    initializeEventListeners() {
        // Validación del formulario
        this.form.addEventListener('submit', this.handleSubmit.bind(this));

        // Manejo de la previsualización del plan
        this.planSelect.addEventListener('change', this.handlePlanChange.bind(this));

        // Validación de teléfono en tiempo real
        const telefonoInput = document.getElementById('telefono');
        telefonoInput.addEventListener('input', this.handleTelefonoInput.bind(this));

        // Guardado automático
        this.initializeAutoSave();

        // Botones de navegación
        document.querySelectorAll('.step-nav-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const action = e.target.dataset.action;
                if (action === 'next' && this.validateCurrentStep()) {
                    this.nextStep();
                } else if (action === 'prev') {
                    this.prevStep();
                }
            });
        });
    }

    initializeStepper() {
        const stepper = document.createElement('div');
        stepper.className = 'stepper';
        stepper.innerHTML = `
            <div class="step active" data-step="1">
                <span class="step-number">1</span>
                <span class="step-label">Información Personal</span>
            </div>
            <div class="step" data-step="2">
                <span class="step-number">2</span>
                <span class="step-label">Selección de Plan</span>
            </div>
            <div class="step" data-step="3">
                <span class="step-number">3</span>
                <span class="step-label">Mensaje</span>
            </div>
        `;
        this.form.insertBefore(stepper, this.form.firstChild);
    }

    showCurrentStep() {
        this.formSections.forEach((section, index) => {
            if (index + 1 === this.currentStep) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        });

        // Actualizar estado del stepper
        document.querySelectorAll('.step').forEach((step, index) => {
            if (index + 1 < this.currentStep) {
                step.classList.add('completed');
                step.classList.remove('active');
            } else if (index + 1 === this.currentStep) {
                step.classList.add('active');
                step.classList.remove('completed');
            } else {
                step.classList.remove('active', 'completed');
            }
        });
    }

    nextStep() {
        if (this.currentStep < this.totalSteps) {
            this.currentStep++;
            this.showCurrentStep();
        }
    }

    prevStep() {
        if (this.currentStep > 1) {
            this.currentStep--;
            this.showCurrentStep();
        }
    }

    validateCurrentStep() {
        const currentSection = this.formSections[this.currentStep - 1];
        const inputs = currentSection.querySelectorAll('input, select, textarea');
        let isValid = true;

        inputs.forEach(input => {
            if (input.hasAttribute('required') && !input.value) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        return isValid;
    }

    handleSubmit(event) {
        if (!this.form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            this.submitButton.classList.add('btn-loading');
            this.saveFormData();
        }
        this.form.classList.add('was-validated');
    }

    handlePlanChange() {
        const selectedOption = this.planSelect.options[this.planSelect.selectedIndex];
        if (selectedOption.value) {
            const planData = {
                nombre: selectedOption.dataset.nombre,
                precio: selectedOption.dataset.precio,
                intervalo: selectedOption.dataset.intervalo,
                caracteristicas: JSON.parse(selectedOption.dataset.caracteristicas)
            };

            this.updatePlanPreview(planData);
        } else {
            this.planPreview.classList.remove('active');
        }
    }

    updatePlanPreview(planData) {
        const planPreview = this.planPreview;
        planPreview.querySelector('.plan-nombre').textContent = planData.nombre;
        planPreview.querySelector('.plan-price').textContent = `S/. ${planData.precio}/${planData.intervalo}`;

        const caracteristicasContainer = planPreview.querySelector('.plan-caracteristicas');
        caracteristicasContainer.innerHTML = '';

        // Agregar límites
        if (planData.caracteristicas.limites) {
            const limites = planData.caracteristicas.limites;
            Object.entries(limites).forEach(([key, value]) => {
                const feature = document.createElement('div');
                feature.className = 'feature-item';
                feature.innerHTML = `
                    <i class="bi bi-check-circle-fill"></i>
                    <span>${key.charAt(0).toUpperCase() + key.slice(1)}: ${value}</span>
                `;
                caracteristicasContainer.appendChild(feature);
            });
        }

        // Agregar características adicionales
        if (planData.caracteristicas.adicionales) {
            planData.caracteristicas.adicionales.forEach(caracteristica => {
                const feature = document.createElement('div');
                feature.className = 'feature-item';
                feature.innerHTML = `
                    <i class="bi bi-check-circle-fill"></i>
                    <span>${caracteristica}</span>
                `;
                caracteristicasContainer.appendChild(feature);
            });
        }

        planPreview.classList.add('active', 'fade-in');
    }

    handleTelefonoInput(event) {
        event.target.value = event.target.value.replace(/[^0-9]/g, '').slice(0, 9);
    }

    initializeAutoSave() {
        const formData = localStorage.getItem('contactFormData');
        if (formData) {
            const data = JSON.parse(formData);
            Object.keys(data).forEach(key => {
                const input = this.form.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = data[key];
                }
            });
        }

        this.form.addEventListener('input', () => {
            const formData = new FormData(this.form);
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });
            localStorage.setItem('contactFormData', JSON.stringify(data));
        });
    }

    saveFormData() {
        const formData = new FormData(this.form);
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        localStorage.setItem('contactFormData', JSON.stringify(data));
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new ContactoPlanes();
}); 