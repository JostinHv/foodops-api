document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleMenuBtn');
    const sidebar = document.getElementById('sidebar');

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
    });
});
