<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock - Redefinir Senha</title>
    <?php include 'includes/head.php'; ?>
</head>

<body>
    <?php
    session_start();
    include 'includes/header.php';
    ?>

    <section class="esqueci">
        <div class="form-update">
            <div class="form-container-update">
                <form action="../Backend/atualizar_senha.php" method="post" id="redefinirForm" novalidate>
                    <h3>Redefinir Senha</h3>
                    <p class="form-info">Digite sua nova senha</p>
                    
                    <div class="form-group">
                        <label for="nova_senha">Nova Senha</label>
                        <input type="password" name="nova_senha" id="nova_senha" placeholder="•••••••••" required>
                        <span class="error-message" id="nova_senha-error"></span>
                        <div class="password-strength" id="nova_senha-strength"></div>
                        
                        <div class="password-requirements">
                            <div class="requirement" id="req-length">Pelo menos 8 caracteres</div>
                            <div class="requirement" id="req-uppercase">Uma letra maiúscula</div>
                            <div class="requirement" id="req-lowercase">Uma letra minúscula</div>
                            <div class="requirement" id="req-number">Um número</div>
                            <div class="requirement" id="req-symbol">Um caractere especial</div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirmar_senha">Confirmar Nova Senha</label>
                        <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder="•••••••••" required>
                        <span class="error-message" id="confirmar_senha-error"></span>
                    </div>
                    
                    <button type="submit" class="btn" id="submitBtn">
                        <span class="btn-text">Redefinir Senha</span>
                        <span class="btn-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Redefinindo...
                        </span>
                    </button>
                    
                    <div class="form-links">
                        <p><a href="login.php">Voltar para o Login</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    
    <?php
    include 'includes/footer.php';
    ?>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script src="./ressources/js/script.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('redefinirForm');
            const novaSenhaInput = document.getElementById('nova_senha');
            const confirmarSenhaInput = document.getElementById('confirmar_senha');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');
            const strengthDiv = document.getElementById('nova_senha-strength');
            
            // Funções de validação
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
            
            // Validação da nova senha
            novaSenhaInput.addEventListener('input', function() {
                const senha = this.value;
                const requirements = validatePassword(senha);
                const strength = getPasswordStrength(senha);
                
                strengthDiv.textContent = strength.text;
                strengthDiv.className = 'password-strength strength-' + strength.strength;
                updateRequirements(senha);
                
                if (senha === '') {
                    showError(this, 'O campo nova senha é obrigatório');
                } else {
                    clearError(this);
                }
            });
            
            // Validação da confirmação de senha
            confirmarSenhaInput.addEventListener('blur', function() {
                const confirmarSenha = this.value.trim();
                const novaSenha = novaSenhaInput.value.trim();
                
                if (confirmarSenha === '') {
                    showError(this, 'O campo confirmar senha é obrigatório');
                } else if (confirmarSenha !== novaSenha) {
                    showError(this, 'As senhas não coincidem');
                } else {
                    clearError(this);
                }
            });
            
            // Validação do formulário
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const novaSenha = novaSenhaInput.value.trim();
                const confirmarSenha = confirmarSenhaInput.value.trim();
                let isValid = true;
                
                // Limpar erros anteriores
                clearError(novaSenhaInput);
                clearError(confirmarSenhaInput);
                
                // Validar nova senha
                if (novaSenha === '') {
                    showError(novaSenhaInput, 'O campo nova senha é obrigatório');
                    isValid = false;
                } else {
                    const requirements = validatePassword(novaSenha);
                    const metRequirements = Object.values(requirements).filter(Boolean).length;
                    
                    if (metRequirements < 5) {
                        showError(novaSenhaInput, 'A senha não atende aos requisitos mínimos');
                        isValid = false;
                    }
                }
                
                // Validar confirmação de senha
                if (confirmarSenha === '') {
                    showError(confirmarSenhaInput, 'O campo confirmar senha é obrigatório');
                    isValid = false;
                } else if (confirmarSenha !== novaSenha) {
                    showError(confirmarSenhaInput, 'As senhas não coincidem');
                    isValid = false;
                }
                
                if (isValid) {
                    // Mostrar loading
                    btnText.style.display = 'none';
                    btnLoading.style.display = 'flex';
                    submitBtn.disabled = true;
                    
                    // Enviar formulário
                    form.submit();
                }
            });
        });
    </script>
    
    <?php include 'includes/alerts.php'; ?>

</body>
</html>