<?php
session_start();
require_once '../Backend/conexao.php';

// Gerar CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Validar token na URL
$token = $_GET['token'] ?? '';
$tokenValid = false;
$tokenError = '';

if (!empty($token)) {
    // Limpar tokens expirados
    $stmt = $pdo->prepare("DELETE FROM password_reset_tokens WHERE expires_at < NOW() OR used = 1");
    $stmt->execute();

    // Verificar se token é válido
    $stmt = $pdo->prepare("
        SELECT id, user_email, expires_at, used 
        FROM password_reset_tokens 
        WHERE token = ? AND expires_at > NOW() AND used = 0
    ");
    $stmt->execute([$token]);
    $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($tokenData) {
        $tokenValid = true;
    } else {
        $tokenError = 'Token inválido, expirado ou já utilizado.';
    }
} else {
    $tokenError = 'Token de recuperação não fornecido.';
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock - Redefinir Senha</title>
    <?php include 'includes/head.php'; ?>
    <link rel="stylesheet" href="./ressources/css/redefinir-senha.css">
</head>

<body>
    <?php
    include __DIR__ . '/includes/header.php';
    ?>
    <div class="container">
        <!-- ===== LOGO ===== -->
        <div class="logo">
            <img src="./ressources/img/smartstock.png" alt="SmartStock Logo">
        </div>

        <!-- ===== FORMULÁRIO ===== -->
        <div class="form">
            <div class="form-container">
                <?php if ($tokenValid): ?>
                    <form id="resetPasswordForm" novalidate>
                        <h3>Redefinir Senha</h3>
                        <p class="form-info">
                            Digite sua nova senha. Certifique-se de que ela seja forte e segura.
                        </p>

                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                        <!-- Nova Senha -->
                        <div class="form-group">
                            <label for="new_password">Nova Senha</label>
                            <input type="password" name="new_password" id="new_password" placeholder="•••••••••" required>
                            <span class="error-message" id="new_password-error"></span>
                            <div class="password-strength" id="new_password-strength"></div>
                        </div>

                        <!-- Confirmar Senha -->
                        <div class="form-group">
                            <label for="confirm_password">Confirmar Nova Senha</label>
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="•••••••••" required>
                            <span class="error-message" id="confirm_password-error"></span>
                        </div>

                        <!-- Requisitos da Senha -->
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

                        <!-- Botão de Envio -->
                        <button type="submit" class="btn" id="submitBtn">
                            <span class="btn-text">Redefinir Senha</span>
                            <span class="btn-loading" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i> Processando...
                            </span>
                        </button>

                        <!-- Links de Navegação -->
                        <div class="form-links">
                            <p><a href="login.php">Voltar para o Login</a></p>
                        </div>
                    </form>
                <?php else: ?>
                    <!-- Token Inválido -->
                    <div class="error-container">
                        <div class="error-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h3>Token Inválido</h3>
                        <p class="error-message"><?= htmlspecialchars($tokenError) ?></p>
                        <div class="error-actions">
                            <a href="esqueci_senha.php" class="btn">Solicitar Novo Link</a>
                            <a href="login.php" class="btn btn-secondary">Voltar para o Login</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ===== SCRIPT ===== -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script src="./ressources/js/script.js"></script>
    <script src="./ressources/js/alerts.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('resetPasswordForm');
            if (!form) return; // Se não há formulário, não executar

            const newPasswordInput = document.getElementById('new_password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // ===== VALIDAÇÃO DE FORÇA DA SENHA =====
            function validatePasswordStrength(password) {
                const requirements = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    symbol: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
                };

                // Atualizar indicadores visuais
                document.getElementById('req-length').className = requirements.length ? 'valid' : 'invalid';
                document.getElementById('req-uppercase').className = requirements.uppercase ? 'valid' : 'invalid';
                document.getElementById('req-lowercase').className = requirements.lowercase ? 'valid' : 'invalid';
                document.getElementById('req-number').className = requirements.number ? 'valid' : 'invalid';
                document.getElementById('req-symbol').className = requirements.symbol ? 'valid' : 'invalid';

                // Calcular força da senha
                const strength = Object.values(requirements).filter(Boolean).length;
                const strengthElement = document.getElementById('new_password-strength');

                let strengthText = '';
                let strengthClass = '';

                if (strength <= 2) {
                    strengthText = 'Fraca';
                    strengthClass = 'weak';
                } else if (strength <= 4) {
                    strengthText = 'Média';
                    strengthClass = 'medium';
                } else {
                    strengthText = 'Forte';
                    strengthClass = 'strong';
                }

                strengthElement.textContent = `Força: ${strengthText}`;
                strengthElement.className = `password-strength ${strengthClass}`;

                return strength === 5; // Todas as condições atendidas
            }

            // ===== VALIDAÇÃO EM TEMPO REAL =====
            newPasswordInput.addEventListener('input', function() {
                validatePasswordStrength(this.value);
                if (confirmPasswordInput.value) {
                    validatePasswordMatch();
                }
            });

            confirmPasswordInput.addEventListener('input', function() {
                validatePasswordMatch();
            });

            // ===== VALIDAÇÃO DE CONFIRMAÇÃO =====
            function validatePasswordMatch() {
                const newPassword = newPasswordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                if (confirmPassword && newPassword !== confirmPassword) {
                    smartStockAlerts.showFieldError(confirmPasswordInput, 'As senhas não coincidem');
                    return false;
                } else {
                    smartStockAlerts.clearFieldError(confirmPasswordInput);
                    return true;
                }
            }

            // ===== ENVIO DO FORMULÁRIO =====
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const newPassword = newPasswordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                // Validações
                if (!validatePasswordStrength(newPassword)) {
                    smartStockAlerts.showError('Senha Fraca', 'Sua senha não atende aos requisitos mínimos de segurança.');
                    return;
                }

                if (!validatePasswordMatch()) {
                    return;
                }

                // Mostrar loading
                btnText.style.display = 'none';
                btnLoading.style.display = 'inline-flex';
                submitBtn.disabled = true;

                // Mostrar alerta de loading
                smartStockAlerts.showLoading('Processando...', 'Atualizando sua senha...');

                try {
                    const formData = new FormData(form);

                    const response = await fetch('../Backend/process_reset_password.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    // Fechar loading
                    smartStockAlerts.close();

                    if (result.success) {
                        await smartStockAlerts.showSuccess(
                            'Senha Atualizada!',
                            result.message
                        );

                        // Redirecionar para login após 2 segundos
                        setTimeout(() => {
                            window.location.href = result.redirect;
                        }, 2000);

                    } else {
                        smartStockAlerts.showError('Erro', result.message);
                    }

                } catch (error) {
                    smartStockAlerts.close();
                    smartStockAlerts.showError(
                        'Erro de Conexão',
                        'Não foi possível processar sua solicitação. Tente novamente.'
                    );
                    console.error('Erro:', error);
                }

                // Restaurar botão
                btnText.style.display = 'inline-flex';
                btnLoading.style.display = 'none';
                submitBtn.disabled = false;
            });
        });
    </script>
    <?php
    include __DIR__ . '/includes/footer.php';
    ?>
</body>

</html>