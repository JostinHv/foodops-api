// Interceptor para fetch
const originalFetch = window.fetch;
window.fetch = function(url, options) {
    if (typeof url === 'string' && url.startsWith('/') && !url.startsWith('/foodops/')) {
        url = '/foodops' + url;
    }
    return originalFetch(url, options);
};

// Helper para rutas
window.route = function(path) {
    return '/foodops' + (path.startsWith('/') ? path : '/' + path);
};

// Interceptor para form.action
document.addEventListener('DOMContentLoaded', function() {
    // Interceptar cuando se asigna form.action
    const originalSetAttribute = Element.prototype.setAttribute;
    Element.prototype.setAttribute = function(name, value) {
        if (name === 'action' && typeof value === 'string' && value.startsWith('/') && !value.startsWith('/foodops/')) {
            value = '/foodops' + value;
        }
        return originalSetAttribute.call(this, name, value);
    };

    // Interceptar asignación directa a form.action
    Object.defineProperty(HTMLFormElement.prototype, 'action', {
        set: function(value) {
            if (typeof value === 'string' && value.startsWith('/') && !value.startsWith('/foodops/')) {
                value = '/foodops' + value;
            }
            this.setAttribute('action', value);
        },
        get: function() {
            return this.getAttribute('action') || '';
        }
    });
});

// Helper para formularios (por si prefieres usarlo manualmente)
window.setFormAction = function(form, path) {
    form.action = window.route(path);
};
