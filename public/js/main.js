// Main JavaScript for PurinaStock Presentation Dashboard

document.addEventListener('DOMContentLoaded', () => {
    console.log('PurinaStock system dashboard loaded successfully.');

    // Initialize Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle Quick Action clicks with simulation responses
    const quickActions = document.querySelectorAll('.action-btn-card');
    quickActions.forEach(action => {
        action.addEventListener('click', (e) => {
            const actionText = action.querySelector('h6').innerText;
            console.log(`Action selected: ${actionText}`);
            // In a production system, these will route to PHP views or controller endpoints.
        });
    });

    // Update real-time system connection timestamp
    const systemTimeEl = document.getElementById('system-time');
    if (systemTimeEl) {
        const updateTime = () => {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const dateStr = now.toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });
            systemTimeEl.innerHTML = `<span class="status-dot"></span> Sistema Activo: ${dateStr} - ${timeStr}`;
        };
        updateTime();
        setInterval(updateTime, 1000);
    }
});
