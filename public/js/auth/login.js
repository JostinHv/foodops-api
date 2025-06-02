import EmailValidator from '../utils/emailValidator.js';

document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const emailFeedback = document.getElementById('email-feedback');
    const form = document.querySelector('form');

    if (emailInput && emailFeedback) {
        const validator = new EmailValidator(emailInput, emailFeedback);
        validator.init();

        // Validación del formulario antes de enviar
        form.addEventListener('submit', function(e) {
            const email = emailInput.value;
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            
            if (!emailRegex.test(email)) {
                e.preventDefault();
                emailFeedback.textContent = 'Por favor ingrese un correo electrónico válido';
                emailFeedback.style.color = 'red';
                emailInput.classList.add('is-invalid');
            } else {
                emailFeedback.textContent = '';
                emailInput.classList.remove('is-invalid');
            }
        });
    }
});
