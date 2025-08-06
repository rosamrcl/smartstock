/**
 * Sistema de Notificações SmartStock
 * Gerencia o contador de notificações no header
 */

class NotificacoesManager {
    constructor() {
        this.contadorElement = document.getElementById('contador-notificacoes');
        this.linkNotificacoes = document.getElementById('notificacoes-link');
        this.interval = null;
        this.init();
    }

    init() {
        // Carregar contador inicial
        this.atualizarContador();
        
        // Atualizar contador a cada 30 segundos
        this.interval = setInterval(() => {
            this.atualizarContador();
        }, 30000);
    }

    atualizarContador() {
        fetch('../Backend/listar_notificacoes.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.exibirContador(data.nao_lidas);
            }
        })
        .catch(error => {
            console.error('Erro ao atualizar contador de notificações:', error);
        });
    }

    exibirContador(quantidade) {
        if (this.contadorElement) {
            if (quantidade > 0) {
                this.contadorElement.textContent = quantidade > 99 ? '99+' : quantidade;
                this.contadorElement.style.display = 'flex';
                
                // Adicionar animação se for uma nova notificação
                if (quantidade > this.ultimaQuantidade) {
                    this.contadorElement.style.animation = 'none';
                    setTimeout(() => {
                        this.contadorElement.style.animation = 'pulse 0.5s ease-in-out';
                    }, 10);
                }
            } else {
                this.contadorElement.style.display = 'none';
            }
            this.ultimaQuantidade = quantidade;
        }
    }

    destruir() {
        if (this.interval) {
            clearInterval(this.interval);
        }
    }
}

// Inicializar o gerenciador de notificações quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    // Só inicializar se estivermos em uma página que não seja a de notificações
    if (!window.location.pathname.includes('notificacoes.php')) {
        window.notificacoesManager = new NotificacoesManager();
    }
}); 