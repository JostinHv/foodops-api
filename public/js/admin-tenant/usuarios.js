document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add click handlers for action buttons (placeholder)
    document.querySelectorAll('.btn-outline-primary').forEach(btn => {
        btn.addEventListener('click', function() {
            console.log('View details clicked for user');
            // Implement view details functionality
        });
    });

    document.querySelectorAll('.btn-outline-secondary').forEach(btn => {
        btn.addEventListener('click', function() {
            console.log('Edit clicked for user');
            // Implement edit functionality
        });
    });

    document.querySelectorAll('.btn-outline-danger').forEach(btn => {
        btn.addEventListener('click', function() {
            console.log('Delete clicked for user');
            // Implement delete functionality with confirmation
            if (confirm('¿Está seguro que desea eliminar este usuario?')) {
                // Proceed with deletion
            }
        });
    });
});