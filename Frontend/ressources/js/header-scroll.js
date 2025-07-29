/**
 * Header Scroll Behavior - SmartStock
 * Controla o comportamento de scroll do header com transições suaves
 */

class HeaderScrollController {
    constructor() {
        this.header = document.querySelector('header');
        this.lastScrollY = window.scrollY;
        this.isScrolling = false;
        this.scrollTimeout = null;
        
        // Configurações
        this.scrollThreshold = 50; // Pixels para ativar o comportamento
        this.throttleDelay = 100; // Delay para throttle
        
        this.init();
    }
    
    init() {
        if (!this.header) {
            console.warn('Header não encontrado');
            return;
        }
        
        // Adicionar listeners
        this.addScrollListener();
        this.addResizeListener();
        
        // Estado inicial
        this.updateHeaderState();
    }
    
    addScrollListener() {
        let ticking = false;
        
        const updateHeader = () => {
            this.handleScroll();
            ticking = false;
        };
        
        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(updateHeader);
                ticking = true;
            }
        }, { passive: true });
    }
    
    addResizeListener() {
        window.addEventListener('resize', () => {
            this.updateHeaderState();
        }, { passive: true });
    }
    
    handleScroll() {
        const currentScrollY = window.scrollY;
        const scrollDirection = currentScrollY > this.lastScrollY ? 'down' : 'up';
        const scrollDistance = Math.abs(currentScrollY - this.lastScrollY);
        
        // Só ativa o comportamento se o scroll for significativo
        if (scrollDistance < this.scrollThreshold) {
            this.lastScrollY = currentScrollY;
            return;
        }
        
        // Determinar ação baseada na direção do scroll
        if (scrollDirection === 'down' && currentScrollY > 100) {
            this.hideHeader();
        } else if (scrollDirection === 'up') {
            this.showHeader();
        }
        
        this.lastScrollY = currentScrollY;
    }
    
    hideHeader() {
        if (!this.header.classList.contains('header-hidden')) {
            this.header.classList.remove('header-visible');
            this.header.classList.add('header-hidden');
        }
    }
    
    showHeader() {
        if (this.header.classList.contains('header-hidden')) {
            this.header.classList.remove('header-hidden');
            this.header.classList.add('header-visible');
        }
    }
    
    updateHeaderState() {
        const scrollY = window.scrollY;
        
        // Se estiver no topo, sempre mostrar o header
        if (scrollY <= 100) {
            this.header.classList.remove('header-hidden');
            this.header.classList.add('header-visible');
        }
    }
    
    // Método público para forçar visibilidade
    forceShow() {
        this.showHeader();
    }
    
    // Método público para forçar ocultação
    forceHide() {
        this.hideHeader();
    }
    
    // Método para resetar o estado
    reset() {
        this.header.classList.remove('header-hidden', 'header-visible');
        this.lastScrollY = window.scrollY;
    }
}

// Função de throttle para otimizar performance
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

// Função para detectar se o usuário está no topo da página
function isAtTop() {
    return window.scrollY <= 100;
}

// Função para detectar se o usuário está próximo do topo
function isNearTop() {
    return window.scrollY <= 200;
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    // Aguardar um pouco para garantir que todos os elementos estejam carregados
    setTimeout(() => {
        window.headerScrollController = new HeaderScrollController();
    }, 100);
});

// Adicionar listener para quando a página terminar de carregar
window.addEventListener('load', function() {
    if (window.headerScrollController) {
        window.headerScrollController.updateHeaderState();
    }
});

// Adicionar listener para mudanças de orientação em dispositivos móveis
window.addEventListener('orientationchange', function() {
    setTimeout(() => {
        if (window.headerScrollController) {
            window.headerScrollController.updateHeaderState();
        }
    }, 100);
});

// Exportar para uso global
window.HeaderScrollController = HeaderScrollController; 