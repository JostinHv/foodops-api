document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const toggleMenuBtn = document.getElementById('toggleMenuBtn');
    const toggleIcon = document.getElementById('toggleIcon');

    // Colapsar sidebar por defecto al cargar la página
    if (sidebar && toggleIcon) {
        sidebar.classList.add('collapsed');
        toggleIcon.classList.add('bi-list'); // Asegurar el icono correcto
        toggleIcon.classList.remove('bi-x');
    }

    // Toggle del sidebar y overlay al hacer clic en el botón del navbar
    if (toggleMenuBtn && sidebar && sidebarOverlay && toggleIcon) {
        toggleMenuBtn.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
            sidebarOverlay.classList.toggle('visible');
            toggleIcon.classList.toggle('bi-list');
            toggleIcon.classList.toggle('bi-x');
        });
    }

    // Colapsar sidebar y ocultar overlay al hacer clic en una opción del sidebar
    if (sidebar && sidebarOverlay && toggleIcon) {
        sidebar.querySelectorAll('a.nav-link:not(.dropdown-toggle)').forEach(function(link) {
            link.addEventListener('click', function() {
                sidebar.classList.add('collapsed');
                sidebarOverlay.classList.remove('visible');
                toggleIcon.classList.add('bi-list'); // Asegurar el icono correcto
                toggleIcon.classList.remove('bi-x');
            });
        });
    }

    // Colapsar sidebar y ocultar overlay al hacer clic en el overlay
     if (sidebar && sidebarOverlay && toggleIcon) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.add('collapsed');
            sidebarOverlay.classList.remove('visible');
            toggleIcon.classList.add('bi-list'); // Asegurar el icono correcto
            toggleIcon.classList.remove('bi-x');
        });
     }
}); 