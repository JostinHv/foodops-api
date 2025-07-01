/**
 * NotificationManager - Gestor de notificaciones modular
 * Implementa el patrón Observer y Factory Pattern
 * Sigue principios SOLID para reutilización en múltiples vistas
 */
class NotificationManager {
    constructor() {
        this.notifications = [];
        this.observers = [];
        this.maxNotifications = 5;
        this.notificationDuration = 5000; // 5 segundos
        this.container = null;
        this.init();
    }

    /**
     * Inicializa el gestor de notificaciones
     */
    init() {
        this.createNotificationContainer();
        this.setupGlobalStyles();
    }

    /**
     * Crea el contenedor de notificaciones
     */
    createNotificationContainer() {
        this.container = document.createElement('div');
        this.container.id = 'notification-container';
        this.container.className = 'notification-container';
        document.body.appendChild(this.container);
    }

    /**
     * Configura los estilos globales para las notificaciones
     */
    setupGlobalStyles() {
        if (!document.getElementById('notification-styles')) {
            const style = document.createElement('style');
            style.id = 'notification-styles';
            style.textContent = `
                .notification-container {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    z-index: 9999;
                    max-width: 400px;
                }
                
                .notification {
                    background: white;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    margin-bottom: 10px;
                    padding: 16px;
                    border-left: 4px solid;
                    animation: slideInRight 0.3s ease-out;
                    position: relative;
                    overflow: hidden;
                }
                
                .notification.success {
                    border-left-color: #28a745;
                }
                
                .notification.error {
                    border-left-color: #dc3545;
                }
                
                .notification.warning {
                    border-left-color: #ffc107;
                }
                
                .notification.info {
                    border-left-color: #17a2b8;
                }
                
                .notification-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 8px;
                }
                
                .notification-title {
                    font-weight: 600;
                    font-size: 14px;
                    margin: 0;
                }
                
                .notification-close {
                    background: none;
                    border: none;
                    font-size: 18px;
                    cursor: pointer;
                    color: #6c757d;
                    padding: 0;
                    line-height: 1;
                }
                
                .notification-message {
                    font-size: 13px;
                    color: #495057;
                    margin: 0;
                    line-height: 1.4;
                }
                
                .notification-progress {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    height: 3px;
                    background: rgba(0,0,0,0.1);
                    animation: progressBar 5s linear;
                }
                
                @keyframes slideInRight {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                
                @keyframes slideOutRight {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
                
                @keyframes progressBar {
                    from { width: 100%; }
                    to { width: 0%; }
                }
                
                .notification.removing {
                    animation: slideOutRight 0.3s ease-in forwards;
                }
            `;
            document.head.appendChild(style);
        }
    }

    /**
     * Factory Pattern: Crea diferentes tipos de notificaciones
     */
    createNotification(type, title, message, options = {}) {
        const notificationTypes = {
            success: { icon: 'bi-check-circle', color: 'success' },
            error: { icon: 'bi-exclamation-triangle', color: 'error' },
            warning: { icon: 'bi-exclamation-circle', color: 'warning' },
            info: { icon: 'bi-info-circle', color: 'info' }
        };

        const config = notificationTypes[type] || notificationTypes.info;
        
        return {
            id: this.generateId(),
            type,
            title,
            message,
            icon: config.icon,
            color: config.color,
            timestamp: Date.now(),
            duration: options.duration || this.notificationDuration,
            persistent: options.persistent || false,
            actions: options.actions || []
        };
    }

    /**
     * Genera un ID único para cada notificación
     */
    generateId() {
        return 'notification_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    /**
     * Muestra una notificación
     */
    show(type, title, message, options = {}) {
        const notification = this.createNotification(type, title, message, options);
        
        // Limitar el número de notificaciones
        if (this.notifications.length >= this.maxNotifications) {
            this.removeOldestNotification();
        }

        this.notifications.push(notification);
        this.renderNotification(notification);
        this.notifyObservers('notification_created', notification);

        // Auto-remover si no es persistente
        if (!notification.persistent) {
            setTimeout(() => {
                this.remove(notification.id);
            }, notification.duration);
        }

        return notification.id;
    }

    /**
     * Renderiza una notificación en el DOM
     */
    renderNotification(notification) {
        const element = document.createElement('div');
        element.className = `notification ${notification.color}`;
        element.id = notification.id;
        element.dataset.notificationId = notification.id;

        element.innerHTML = `
            <div class="notification-header">
                <h6 class="notification-title">
                    <i class="bi ${notification.icon} me-2"></i>
                    ${notification.title}
                </h6>
                <button class="notification-close" onclick="window.notificationManager.remove('${notification.id}')">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <p class="notification-message">${notification.message}</p>
            ${notification.actions.length > 0 ? this.renderActions(notification) : ''}
            <div class="notification-progress"></div>
        `;

        this.container.appendChild(element);
    }

    /**
     * Renderiza acciones adicionales para la notificación
     */
    renderActions(notification) {
        const actionsHtml = notification.actions.map(action => 
            `<button class="btn btn-sm btn-${action.type || 'outline-secondary'} me-2" onclick="${action.onClick}">
                ${action.icon ? `<i class="bi ${action.icon} me-1"></i>` : ''}
                ${action.text}
            </button>`
        ).join('');

        return `<div class="notification-actions mt-2">${actionsHtml}</div>`;
    }

    /**
     * Remueve una notificación específica
     */
    remove(notificationId) {
        const element = document.getElementById(notificationId);
        if (element) {
            element.classList.add('removing');
            setTimeout(() => {
                if (element.parentNode) {
                    element.parentNode.removeChild(element);
                }
                this.notifications = this.notifications.filter(n => n.id !== notificationId);
                this.notifyObservers('notification_removed', { id: notificationId });
            }, 300);
        }
    }

    /**
     * Remueve la notificación más antigua
     */
    removeOldestNotification() {
        if (this.notifications.length > 0) {
            const oldest = this.notifications[0];
            this.remove(oldest.id);
        }
    }

    /**
     * Remueve todas las notificaciones
     */
    clearAll() {
        this.notifications.forEach(notification => {
            this.remove(notification.id);
        });
    }

    /**
     * Observer Pattern: Agregar observador
     */
    addObserver(observer) {
        this.observers.push(observer);
    }

    /**
     * Observer Pattern: Remover observador
     */
    removeObserver(observer) {
        this.observers = this.observers.filter(obs => obs !== observer);
    }

    /**
     * Observer Pattern: Notificar a todos los observadores
     */
    notifyObservers(event, data) {
        this.observers.forEach(observer => {
            if (typeof observer.update === 'function') {
                observer.update(event, data);
            }
        });
    }

    /**
     * Métodos de conveniencia para diferentes tipos de notificaciones
     */
    success(title, message, options = {}) {
        return this.show('success', title, message, options);
    }

    error(title, message, options = {}) {
        return this.show('error', title, message, options);
    }

    warning(title, message, options = {}) {
        return this.show('warning', title, message, options);
    }

    info(title, message, options = {}) {
        return this.show('info', title, message, options);
    }
}

// Crear instancia global
window.notificationManager = new NotificationManager(); 