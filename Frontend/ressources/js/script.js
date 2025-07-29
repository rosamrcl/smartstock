/* ===== SCRIPT PRINCIPAL - SISTEMA INTERATIVO ===== */

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== VARIÁVEIS GLOBAIS =====
    const menuBtn = document.getElementById('menu-bars');
    const navbar = document.querySelector('.navbar');
    const header = document.querySelector('header');
    const cards = document.querySelectorAll('.box');
    const body = document.body;
    
    // ===== CONTROLE DO MENU MOBILE =====
    if (menuBtn) {
        menuBtn.addEventListener('click', function() {
            navbar.classList.toggle('active');
            menuBtn.classList.toggle('active');
            
            // Anima o ícone do menu
            const icon = menuBtn;
            if (icon) {
                icon.classList.toggle('fa-bars');
                icon.classList.toggle('fa-times');
            }
            
            // Previne scroll do body quando menu está aberto
            body.style.overflow = navbar.classList.contains('active') ? 'hidden' : '';
        });
    }
    
    // ===== FECHAR MENU AO CLICAR FORA =====
    document.addEventListener('click', function(e) {
        if (navbar && navbar.classList.contains('active')) {
            if (!navbar.contains(e.target) && !menuBtn.contains(e.target)) {
                navbar.classList.remove('active');
                menuBtn.classList.remove('active');
                body.style.overflow = '';
                
                const icon = menuBtn;
                if (icon) {
                    icon.classList.add('fa-bars');
                    icon.classList.remove('fa-times');
                }
            }
        }
    });
    
    // ===== ANIMAÇÃO DOS CARDS =====
    function animateCards() {
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 200);
        });
    }
    
    // ===== INTERSECTION OBSERVER PARA CARDS =====
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observa todos os cards
    cards.forEach(card => {
        observer.observe(card);
    });
    
    // ===== ANIMAÇÃO INICIAL DOS CARDS =====
    if (cards.length > 0) {
        animateCards();
    }
    
    // ===== LOADING STATES =====
    function showLoading(card) {
        const loadingElement = card.querySelector('.loading');
        if (loadingElement) {
            loadingElement.style.display = 'flex';
        }
    }
    
    function hideLoading(card) {
        const loadingElement = card.querySelector('.loading');
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
    }
    
    // ===== SISTEMA DE ALERTAS =====
    function showAlert(message, type = 'info', duration = 5000) {
        // Criar elemento de alerta
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.innerHTML = `
            <div class="alert-content">
                <i class="alert-icon ${getAlertIcon(type)}"></i>
                <span class="alert-message">${message}</span>
                <button class="alert-close" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        // Adicionar ao body
        document.body.appendChild(alert);
        
        // Animar entrada
        setTimeout(() => {
            alert.classList.add('show');
        }, 100);
        
        // Auto-remover após duração
        if (duration > 0) {
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => {
                    if (alert.parentElement) {
                        alert.remove();
                    }
                }, 300);
            }, duration);
        }
        
        return alert;
    }
    
    function getAlertIcon(type) {
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };
        return icons[type] || icons.info;
    }
    
    // ===== EXPOSIÇÃO DE FUNÇÕES GLOBAIS =====
    window.showAlert = showAlert;
    window.showLoading = showLoading;
    window.hideLoading = hideLoading;
    
    // ===== SISTEMA DE NOTIFICAÇÕES =====
    class NotificationSystem {
        constructor() {
            this.notifications = [];
            this.container = this.createContainer();
        }
        
        createContainer() {
            const container = document.createElement('div');
            container.className = 'notification-container';
            container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                display: flex;
                flex-direction: column;
                gap: 10px;
                max-width: 400px;
            `;
            document.body.appendChild(container);
            return container;
        }
        
        show(message, type = 'info', duration = 5000) {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.style.cssText = `
                background: white;
                border-radius: 8px;
                padding: 15px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                border-left: 4px solid ${this.getTypeColor(type)};
                transform: translateX(100%);
                transition: transform 0.3s ease;
                display: flex;
                align-items: center;
                gap: 10px;
            `;
            
            notification.innerHTML = `
                <i class="${getAlertIcon(type)}" style="color: ${this.getTypeColor(type)};"></i>
                <span style="flex: 1;">${message}</span>
                <button onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; color: #666;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            this.container.appendChild(notification);
            
            // Animar entrada
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            // Auto-remover
            if (duration > 0) {
                setTimeout(() => {
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.remove();
                        }
                    }, 300);
                }, duration);
            }
            
            return notification;
        }
        
        getTypeColor(type) {
            const colors = {
                success: '#28a745',
                error: '#dc3545',
                warning: '#ffc107',
                info: '#17a2b8'
            };
            return colors[type] || colors.info;
        }
    }
    
    // ===== INICIALIZAR SISTEMA DE NOTIFICAÇÕES =====
    window.notificationSystem = new NotificationSystem();
    
    // ===== UTILITÁRIOS =====
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    }
    
    // ===== EXPOSIÇÃO DE UTILITÁRIOS =====
    window.debounce = debounce;
    window.throttle = throttle;
    
    // ===== INICIALIZAÇÃO COMPLETA =====
    console.log('Sistema SmartStock inicializado com sucesso!');
});

// ===== FUNÇÕES GLOBAIS ADICIONAIS =====

// Função para mostrar loading em qualquer elemento
function showElementLoading(element) {
    if (!element) return;
    
    const loading = document.createElement('div');
    loading.className = 'element-loading';
    loading.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    loading.style.cssText = `
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        border-radius: inherit;
    `;
    
    element.style.position = 'relative';
    element.appendChild(loading);
}

// Função para esconder loading de qualquer elemento
function hideElementLoading(element) {
    if (!element) return;
    
    const loading = element.querySelector('.element-loading');
    if (loading) {
        loading.remove();
    }
}

// Função para fazer scroll suave para um elemento
function smoothScrollTo(element, offset = 0) {
    if (!element) return;
    
    const elementPosition = element.offsetTop - offset;
    window.scrollTo({
        top: elementPosition,
        behavior: 'smooth'
    });
}

// Função para detectar se um elemento está visível
function isElementVisible(element) {
    if (!element) return false;
    
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

// Expor funções globais
window.showElementLoading = showElementLoading;
window.hideElementLoading = hideElementLoading;
window.smoothScrollTo = smoothScrollTo;
window.isElementVisible = isElementVisible;