<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

require_once('../Backend/conexao.php');

$id = $_SESSION['id_user'];

$stmt = $pdo->prepare("SELECT nome, sobrenome, email, foto_perfil FROM usuarios WHERE id_user = ?");
$stmt->execute([$id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se tem foto cadastrada
if (!empty($dados['foto_perfil'])) {
    $fotoPerfil = './uploads/' . $dados['foto_perfil'];
} else {
    $fotoPerfil = "./ressources/img/perfil.png";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock - Alterar Senha</title>
    <?php include 'includes/head.php'; ?>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <section class="updateperfil">
        <div class="logo">
            <img src="./ressources/img/smartstock.png" alt="SmartStock Logo">
        </div>

        <div class="form-update">
            <div class="form-container-update">
                <form action="../Backend/alterar_senha.php" method="post" id="alterarSenhaForm" novalidate>
                    <h3>Alterar Senha</h3>
                    <p class="form-info">Para sua segurança, confirme sua senha atual e defina uma nova senha forte</p>

                    <!-- SENHA ATUAL -->
                    <div class="form-group">
                        <label for="senha_atual">Senha Atual</label>
                        <input type="password" name="senha_atual" id="senha_atual" placeholder="•••••••••" required>
                        <span class="error-message" id="senha_atual-error"></span>
                    </div>

                    <!-- NOVA SENHA -->
                    <div class="form-group">
                        <label for="nova_senha">Nova Senha</label>
                        <input type="password" name="nova_senha" id="nova_senha" placeholder="•••••••••" required>
                        <span class="error-message" id="nova_senha-error"></span>
                        <div class="password-strength" id="nova_senha-strength"></div>
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

                    <!-- CONFIRMAR NOVA SENHA -->
                    <div class="form-group">
                        <label for="confirmar_senha">Confirmar Nova Senha</label>
                        <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder="•••••••••" required>
                        <span class="error-message" id="confirmar_senha-error"></span>
                    </div>

                    <!-- BOTÃO -->
                    <button type="submit" class="btn" id="submitBtn">
                        <span class="btn-text">Alterar Senha</span>
                        <span class="btn-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Processando...
                        </span>
                    </button>

                    <div class="form-links">
                        <a class="btn-secundario" href="updateperfil.php">Voltar para Editar Perfil</a>
                        <a class="btn-secundario" href="home.php">Voltar para o Início</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script src="./ressources/js/script.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('alterarSenhaForm');
            const senhaAtualInput = document.getElementById('senha_atual');
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
            
            // Validação da senha atual
            senhaAtualInput.addEventListener('blur', function() {
                const senha = this.value.trim();
                if (senha === '') {
                    showError(this, 'O campo senha atual é obrigatório');
                } else {
                    clearError(this);
                }
            });
            
            // Validação da nova senha
            novaSenhaInput.addEventListener('input', function() {
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
                if (confirmarSenhaInput.value) {
                    if (confirmarSenhaInput.value !== senha) {
                        showError(confirmarSenhaInput, 'As senhas não coincidem');
                    } else {
                        clearError(confirmarSenhaInput);
                    }
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
                
                const senhaAtual = senhaAtualInput.value.trim();
                const novaSenha = novaSenhaInput.value.trim();
                const confirmarSenha = confirmarSenhaInput.value.trim();
                let isValid = true;
                
                // Limpar erros anteriores
                clearError(senhaAtualInput);
                clearError(novaSenhaInput);
                clearError(confirmarSenhaInput);
                
                // Validar senha atual
                if (senhaAtual === '') {
                    showError(senhaAtualInput, 'O campo senha atual é obrigatório');
                    isValid = false;
                }
                
                // Validar nova senha
                if (novaSenha === '') {
                    showError(novaSenhaInput, 'O campo nova senha é obrigatório');
                    isValid = false;
                } else {
                    const requirements = validatePassword(novaSenha);
                    if (!requirements.length || !requirements.uppercase || !requirements.lowercase || !requirements.number || !requirements.symbol) {
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