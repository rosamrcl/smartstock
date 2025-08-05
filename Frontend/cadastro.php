<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock - Cadastro</title>
    <?php include 'includes/head.php'; ?>

</head>

<body>

    <?php
    session_start();
    include __DIR__ . '/includes/header.php';
    ?>

    <section class="cadastro container">
        

        <div class="logo">

            <img src="./ressources/img/smartstock.png" alt="SmartStock Logo">

        </div>

        <div class="form">

            <div class="form-container">

                <form action="../Backend/cadastro.php" method="post" id="cadastroForm" novalidate>

                    <h3>Cadastro</h3>

                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" name="nome" id="nome" placeholder="Nome" required>
                        <span class="error-message" id="nome-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="sobrenome">Sobrenome</label>
                        <input type="text" name="sobrenome" id="sobrenome" placeholder="Sobrenome" required>
                        <span class="error-message" id="sobrenome-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" placeholder="email@email.com" required>
                        <span class="error-message" id="email-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" id="senha" placeholder="•••••••••" required>
                        <span class="error-message" id="senha-error"></span>
                        <div class="password-strength" id="senha-strength"></div>
                        <div class="password-requirements" id="password-requirements">
                            <p><strong>Requisitos da senha:</strong></p>
                            <ul>
                                <li id="req-length">Mínimo 8 caracteres</li>
                                <li id="req-uppercase">Pelo menos 1 letra maiúscula</li>
                                <li id="req-lowercase">Pelo menos 1 letra minúscula</li>
                                <li id="req-number">Pelo menos 1 número</li>
                                <li id="req-symbol">Pelo menos 1 símbolo (!@#$%^&*)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="csenha">Confirmar Senha</label>
                        <input type="password" name="csenha" id="csenha" placeholder="•••••••••" required>
                        <span class="error-message" id="csenha-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="codigo_superior">Código do Superior</label>
                        <input type="text" name="codigo_superior" id="codigo_superior" placeholder="Código do Superior" required>
                        <span class="error-message" id="codigo_superior-error"></span>
                    </div>

                    <button type="submit" class="btn" id="submitBtn">
                        <span class="btn-text">Cadastrar</span>
                        <span class="btn-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Cadastrando...
                        </span>
                    </button>

                    <p>Já tem cadastro? <a href="login.php">Clique aqui</a></p>
                    <p>Esqueci minha senha <a href="esqueci_senha.php">Clique aqui</a></p>

                </form>

            </div>

        </div>

    </section>

    <?php
    include __DIR__ . '/includes/footer.php';
    ?>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <!-- Sistema de Alertas SmartStock -->
    <script src="./ressources/js/alerts.js"></script>
    <script src="./ressources/js/script.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('cadastroForm');
            const nomeInput = document.getElementById('nome');
            const sobrenomeInput = document.getElementById('sobrenome');
            const emailInput = document.getElementById('email');
            const senhaInput = document.getElementById('senha');
            const csenhaInput = document.getElementById('csenha');
            const codigoInput = document.getElementById('codigo_superior');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');
            const strengthDiv = document.getElementById('senha-strength');
            
            // Funções de validação de senha 
            function validatePassword(password) {
                const requirements = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    symbol: /[!@#$%^&*(),.?":{}|<>]/.test(password)
                };
                
                return requirements;
            }
            
            function getPasswordStrength(password) {
                const requirements = validatePassword(password);
                const metRequirements = Object.values(requirements).filter(Boolean).length;
                
                if (metRequirements < 3) return { strength: 'weak', text: 'Senha muito fraca' };
                if (metRequirements < 5) return { strength: 'medium', text: 'Senha média' };
                return { strength: 'strong', text: 'Senha forte' };
            }
            
            function updateRequirements(password) {
                const requirements = validatePassword(password);
                
                document.getElementById('req-length').className = requirements.length ? 'requirement-met' : 'requirement-not-met';
                document.getElementById('req-uppercase').className = requirements.uppercase ? 'requirement-met' : 'requirement-not-met';
                document.getElementById('req-lowercase').className = requirements.lowercase ? 'requirement-met' : 'requirement-not-met';
                document.getElementById('req-number').className = requirements.number ? 'requirement-met' : 'requirement-not-met';
                document.getElementById('req-symbol').className = requirements.symbol ? 'requirement-met' : 'requirement-not-met';
            }
            
            function showError(input, message) {
                const errorElement = document.getElementById(input.id + '-error');
                errorElement.textContent = message;
                input.classList.add('error');
            }
            
            function clearError(input) {
                const errorElement = document.getElementById(input.id + '-error');
                errorElement.textContent = '';
                input.classList.remove('error');
            }
            
            // Validação em tempo real
            nomeInput.addEventListener('blur', function() {
                const nome = this.value.trim();
                if (nome === '') {
                    smartStockAlerts.showFieldError(this, 'O campo nome é obrigatório');
                } else {
                    smartStockAlerts.clearFieldError(this);
                }
            });
            
            sobrenomeInput.addEventListener('blur', function() {
                const sobrenome = this.value.trim();
                if (sobrenome === '') {
                    smartStockAlerts.showFieldError(this, 'O campo sobrenome é obrigatório');
                } else {
                    smartStockAlerts.clearFieldError(this);
                }
            });
            
            emailInput.addEventListener('blur', function() {
                const email = this.value.trim();
                if (email === '') {
                    smartStockAlerts.showFieldError(this, 'O campo email é obrigatório');
                } else if (!smartStockAlerts.validateEmail(email)) {
                    smartStockAlerts.showFieldError(this, 'Por favor, insira um email válido');
                } else {
                    smartStockAlerts.clearFieldError(this);
                }
            });
            
            // Validação da senha 
            senhaInput.addEventListener('input', function() {
                const senha = this.value;
                const requirements = validatePassword(senha);
                const strength = getPasswordStrength(senha);
                
                strengthDiv.textContent = strength.text;
                strengthDiv.className = 'password-strength strength-' + strength.strength;
                updateRequirements(senha);
                
                if (senha === '') {
                    clearError(this);
                    strengthDiv.textContent = '';
                } else if (!requirements.length || !requirements.uppercase || !requirements.lowercase || !requirements.number || !requirements.symbol) {
                    showError(this, 'A senha não atende aos requisitos mínimos');
                } else {
                    clearError(this);
                }
                
                // Validar confirmação se já foi preenchida
                if (csenhaInput.value) {
                    if (csenhaInput.value !== senha) {
                        showError(csenhaInput, 'As senhas não coincidem');
                    } else {
                        clearError(csenhaInput);
                    }
                }
            });
            
            csenhaInput.addEventListener('blur', function() {
                const csenha = this.value.trim();
                const senha = senhaInput.value.trim();
                
                if (csenha === '') {
                    showError(this, 'O campo confirmar senha é obrigatório');
                } else if (csenha !== senha) {
                    showError(this, 'As senhas não coincidem');
                } else {
                    clearError(this);
                }
            });
            
            codigoInput.addEventListener('blur', function() {
                const codigo = this.value.trim();
                if (codigo === '') {
                    smartStockAlerts.showFieldError(this, 'O campo código do superior é obrigatório');
                } else {
                    smartStockAlerts.clearFieldError(this);
                }
            });
            
            // Validação do formulário
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = {
                    nome: nomeInput.value.trim(),
                    sobrenome: sobrenomeInput.value.trim(),
                    email: emailInput.value.trim(),
                    senha: senhaInput.value.trim(),
                    csenha: csenhaInput.value.trim(),
                    codigo_superior: codigoInput.value.trim()
                };
                
                // Limpar erros anteriores
                smartStockAlerts.clearFieldError(nomeInput);
                smartStockAlerts.clearFieldError(sobrenomeInput);
                smartStockAlerts.clearFieldError(emailInput);
                clearError(senhaInput);
                clearError(csenhaInput);
                smartStockAlerts.clearFieldError(codigoInput);
                
                let isValid = true;
                const errors = [];
                
                // Validar nome
                if (!formData.nome) {
                    smartStockAlerts.showFieldError(nomeInput, 'O campo nome é obrigatório');
                    errors.push('O campo nome é obrigatório');
                    isValid = false;
                }
                
                // Validar sobrenome
                if (!formData.sobrenome) {
                    smartStockAlerts.showFieldError(sobrenomeInput, 'O campo sobrenome é obrigatório');
                    errors.push('O campo sobrenome é obrigatório');
                    isValid = false;
                }
                
                // Validar email
                if (!formData.email) {
                    smartStockAlerts.showFieldError(emailInput, 'O campo email é obrigatório');
                    errors.push('O campo email é obrigatório');
                    isValid = false;
                } else if (!smartStockAlerts.validateEmail(formData.email)) {
                    smartStockAlerts.showFieldError(emailInput, 'Por favor, insira um email válido');
                    errors.push('Por favor, insira um email válido');
                    isValid = false;
                }
                
                // Validar senha (nova validação baseada no alterar_senha.php)
                if (!formData.senha) {
                    showError(senhaInput, 'O campo senha é obrigatório');
                    errors.push('O campo senha é obrigatório');
                    isValid = false;
                } else {
                    const requirements = validatePassword(formData.senha);
                    if (!requirements.length || !requirements.uppercase || !requirements.lowercase || !requirements.number || !requirements.symbol) {
                        showError(senhaInput, 'A senha não atende aos requisitos mínimos');
                        errors.push('A senha não atende aos requisitos mínimos');
                        isValid = false;
                    }
                }
                
                // Validar confirmação de senha
                if (!formData.csenha) {
                    showError(csenhaInput, 'O campo confirmar senha é obrigatório');
                    errors.push('O campo confirmar senha é obrigatório');
                    isValid = false;
                } else if (formData.csenha !== formData.senha) {
                    showError(csenhaInput, 'As senhas não coincidem');
                    errors.push('As senhas não coincidem');
                    isValid = false;
                }
                
                // Validar código do superior
                if (!formData.codigo_superior) {
                    smartStockAlerts.showFieldError(codigoInput, 'O campo código do superior é obrigatório');
                    errors.push('O campo código do superior é obrigatório');
                    isValid = false;
                }
                
                if (!isValid) {
                    // Mostrar primeiro erro
                    smartStockAlerts.showError('Campos obrigatórios', errors[0]);
                    return;
                }
                
                // Mostrar confirmação antes de cadastrar
                smartStockAlerts.showConfirm(
                    'Confirmar cadastro?',
                    'Deseja realmente criar sua conta com os dados informados?',
                    'Sim, cadastrar!',
                    'Cancelar'
                ).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar loading
                        const loadingAlert = smartStockAlerts.showLoading('Cadastrando...', 'Criando sua conta...');
                        
                        // Desabilitar botão
                        btnText.style.display = 'none';
                        btnLoading.style.display = 'flex';
                        submitBtn.disabled = true;
                        
                        // Enviar formulário após um pequeno delay para mostrar o loading
                        setTimeout(() => {
                            form.submit();
                        }, 500);
                    }
                });
            });
            
            // Limpar erros quando o usuário começa a digitar
            nomeInput.addEventListener('input', function() {
                smartStockAlerts.clearFieldError(this);
            });
            
            sobrenomeInput.addEventListener('input', function() {
                smartStockAlerts.clearFieldError(this);
            });
            
            emailInput.addEventListener('input', function() {
                smartStockAlerts.clearFieldError(this);
            });
            
            senhaInput.addEventListener('input', function() {
                clearError(this);
            });
            
            csenhaInput.addEventListener('input', function() {
                clearError(this);
            });
            
            codigoInput.addEventListener('input', function() {
                smartStockAlerts.clearFieldError(this);
            });
        });
    </script>
    
    <?php include 'includes/alerts.php'; ?>

</body>

</html>