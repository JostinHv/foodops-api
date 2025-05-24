import EmailValidator from '../utils/emailValidator.js';

document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const emailFeedback = document.getElementById('email-feedback');

    if (emailInput && emailFeedback) {
        const validator = new EmailValidator(emailInput, emailFeedback);
        validator.init();
    }
});
