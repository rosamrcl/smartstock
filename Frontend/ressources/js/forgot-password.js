/**
 * SmartStock - Forgot Password Form Handler
 * Gerencia o formulário de recuperação de senha com UX moderna
 */

class ForgotPasswordForm {
    constructor() {
        this.form = document.getElementById('forgotPasswordForm');
        this.emailInput = document.getElementById('email');
        this.submitBtn = document.getElementById('submitBtn');
        this.btnText = this.submitBtn.querySelector('.button-text');
        this.btnLoading = this.submitBtn.querySelector('.button-loading');
        this.resetLinkContainer = document.getElementById('resetLinkContainer');
        this.resetLinkContent = document.getElementById('resetLinkContent');
        this.resetLink = document.getElementById('resetLink');
        this.copyLinkBtn = document.getElementById('copyLinkBtn');
        this.backToFormBtn = document.getElementById('backToFormBtn');
        this.submitStatus = document.getElementById('submit-status');
        
        this.currentEmail = '';
        this.isSubmitting = false;
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.emailInput.focus();
    }

    bindEvents() {
        // Validação em tempo real
        this.emailInput.addEventListener('blur', () => this.validateEmail());
        this.emailInput.addEventListener('input', () => this.clearFieldError());
        this.emailInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !this.isSubmitting) {
                this.form.dispatchEvent(new Event('submit'));
            }
        });

        // Clique no link para copiar
        this.resetLink.addEventListener('click', () => this.copyLink());
        this.resetLink.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.copyLink();
            }
        });

        // Botões de ação
        if (this.copyLinkBtn) {
            this.copyLinkBtn.addEventListener('click', () => this.copyLink());
        }

        if (this.backToFormBtn) {
            this.backToFormBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.resetForm();
            });
        }

        // Envio do formulário
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    }

    validateEmail() {
        const email = this.emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        // Remover classes de estado anteriores
        this.emailInput.classList.remove('error', 'success');
        
        if (email === '') {
            this.showFieldError('Email é obrigatório');
            return false;
        }

        if (!emailRegex.test(email)) {
            this.showFieldError('Email inválido');
            return false;
        }

        // Email válido
        this.emailInput.classList.add('success');
        this.clearFieldError();
        return true;
    }

    showFieldError(message) {
        this.emailInput.classList.add('error');
        const errorElement = document.getElementById('email-error');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }
    }

    clearFieldError() {
        this.emailInput.classList.remove('error', 'success');
        const errorElement = document.getElementById('email-error');
        if (errorElement) {
            errorElement.textContent = '';
            errorElement.classList.remove('show');
        }
    }

    showLoading(isLoading) {
        this.isSubmitting = isLoading;
        
        if (isLoading) {
            this.submitBtn.classList.add('loading');
            this.submitBtn.disabled = true;
            this.submitStatus.textContent = 'Enviando solicitação...';
        } else {
            this.submitBtn.classList.remove('loading');
            this.submitBtn.disabled = false;
            this.submitStatus.textContent = '';
        }
    }

    showResetLink(link) {
        // Atualizar o link
        this.resetLink.textContent = link;
        this.resetLink.setAttribute('data-link', link);
        
        // Mostrar o container de link
        this.resetLinkContent.style.display = 'block';
        this.resetLinkContainer.classList.add('visible');
        
        // Focar no link para acessibilidade
        setTimeout(() => {
            this.resetLink.focus();
        }, 100);
    }

    resetForm() {
        this.form.reset();
        this.emailInput.classList.remove('error', 'success');
        this.clearFieldError();
        this.resetLinkContainer.classList.remove('visible');
        this.resetLinkContent.style.display = 'none';
        this.submitBtn.classList.remove('success');
        this.btnText.textContent = 'Enviar Link de Recuperação';
        this.emailInput.focus();
    }

    async copyLink() {
        const link = this.resetLink.getAttribute('data-link');
        if (link) {
            try {
                await navigator.clipboard.writeText(link);
                this.showCopySuccess();
            } catch (err) {
                console.error('Erro ao copiar link:', err);
                this.showCopyError();
            }
        }
    }

    showCopySuccess() {
        // Feedback visual temporário
        const originalText = this.copyLinkBtn.innerHTML;
        this.copyLinkBtn.innerHTML = '<i class="fas fa-check" aria-hidden="true"></i> Copiado!';
        this.copyLinkBtn.style.background = 'var(--verde-medio)';
        
        setTimeout(() => {
            this.copyLinkBtn.innerHTML = originalText;
            this.copyLinkBtn.style.background = '';
        }, 2000);

        // Notificação para leitores de tela
        this.submitStatus.textContent = 'Link copiado para a área de transferência.';
        
        // SweetAlert se disponível
        if (typeof smartStockAlerts !== 'undefined') {
            smartStockAlerts.showSuccess('Link copiado!', 'O link foi copiado para a área de transferência.');
        }
    }

    showCopyError() {
        this.submitStatus.textContent = 'Erro ao copiar link. Tente novamente.';
        
        if (typeof smartStockAlerts !== 'undefined') {
            smartStockAlerts.showError('Erro', 'Não foi possível copiar o link. Por favor, copie manualmente.');
        }
    }

    async handleSubmit(e) {
        e.preventDefault();

        // Validar email
        if (!this.validateEmail()) {
            this.emailInput.focus();
            return;
        }

        // Salvar o email atual
        this.currentEmail = this.emailInput.value.trim();

        // Mostrar estado de carregamento
        this.showLoading(true);

        try {
            const formData = new FormData(this.form);

            // Enviar requisição
            const response = await fetch('../Backend/process_forgot_password.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.handleSuccess(result);
            } else {
                this.handleError(result.message || 'Ocorreu um erro ao processar sua solicitação.');
            }

        } catch (error) {
            console.error('Erro:', error);
            this.handleError('Não foi possível processar sua solicitação. Verifique sua conexão e tente novamente.');
        } finally {
            // Restaurar estado do botão
            this.showLoading(false);
        }
    }

    handleSuccess(result) {
        // Atualizar estado do formulário para sucesso
        this.submitBtn.classList.add('success');
        this.btnText.textContent = 'Link Enviado para ' + this.currentEmail;
        
        // Mostrar o link de redefinição se disponível
        if (result.reset_link) {
            this.showResetLink(result.reset_link);
        } else {
            // Se não houver link, apenas mostrar mensagem de sucesso
            this.resetLinkContent.style.display = 'none';
            this.resetLinkContainer.classList.add('visible');
            this.resetLink.focus();
        }

        // Feedback visual para usuários de leitor de tela
        const successMessage = result.reset_link ? 
            'Link de redefinição gerado com sucesso. Use o link fornecido para redefinir sua senha.' :
            'Instruções de redefinição de senha enviadas para o seu email.';
        
        this.submitStatus.textContent = successMessage;
    }

    handleError(message) {
        this.submitStatus.textContent = 'Erro ao processar solicitação.';
        
        if (typeof smartStockAlerts !== 'undefined') {
            smartStockAlerts.showError('Erro', message);
        }
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    new ForgotPasswordForm();
});

// Exportar para uso global se necessário
window.ForgotPasswordForm = ForgotPasswordForm;
