class MetodoPagoManager {
    constructor() {
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Inicializar el modal de edición
        const editarModal = document.getElementById('editarMetodoPagoModal');
        if (editarModal) {
            editarModal.addEventListener('show.bs.modal', this.handleEditarModalShow.bind(this));
        }

        // Inicializar validación de formularios
        this.initializeFormValidation();
    }

    handleEditarModalShow(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const nombre = button.getAttribute('data-nombre');
        const descripcion = button.getAttribute('data-descripcion');

        const form = event.target.querySelector('form');
        form.action = `${window.location.origin}/superadmin/pago/${id}`;

        event.target.querySelector('#editar_nombre').value = nombre;
        event.target.querySelector('#editar_descripcion').value = descripcion;
    }

    initializeFormValidation() {
        const forms = document.querySelectorAll('.needs-validation');

        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new MetodoPagoManager();
});
