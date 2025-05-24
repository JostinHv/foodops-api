export default class EmailValidator {
    constructor(emailInput, feedbackElement) {
        this.emailInput = emailInput;
        this.feedbackElement = feedbackElement;
        this.emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        this.typingTimer = null;
        this.doneTypingInterval = 1000;
    }

    init() {
        this.emailInput.addEventListener('keyup', () => {
            clearTimeout(this.typingTimer);
            this.typingTimer = setTimeout(() => {
                this.validateEmailFormat();
            }, this.doneTypingInterval);
        });
    }

    validateEmailFormat() {
        const email = this.emailInput.value;

        if (!email) {
            this.updateFeedback("", "");
            return false;
        }

        if (!this.emailRegex.test(email)) {
            this.updateFeedback("Formato de correo electrónico inválido", "red");
            return false;
        }

        this.updateFeedback("Formato de correo válido", "green");
        return true;
    }

    updateFeedback(message, color) {
        this.feedbackElement.innerText = message;
        this.feedbackElement.style.color = color;
    }
}
