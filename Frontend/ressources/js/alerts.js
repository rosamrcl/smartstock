/**
 * Sistema de Alertas SmartStock
 * Usando SweetAlert2 para feedback visual do usuário
 */

class SmartStockAlerts {
    constructor() {
        this.defaultConfig = {
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'OK',
            cancelButtonText: 'Cancelar'
        };
    }

    /**
     * Alerta de erro
     * @param {string} title - Título do alerta
     * @param {string} message - Mensagem do alerta
     * @param {string} icon - Ícone (padrão: 'error')
     */
    showError(title, message, icon = 'error') {
        return Swal.fire({
            icon: icon,
            title: title,
            text: message,
            confirmButtonColor: '#d33',
            ...this.defaultConfig
        });
    }

    /**
     * Alerta de sucesso
     * @param {string} title - Título do alerta
     * @param {string} message - Mensagem do alerta
     * @param {string} icon - Ícone (padrão: 'success')
     */
    showSuccess(title, message, icon = 'success') {
        return Swal.fire({
            icon: icon,
            title: title,
            text: message,
            confirmButtonColor: '#28a745',
            ...this.defaultConfig
        });
    }

    /**
     * Alerta de aviso
     * @param {string} title - Título do alerta
     * @param {string} message - Mensagem do alerta
     * @param {string} icon - Ícone (padrão: 'warning')
     */
    showWarning(title, message, icon = 'warning') {
        return Swal.fire({
            icon: icon,
            title: title,
            text: message,
            confirmButtonColor: '#ffc107',
            ...this.defaultConfig
        });
    }

    /**
     * Alerta de informação
     * @param {string} title - Título do alerta
     * @param {string} message - Mensagem do alerta
     * @param {string} icon - Ícone (padrão: 'info')
     */
    showInfo(title, message, icon = 'info') {
        return Swal.fire({
            icon: icon,
            title: title,
            text: message,
            confirmButtonColor: '#17a2b8',
            ...this.defaultConfig
        });
    }

    /**
     * Alerta de confirmação
     * @param {string} title - Título do alerta
     * @param {string} message - Mensagem do alerta
     * @param {string} confirmText - Texto do botão confirmar
     * @param {string} cancelText - Texto do botão cancelar
     */
    showConfirm(title, message, confirmText = 'Sim, confirmar!', cancelText = 'Cancelar') {
        return Swal.fire({
            title: title,
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmText,
            cancelButtonText: cancelText
        });
    }

    /**
     * Alerta de loading
     * @param {string} title - Título do alerta
     * @param {string} message - Mensagem do alerta
     */
    showLoading(title = 'Processando...', message = 'Aguarde enquanto validamos seus dados.') {
        return Swal.fire({
            title: title,
            text: message,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    /**
     * Fechar alerta atual
     */
    close() {
        Swal.close();
    }

    /**
     * Validar email
     * @param {string} email - Email a ser validado
     * @returns {boolean} - True se válido, false caso contrário
     */
    validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    /**
     * Validar senha
     * @param {string} password - Senha a ser validada
     * @returns {object} - Objeto com validação e mensagens
     */
    validatePassword(password) {
        const validations = {
            isValid: true,
            errors: [],
            suggestions: []
        };

        // Validação básica para compatibilidade com outros formulários
        if (password.length < 8) {
            validations.isValid = false;
            validations.errors.push('A senha deve ter pelo menos 8 caracteres');
        }

        if (!/[A-Z]/.test(password)) {
            validations.suggestions.push('Adicione pelo menos uma letra maiúscula');
        }

        if (!/[a-z]/.test(password)) {
            validations.suggestions.push('Adicione pelo menos uma letra minúscula');
        }

        if (!/\d/.test(password)) {
            validations.suggestions.push('Adicione pelo menos um número');
        }

        if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
            validations.suggestions.push('Adicione pelo menos um caractere especial');
        }

        return validations;
    }

    /**
     * Mostrar erro de validação de campo
     * @param {HTMLElement} input - Elemento input
     * @param {string} message - Mensagem de erro
     */
    showFieldError(input, message) {
        const errorElement = document.getElementById(input.id + '-error');
        if (errorElement) {
            errorElement.textContent = message;
            input.classList.add('error');
        }
    }

    /**
     * Limpar erro de validação de campo
     * @param {HTMLElement} input - Elemento input
     */
    clearFieldError(input) {
        const errorElement = document.getElementById(input.id + '-error');
        if (errorElement) {
            errorElement.textContent = '';
            input.classList.remove('error');
        }
    }

    /**
     * Validar formulário de login
     * @param {Object} formData - Dados do formulário
     * @returns {Object} - Resultado da validação
     */
    validateLoginForm(formData) {
        const errors = [];

        if (!formData.email || formData.email.trim() === '') {
            errors.push('O campo email é obrigatório');
        } else if (!this.validateEmail(formData.email)) {
            errors.push('Por favor, insira um email válido');
        }

        if (!formData.senha || formData.senha.trim() === '') {
            errors.push('O campo senha é obrigatório');
        }

        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }

    /**
     * Validar formulário de cadastro
     * @param {Object} formData - Dados do formulário
     * @returns {Object} - Resultado da validação
     */
    validateCadastroForm(formData) {
        const errors = [];

        if (!formData.nome || formData.nome.trim() === '') {
            errors.push('O campo nome é obrigatório');
        }

        if (!formData.sobrenome || formData.sobrenome.trim() === '') {
            errors.push('O campo sobrenome é obrigatório');
        }

        if (!formData.email || formData.email.trim() === '') {
            errors.push('O campo email é obrigatório');
        } else if (!this.validateEmail(formData.email)) {
            errors.push('Por favor, insira um email válido');
        }

        if (!formData.senha || formData.senha.trim() === '') {
            errors.push('O campo senha é obrigatório');
        } else {
            const passwordValidation = this.validatePassword(formData.senha);
            if (!passwordValidation.isValid) {
                errors.push(...passwordValidation.errors);
            }
        }

        if (!formData.csenha || formData.csenha.trim() === '') {
            errors.push('O campo confirmar senha é obrigatório');
        } else if (formData.senha !== formData.csenha) {
            errors.push('As senhas não coincidem');
        }

        if (!formData.codigo_superior || formData.codigo_superior.trim() === '') {
            errors.push('O campo código do superior é obrigatório');
        }

        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }
}

// Instância global do sistema de alertas
const smartStockAlerts = new SmartStockAlerts(); 