class TenantFormValidator {
    constructor() {
        this.domainInput = document.getElementById('dominio');
        this.domainFeedback = document.getElementById('dominio-feedback');
        this.submitButton = document.querySelector('button[type="submit"]');
        this.isDomainValid = false;
        this.debounceTimer = null;
        this.tenantId = this.domainInput.dataset.tenantId;

        this.init();
    }

    init() {
        if (this.domainInput) {
            this.domainInput.addEventListener('input', () => this.validateDomain());
            this.domainInput.addEventListener('blur', () => this.validateDomain());
        }
    }

    validateDomain() {
        clearTimeout(this.debounceTimer);
        
        const domain = this.domainInput.value.trim();
        
        if (!domain) {
            this.showFeedback('El dominio es requerido', false);
            return;
        }

        // Validación básica de formato
        if (!this.isValidDomainFormat(domain)) {
            this.showFeedback('Formato de dominio inválido', false);
            return;
        }

        // Debounce para evitar muchas peticiones
        this.debounceTimer = setTimeout(() => {
            this.checkDomainAvailability(domain);
        }, 500);
    }

    isValidDomainFormat(domain) {
        // Expresión regular para validar formato de dominio
        const domainRegex = /^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/;
        return domainRegex.test(domain);
    }

    async checkDomainAvailability(domain) {
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
            this.updateSubmitButton();
        } catch (error) {
            console.error('Error al validar el dominio:', error);
            this.showFeedback('Error al validar el dominio', false);
        }
    }

    showFeedback(message, isValid) {
        if (!this.domainFeedback) {
            this.domainFeedback = document.createElement('div');
            this.domainFeedback.id = 'dominio-feedback';
            this.domainInput.parentNode.appendChild(this.domainFeedback);
        }

        this.domainFeedback.textContent = message;
        this.domainFeedback.className = `invalid-feedback ${isValid ? 'text-success' : 'text-danger'}`;
        this.domainInput.classList.toggle('is-invalid', !isValid);
        this.domainInput.classList.toggle('is-valid', isValid);
    }

    updateSubmitButton() {
        if (this.submitButton) {
            this.submitButton.disabled = !this.isDomainValid;
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new TenantFormValidator();
}); 