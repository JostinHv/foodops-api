class IgvManager {
    constructor() {
        this.initializeEventListeners();
        this.initializeFormValidation();
    }

    initializeEventListeners() {
        // Inicializar el modal de edición
        const editarModal = document.getElementById('editarIgvModal');
        if (editarModal) {
            editarModal.addEventListener('show.bs.modal', this.handleEditarModalShow.bind(this));
        }

        // Inicializar la calculadora
        this.initializeCalculadora();
    }

    handleEditarModalShow(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const anio = button.getAttribute('data-anio');
        const valor = button.getAttribute('data-valor');

        const form = event.target.querySelector('form');
        form.action = `${window.location.origin}/superadmin/igv/${id}`;

        event.target.querySelector('#editar_anio').value = anio;
        event.target.querySelector('#editar_valor_porcentaje').value = valor;
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

    initializeCalculadora() {
        const calcularBtn = document.getElementById('calcularBtn');
        if (calcularBtn) {
            calcularBtn.addEventListener('click', this.calcularIGV.bind(this));
        }

        // Calcular automáticamente al cambiar el monto base
        const montoBase = document.getElementById('monto_base');
        if (montoBase) {
            montoBase.addEventListener('change', () => {
                if (montoBase.value && document.getElementById('resultadosCalculo').style.display !== 'none') {
                    this.calcularIGV();
                }
            });
        }
    }

    calcularIGV() {
        const montoBase = parseFloat(document.getElementById('monto_base').value) || 0;
        const tasaIGV = parseFloat(document.getElementById('tasa_igv').value) || 0;
        
        const impuesto = montoBase * (tasaIGV / 100);
        const total = montoBase + impuesto;
        
        // Mostrar resultados
        document.getElementById('subtotal').textContent = `S/${montoBase.toFixed(2)}`;
        document.getElementById('tasaResultado').textContent = tasaIGV;
        document.getElementById('impuestoCalculado').textContent = `S/${impuesto.toFixed(2)}`;
        document.getElementById('totalCalculado').textContent = `S/${total.toFixed(2)}`;
        
        document.getElementById('resultadosCalculo').style.display = 'block';
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new IgvManager();
});