<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock - Login</title>
    <?php include 'includes/head.php'; ?>
</head>

<body>
    <?php
    session_start();
    include __DIR__ . '/includes/header.php';
    ?>
    <section class="login container">

        <div class="logo">
            <img src="./ressources/img/smartstock.png" alt="SmartStock Logo">
        </div>

        <div class="form">
            <div class="form-container">
                <form action="../Backend/login.php" method="post" id="loginForm" novalidate>
                    <h3>Login</h3>
                    
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" placeholder="email@email.com" required>
                        <span class="error-message" id="email-error"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" id="senha" placeholder="•••••••••" required>
                        <span class="error-message" id="senha-error"></span>
                    </div>
                    
                    <p>Esqueci minha senha <a href="esqueci_senha.php">Clique aqui</a></p>
                    <p>Ainda não tem cadastro? <a href="cadastro.php">Clique aqui</a></p>
                    
                    <button type="submit" class="btn" id="submitBtn">
                        <span class="btn-text">Entrar</span>
                        <span class="btn-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Entrando...
                        </span>
                    </button>
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
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const senhaInput = document.getElementById('senha');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');
            
            // Validação em tempo real
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
            
            senhaInput.addEventListener('blur', function() {
                const senha = this.value.trim();
                if (senha === '') {
                    smartStockAlerts.showFieldError(this, 'O campo senha é obrigatório');
                } else {
                    smartStockAlerts.clearFieldError(this);
                }
            });
            
            // Validação do formulário
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = {
                    email: emailInput.value.trim(),
                    senha: senhaInput.value.trim()
                };
                
                // Limpar erros anteriores
                smartStockAlerts.clearFieldError(emailInput);
                smartStockAlerts.clearFieldError(senhaInput);
                
                // Validar formulário
                const validation = smartStockAlerts.validateLoginForm(formData);
                
                if (!validation.isValid) {
                    // Mostrar primeiro erro
                    smartStockAlerts.showError('Campos obrigatórios', validation.errors[0]);
                    return;
                }
                
                // Mostrar loading
                const loadingAlert = smartStockAlerts.showLoading('Entrando...', 'Validando suas credenciais...');
                
                // Desabilitar botão
                btnText.style.display = 'none';
                btnLoading.style.display = 'flex';
                submitBtn.disabled = true;
                
                // Enviar formulário após um pequeno delay para mostrar o loading
                setTimeout(() => {
                    form.submit();
                }, 500);
            });
            
            // Limpar erros quando o usuário começa a digitar
            emailInput.addEventListener('input', function() {
                smartStockAlerts.clearFieldError(this);
            });
            
            senhaInput.addEventListener('input', function() {
                smartStockAlerts.clearFieldError(this);
            });
        });
    </script>
    
    <?php include 'includes/alerts.php'; ?>

</body>
</html>