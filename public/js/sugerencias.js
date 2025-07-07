// JS para sugerencias de usuario (crear e historial)
document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.getElementById('sugerencia');
    const contador = document.getElementById('contadorPalabras');
    const btnEnviar = document.getElementById('btnEnviar');
    const btnEnviarText = document.getElementById('btnEnviarText');
    const btnSpinner = document.getElementById('btnSpinner');
    const btnLimpiar = document.getElementById('btnLimpiar');
    const form = document.getElementById('formSugerencia');
    const LIMITE = 300;

    function contarPalabras(text) {
        if (!text) return 0;
        return text.trim().split(/\s+/).filter(Boolean).length;
    }

    function actualizarContador() {
        if (!textarea || !contador) return;
        const palabras = contarPalabras(textarea.value);
        contador.textContent = palabras;
        if (palabras > LIMITE) {
            contador.classList.add('text-danger');
            if (btnEnviar) btnEnviar.disabled = true;
        } else {
            contador.classList.remove('text-danger');
            if (btnEnviar) btnEnviar.disabled = false;
        }
    }

    if (textarea) textarea.addEventListener('input', actualizarContador);
    if (btnLimpiar && textarea) {
        btnLimpiar.addEventListener('click', function() {
            textarea.value = '';
            actualizarContador();
        });
    }
    if (form && textarea) {
        form.addEventListener('submit', function(e) {
            if (contarPalabras(textarea.value) > LIMITE) {
                e.preventDefault();
                alert('Has superado el límite de 300 palabras.');
                return;
            }
            if (btnEnviar) btnEnviar.disabled = true;
            if (btnEnviarText) btnEnviarText.classList.add('d-none');
            if (btnSpinner) btnSpinner.classList.remove('d-none');
        });
    }
    actualizarContador();

    // Mostrar toast si hay éxito (desde backend)
    const toast = document.getElementById('toastSugerencia');
    if (toast && toast.dataset.show === '1') {
        (new bootstrap.Toast(toast)).show();
    }
}); 