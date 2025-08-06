<?php
session_start();

// Gerar CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock - Recuperar Senha</title>
    <?php include 'includes/head.php'; ?>
</head>

<body>
    <?php include __DIR__ . '/includes/header.php'; ?>
    
    <div class="forgot-password-container">
        <div class="forgot-password-card">
            <!-- ===== HEADER ===== -->
            <div class="forgot-password-header">
                <h1 class="forgot-password-title">Recuperar Senha</h1>
                <p class="forgot-password-subtitle">
                    Digite seu email cadastrado e enviaremos um link para redefinir sua senha.
                </p>
            </div>

            <!-- ===== FORMULÁRIO ===== -->
            <form id="forgotPasswordForm" class="forgot-password-form" novalidate>
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <!-- Campo Email -->
                <div class="form-group">
                    <label for="email" class="form-label">E-mail</label>
                    <div class="input-wrapper">
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="form-input" 
                            placeholder="Digite seu endereço de email"
                            required
                            autocomplete="email"
                            aria-describedby="email-error"
                        >
                        <i class="fas fa-envelope input-icon" aria-hidden="true"></i>
                    </div>
                    <span class="error-message" id="email-error" role="alert"></span>
                </div>

                <!-- Botão de Envio -->
                <button type="submit" class="submit-button" id="submitBtn" aria-describedby="submit-status">
                    <span class="button-text">Enviar Link de Recuperação</span>
                    <span class="button-loading">
                        <div class="spinner" aria-hidden="true"></div>
                        Enviando...
                    </span>
                </button>
                <div id="submit-status" class="sr-only" aria-live="polite"></div>

                <!-- Container para o link de redefinição -->
                <div id="resetLinkContainer" class="reset-link-container" aria-live="polite">
                    <div class="reset-link-header">
                        <i class="fas fa-check-circle" aria-hidden="true"></i>
                        <h2 class="reset-link-title">Link Enviado!</h2>
                    </div>
                    <p class="reset-link-description">
                        Enviamos um link para redefinir sua senha. Por favor, verifique sua caixa de entrada.
                    </p>
                    
                    <div id="resetLinkContent" style="display: none;">
                        <p class="reset-link-description">
                            Se preferir, você pode copiar o link abaixo:
                        </p>
                        <div 
                            id="resetLink" 
                            class="reset-link-display" 
                            tabindex="0"
                            role="button"
                            aria-label="Link de redefinição de senha. Clique para copiar."
                        ></div>
                        
                        <div class="action-buttons">
                            <button type="button" id="copyLinkBtn" class="action-button copy-button">
                                <i class="far fa-copy" aria-hidden="true"></i>
                                Copiar Link
                            </button>
                            <button type="button" id="backToFormBtn" class="action-button back-button">
                                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                                Tentar outro email
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Links de Navegação -->
                <div class="navigation-links">
                    <a href="login.php" aria-label="Voltar para a página de login">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Voltar para o Login
                    </a>
                    <a href="cadastro.php" aria-label="Ir para a página de cadastro">
                        <i class="fas fa-user-plus" aria-hidden="true"></i>
                        Não tem conta? Cadastre-se
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== SCRIPT ===== -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script src="./ressources/js/script.js"></script>
    <script src="./ressources/js/alerts.js"></script>
    <script src="./ressources/js/forgot-password.js"></script>
</body>

</html>