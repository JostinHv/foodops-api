import EmailValidator from '../utils/emailValidator.js';

class RegisterValidator extends EmailValidator {
    constructor(emailInput, feedbackElement) {
        super(emailInput, feedbackElement);
        this.checkEmailUrl = document.querySelector('meta[name="check-email-url"]').content;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    }

    init() {
        super.init();
        this.emailInput.addEventListener('keyup', () => {
            clearTimeout(this.typingTimer);
            this.typingTimer = setTimeout(() => {
                if (this.validateEmailFormat()) {
                    this.checkEmailAvailability();
                }
            }, this.doneTypingInterval);
        });
    }

    checkEmailAvailability() {
        const email = this.emailInput.value;
        if (!email) return;

        fetch(this.checkEmailUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken
            },
            body: JSON.stringify({email})
        })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    this.updateFeedback("Este correo ya está registrado", "red");
                } else {
                    this.updateFeedback("Correo disponible", "green");
                }
            });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const emailFeedback = document.getElementById('email-feedback');

    if (emailInput && emailFeedback) {
        const validator = new RegisterValidator(emailInput, emailFeedback);
        validator.init();
    }
});
