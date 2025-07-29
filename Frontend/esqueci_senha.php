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
    <title>SmartStock - Esqueci a Senha</title>
    <?php include 'includes/head.php'; ?>
    <link rel="stylesheet" href="./ressources/css/esquecisenha.css">
</head>

<body>
    <div class="container">
        <!-- ===== LOGO ===== -->
        <div class="logo">
            <img src="./ressources/img/smartstock.png" alt="SmartStock Logo">
        </div>

        <!-- ===== FORMULÁRIO ===== -->
        <div class="form">
            <div class="form-container">
                <form id="forgotPasswordForm" novalidate>
                    <h3>Recuperar Senha</h3>
                    <p class="form-info">
                        Digite seu email cadastrado e enviaremos um link para redefinir sua senha.
                    </p>

                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <!-- Campo Email -->
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" placeholder="Digite seu email" required>
                        <span class="error-message" id="email-error"></span>
                    </div>

                    <!-- Botão de Envio -->
                    <button type="submit" class="btn" id="submitBtn">
                        <span class="btn-text">Enviar Link de Recuperação</span>
                        <span class="btn-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Enviando...
                        </span>
                    </button>

                    <!-- Links de Navegação -->
                    <div class="form-links">
                        <p><a href="login.php">Voltar para o Login</a></p>
                        <p><a href="cadastro.php">Não tem conta? Cadastre-se</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== SCRIPT ===== -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script src="./ressources/js/script.js"></script>
    <script src="./ressources/js/alerts.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('forgotPasswordForm');
            const emailInput = document.getElementById('email');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // ===== VALIDAÇÃO EM TEMPO REAL =====
            emailInput.addEventListener('blur', function() {
                validateEmail(this);
            });

            emailInput.addEventListener('input', function() {
                clearFieldError(this);
            });

            // ===== VALIDAÇÃO DE EMAIL =====
            function validateEmail(input) {
                const email = input.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                
                if (email === '') {
                    smartStockAlerts.showFieldError(input, 'Email é obrigatório');
                    return false;
                }
                
                if (!emailRegex.test(email)) {
                    smartStockAlerts.showFieldError(input, 'Email inválido');
                    return false;
                }
                
                smartStockAlerts.clearFieldError(input);
                return true;
            }

            // ===== ENVIO DO FORMULÁRIO =====
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Validar email
                if (!validateEmail(emailInput)) {
                    return;
                }
                
                // Mostrar loading
                btnText.style.display = 'none';
                btnLoading.style.display = 'inline-flex';
                submitBtn.disabled = true;
                
                // Mostrar alerta de loading
                smartStockAlerts.showLoading('Processando...', 'Verificando seu email...');
                
                try {
                    const formData = new FormData(form);
                    
                    const response = await fetch('../Backend/process_forgot_password.php', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    // Fechar loading
                    smartStockAlerts.close();
                    
                    if (result.success) {
                        if (result.reset_link) {
                            // Para localhost, mostrar o link diretamente
                            await smartStockAlerts.showSuccess(
                                'Link Gerado!', 
                                'Clique no link abaixo para redefinir sua senha:'
                            );
                            
                            // Criar elemento para mostrar o link
                            const linkContainer = document.createElement('div');
                            linkContainer.style.cssText = `
                                margin: 20px 0;
                                padding: 15px;
                                background: #f8f9fa;
                                border: 1px solid #dee2e6;
                                border-radius: 8px;
                                word-break: break-all;
                            `;
                            
                            const link = document.createElement('a');
                            link.href = result.reset_link;
                            link.textContent = result.reset_link;
                            link.style.cssText = 'color: #007bff; text-decoration: none;';
                            
                            linkContainer.appendChild(link);
                            document.querySelector('.form-container').appendChild(linkContainer);
                            
                            // Adicionar botão para copiar link
                            const copyBtn = document.createElement('button');
                            copyBtn.textContent = 'Copiar Link';
                            copyBtn.className = 'btn';
                            copyBtn.style.cssText = 'margin-top: 10px; background: #28a745;';
                            copyBtn.onclick = () => {
                                navigator.clipboard.writeText(result.reset_link);
                                smartStockAlerts.showSuccess('Copiado!', 'Link copiado para a área de transferência.');
                            };
                            
                            linkContainer.appendChild(copyBtn);
                            
                        } else {
                            smartStockAlerts.showSuccess(
                                'Verificação Enviada', 
                                result.message
                            );
                        }
                        
                        // Limpar formulário
                        form.reset();
                        
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
</body>

</html>