/**
 * Sistema de Validação JavaScript - SmartStock
 * 
 * Este arquivo contém funções de validação reutilizáveis
 * para todo o sistema, garantindo consistência e UX.
 */

// ===== CONFIGURAÇÕES GLOBAIS =====
const VALIDATION_CONFIG = {
    maxFileSize: 5 * 1024 * 1024, // 5MB
    allowedImageTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
    maxImageWidth: 2048,
    maxImageHeight: 2048
};

// ===== FUNÇÕES DE VALIDAÇÃO BÁSICAS =====

/**
 * Validação de Email
 * @param {string} email - Email a ser validado
 * @returns {boolean} - True se válido
 */
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Validação de Nome
 * @param {string} name - Nome a ser validado
 * @returns {boolean} - True se válido
 */
function validateName(name) {
    return name.trim().length >= 2 && /^[a-zA-ZÀ-ÿ\s]+$/.test(name);
}

/**
 * Validação de Senha
 * @param {string} password - Senha a ser validada
 * @param {boolean} strongPassword - Se deve validar senha forte
 * @returns {object} - Objeto com valid e requirements
 */
function validatePassword(password, strongPassword = false) {
    const requirements = {
        length: password.length >= (strongPassword ? 8 : 6),
        uppercase: strongPassword ? /[A-Z]/.test(password) : true,
        lowercase: strongPassword ? /[a-z]/.test(password) : true,
        number: strongPassword ? /[0-9]/.test(password) : true,
        symbol: strongPassword ? /[!@#$%^&*]/.test(password) : true
    };
    
    const valid = Object.values(requirements).every(req => req);
    
    return {
        valid,
        requirements,
        strength: calculatePasswordStrength(password, requirements)
    };
}

/**
 * Calcula a força da senha
 * @param {string} password - Senha
 * @param {object} requirements - Requisitos
 * @returns {string} - Força da senha (weak, medium, strong)
 */
function calculatePasswordStrength(password, requirements) {
    if (!requirements.length) return 'weak';
    
    const validRequirements = Object.values(requirements).filter(req => req).length;
    const totalRequirements = Object.keys(requirements).length;
    
    if (validRequirements === totalRequirements) return 'strong';
    if (validRequirements >= Math.ceil(totalRequirements / 2)) return 'medium';
    return 'weak';
}

/**
 * Validação de Quantidade
 * @param {string|number} quantity - Quantidade a ser validada
 * @param {number} min - Valor mínimo
 * @param {number} max - Valor máximo
 * @returns {boolean} - True se válido
 */
function validateQuantity(quantity, min = 0, max = null) {
    const num = parseInt(quantity);
    if (isNaN(num)) return false;
    if (num < min) return false;
    if (max !== null && num > max) return false;
    return true;
}

/**
 * Validação de Arquivo de Imagem
 * @param {File} file - Arquivo a ser validado
 * @returns {object} - Objeto com valid e message
 */
function validateImageFile(file) {
    if (!file) {
        return { valid: false, message: 'Nenhum arquivo foi selecionado' };
    }
    
    // Verificar tamanho
    if (file.size > VALIDATION_CONFIG.maxFileSize) {
        const maxSizeMB = VALIDATION_CONFIG.maxFileSize / 1024 / 1024;
        return { valid: false, message: `Arquivo muito grande. Máximo: ${maxSizeMB}MB` };
    }
    
    // Verificar tipo
    if (!VALIDATION_CONFIG.allowedImageTypes.includes(file.type)) {
        return { valid: false, message: 'Tipo de arquivo não suportado. Use: JPG, PNG, GIF ou WEBP' };
    }
    
    return { valid: true, message: 'Arquivo válido' };
}

// ===== FUNÇÕES DE MANIPULAÇÃO DE FORMULÁRIOS =====

/**
 * Mostra mensagem de erro
 * @param {HTMLElement} input - Elemento de input
 * @param {string} message - Mensagem de erro
 */
function showError(input, message) {
    const errorElement = document.getElementById(input.id + '-error');
    if (errorElement) {
        errorElement.textContent = message;
        input.classList.add('error');
    }
}

/**
 * Limpa mensagem de erro
 * @param {HTMLElement} input - Elemento de input
 */
function clearError(input) {
    const errorElement = document.getElementById(input.id + '-error');
    if (errorElement) {
        errorElement.textContent = '';
        input.classList.remove('error');
    }
}

/**
 * Limpa todos os erros do formulário
 * @param {HTMLFormElement} form - Formulário
 */
function clearAllErrors(form) {
    const errorElements = form.querySelectorAll('.error-message');
    const errorInputs = form.querySelectorAll('.error');
    
    errorElements.forEach(element => {
        element.textContent = '';
    });
    
    errorInputs.forEach(input => {
        input.classList.remove('error');
    });
}

/**
 * Formata tamanho de arquivo
 * @param {number} bytes - Bytes do arquivo
 * @returns {string} - Tamanho formatado
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

/**
 * Configura preview de imagem
 * @param {HTMLInputElement} input - Input de arquivo
 * @param {HTMLImageElement} preview - Elemento de preview
 * @param {HTMLElement} fileInfo - Elemento de informações do arquivo
 */
function setupImagePreview(input, preview, fileInfo) {
    input.addEventListener('change', function() {
        const file = this.files[0];
        
        if (file) {
            const validation = validateImageFile(file);
            
            if (validation.valid) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    fileInfo.textContent = `Arquivo: ${file.name} (${formatFileSize(file.size)})`;
                    fileInfo.className = 'file-info success';
                };
                reader.readAsDataURL(file);
            } else {
                fileInfo.textContent = validation.message;
                fileInfo.className = 'file-info error';
                this.value = '';
                preview.style.display = 'none';
            }
        } else {
            preview.style.display = 'none';
            fileInfo.textContent = '';
        }
    });
}

// ===== FUNÇÕES DE VALIDAÇÃO EM TEMPO REAL =====

/**
 * Configura validação de nome
 * @param {HTMLInputElement} input - Input de nome
 */
function setupNameValidation(input) {
    input.addEventListener('blur', function() {
        const name = this.value.trim();
        if (name === '') {
            showError(this, 'O campo nome é obrigatório');
        } else if (!validateName(name)) {
            showError(this, 'O nome deve ter pelo menos 2 caracteres');
        } else {
            clearError(this);
        }
    });
}

/**
 * Configura validação de sobrenome
 * @param {HTMLInputElement} input - Input de sobrenome
 */
function setupSurnameValidation(input) {
    input.addEventListener('blur', function() {
        const surname = this.value.trim();
        if (surname === '') {
            showError(this, 'O campo sobrenome é obrigatório');
        } else if (!validateName(surname)) {
            showError(this, 'O sobrenome deve ter pelo menos 2 caracteres');
        } else {
            clearError(this);
        }
    });
}

/**
 * Configura validação de email
 * @param {HTMLInputElement} input - Input de email
 */
function setupEmailValidation(input) {
    input.addEventListener('blur', function() {
        const email = this.value.trim();
        if (email === '') {
            showError(this, 'O campo email é obrigatório');
        } else if (!validateEmail(email)) {
            showError(this, 'Por favor, insira um email válido');
        } else {
            clearError(this);
        }
    });
}

/**
 * Configura validação de senha
 * @param {HTMLInputElement} input - Input de senha
 * @param {boolean} strongPassword - Se deve validar senha forte
 */
function setupPasswordValidation(input, strongPassword = false) {
    input.addEventListener('input', function() {
        const password = this.value;
        const validation = validatePassword(password, strongPassword);
        
        // Atualizar indicador de força da senha se existir
        const strengthIndicator = document.getElementById(this.id + '-strength');
        if (strengthIndicator) {
            updatePasswordStrength(password, validation);
        }
        
        // Atualizar requisitos se existirem
        const requirementsList = document.getElementById(this.id + '-requirements');
        if (requirementsList) {
            updatePasswordRequirements(validation.requirements);
        }
    });
}

// ===== FUNÇÕES DE ESTADO DE CARREGAMENTO =====

/**
 * Mostra estado de carregamento
 * @param {HTMLButtonElement} button - Botão
 * @param {string} loadingText - Texto de carregamento
 */
function showLoadingState(button, loadingText = 'Processando...') {
    const btnText = button.querySelector('.btn-text');
    const btnLoading = button.querySelector('.btn-loading');
    
    if (btnText && btnLoading) {
        button.disabled = true;
        btnText.style.display = 'none';
        btnLoading.style.display = 'flex';
        btnLoading.querySelector('span').textContent = loadingText;
    }
}

/**
 * Esconde estado de carregamento
 * @param {HTMLButtonElement} button - Botão
 * @param {string} originalText - Texto original
 */
function hideLoadingState(button, originalText) {
    const btnText = button.querySelector('.btn-text');
    const btnLoading = button.querySelector('.btn-loading');
    
    if (btnText && btnLoading) {
        button.disabled = false;
        btnText.style.display = 'flex';
        btnLoading.style.display = 'none';
        btnText.querySelector('span').textContent = originalText;
    }
}

// ===== FUNÇÕES DE VALIDAÇÃO DE FORMULÁRIOS ESPECÍFICOS =====

/**
 * Validação do formulário de login
 * @param {HTMLFormElement} form - Formulário de login
 * @returns {boolean} - True se válido
 */
function validateLoginForm(form) {
    const email = form.querySelector('input[name="email"]');
    const password = form.querySelector('input[name="password"]');
    
    let isValid = true;
    
    // Validar email
    if (!email || !validateEmail(email.value.trim())) {
        if (email) showError(email, 'Por favor, insira um email válido');
        isValid = false;
    }
    
    // Validar senha
    if (!password || password.value.trim().length < 6) {
        if (password) showError(password, 'A senha deve ter pelo menos 6 caracteres');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Validação do formulário de cadastro
 * @param {HTMLFormElement} form - Formulário de cadastro
 * @returns {boolean} - True se válido
 */
function validateCadastroForm(form) {
    const nome = form.querySelector('input[name="nome"]');
    const sobrenome = form.querySelector('input[name="sobrenome"]');
    const email = form.querySelector('input[name="email"]');
    const password = form.querySelector('input[name="password"]');
    const confirmPassword = form.querySelector('input[name="confirm_password"]');
    
    let isValid = true;
    
    // Validar nome
    if (!nome || !validateName(nome.value.trim())) {
        if (nome) showError(nome, 'O nome deve ter pelo menos 2 caracteres');
        isValid = false;
    }
    
    // Validar sobrenome
    if (!sobrenome || !validateName(sobrenome.value.trim())) {
        if (sobrenome) showError(sobrenome, 'O sobrenome deve ter pelo menos 2 caracteres');
        isValid = false;
    }
    
    // Validar email
    if (!email || !validateEmail(email.value.trim())) {
        if (email) showError(email, 'Por favor, insira um email válido');
        isValid = false;
    }
    
    // Validar senha
    if (!password || password.value.trim().length < 6) {
        if (password) showError(password, 'A senha deve ter pelo menos 6 caracteres');
        isValid = false;
    }
    
    // Validar confirmação de senha
    if (password && confirmPassword && password.value !== confirmPassword.value) {
        if (confirmPassword) showError(confirmPassword, 'As senhas não coincidem');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Validação do formulário de alterar senha
 * @param {HTMLFormElement} form - Formulário de alterar senha
 * @returns {boolean} - True se válido
 */
function validateAlterarSenhaForm(form) {
    const currentPassword = form.querySelector('input[name="current_password"]');
    const newPassword = form.querySelector('input[name="new_password"]');
    const confirmPassword = form.querySelector('input[name="confirm_password"]');
    
    let isValid = true;
    
    // Validar senha atual
    if (!currentPassword || currentPassword.value.trim().length < 6) {
        if (currentPassword) showError(currentPassword, 'A senha atual é obrigatória');
        isValid = false;
    }
    
    // Validar nova senha
    if (!newPassword || newPassword.value.trim().length < 6) {
        if (newPassword) showError(newPassword, 'A nova senha deve ter pelo menos 6 caracteres');
        isValid = false;
    }
    
    // Validar confirmação de senha
    if (newPassword && confirmPassword && newPassword.value !== confirmPassword.value) {
        if (confirmPassword) showError(confirmPassword, 'As senhas não coincidem');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Validação do formulário de produto
 * @param {HTMLFormElement} form - Formulário de produto
 * @returns {boolean} - True se válido
 */
function validateProdutoForm(form) {
    const nome = form.querySelector('input[name="nome"]');
    const descricao = form.querySelector('textarea[name="descricao"]');
    const quantidade = form.querySelector('input[name="quantidade"]');
    const status = form.querySelector('select[name="status"]');
    
    let isValid = true;
    
    // Validar nome
    if (!nome || nome.value.trim().length < 2) {
        if (nome) showError(nome, 'O nome do produto deve ter pelo menos 2 caracteres');
        isValid = false;
    }
    
    // Validar descrição
    if (!descricao || descricao.value.trim().length < 10) {
        if (descricao) showError(descricao, 'A descrição deve ter pelo menos 10 caracteres');
        isValid = false;
    }
    
    // Validar quantidade
    if (!quantidade || !validateQuantity(quantidade.value, 0)) {
        if (quantidade) showError(quantidade, 'A quantidade deve ser um número válido');
        isValid = false;
    }
    
    // Validar status
    if (!status || !status.value) {
        if (status) showError(status, 'Selecione um status');
        isValid = false;
    }
    
    return isValid;
}

// ===== FUNÇÕES AUXILIARES =====

/**
 * Atualiza indicador de força da senha
 * @param {string} password - Senha
 * @param {object} validation - Resultado da validação
 */
function updatePasswordStrength(password, validation) {
    const strengthIndicator = document.getElementById('password-strength');
    if (!strengthIndicator) return;
    
    strengthIndicator.className = `password-strength strength-${validation.strength}`;
    
    const strengthText = {
        weak: 'Fraca',
        medium: 'Média',
        strong: 'Forte'
    };
    
    strengthIndicator.textContent = strengthText[validation.strength] || 'Fraca';
}

/**
 * Atualiza lista de requisitos da senha
 * @param {object} requirements - Requisitos da senha
 */
function updatePasswordRequirements(requirements) {
    const requirementsList = document.getElementById('password-requirements');
    if (!requirementsList) return;
    
    const items = requirementsList.querySelectorAll('li');
    
    items.forEach(item => {
        const requirement = item.dataset.requirement;
        if (requirements[requirement]) {
            item.classList.add('valid');
            item.classList.remove('invalid');
        } else {
            item.classList.add('invalid');
            item.classList.remove('valid');
        }
    });
}

// ===== INICIALIZAÇÃO =====

/**
 * Inicializa o sistema de validação
 */
function initValidationSystem() {
    // Configurar validações em tempo real para inputs comuns
    document.addEventListener('DOMContentLoaded', function() {
        // Configurar validações de nome
        const nameInputs = document.querySelectorAll('input[name="nome"], input[name="sobrenome"]');
        nameInputs.forEach(input => {
            if (input.name === 'nome') {
                setupNameValidation(input);
            } else {
                setupSurnameValidation(input);
            }
        });
        
        // Configurar validações de email
        const emailInputs = document.querySelectorAll('input[type="email"], input[name="email"]');
        emailInputs.forEach(input => {
            setupEmailValidation(input);
        });
        
        // Configurar validações de senha
        const passwordInputs = document.querySelectorAll('input[type="password"], input[name="password"], input[name="new_password"]');
        passwordInputs.forEach(input => {
            setupPasswordValidation(input, true);
        });
        
        // Configurar preview de imagem
        const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
        imageInputs.forEach(input => {
            const preview = document.getElementById(input.id.replace('input', 'preview'));
            const fileInfo = document.getElementById(input.id.replace('input', 'info'));
            if (preview && fileInfo) {
                setupImagePreview(input, preview, fileInfo);
            }
        });
    });
}

// ===== EXPORTAÇÃO GLOBAL =====
window.ValidationSystem = {
    validateEmail,
    validateName,
    validatePassword,
    validateQuantity,
    validateImageFile,
    showError,
    clearError,
    clearAllErrors,
    formatFileSize,
    setupImagePreview,
    setupNameValidation,
    setupSurnameValidation,
    setupEmailValidation,
    setupPasswordValidation,
    showLoadingState,
    hideLoadingState,
    validateLoginForm,
    validateCadastroForm,
    validateAlterarSenhaForm,
    validateProdutoForm,
    updatePasswordStrength,
    updatePasswordRequirements,
    initValidationSystem
};

// Inicializar sistema
initValidationSystem(); 