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
    include __DIR__ . '/includes/headerog.php';
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
            
            senhaInput.addEventListener('blur', function() {
                const senha = this.value.trim();
                if (senha === '') {
                    smartStockAlerts.showFieldError(this, 'O campo senha é obrigatório');
                } else {
                    const passwordValidation = smartStockAlerts.validatePassword(senha);
                    if (!passwordValidation.isValid) {
                        smartStockAlerts.showFieldError(this, passwordValidation.errors[0]);
                    } else {
                        smartStockAlerts.clearFieldError(this);
                    }
                }
            });
            
            csenhaInput.addEventListener('blur', function() {
                const csenha = this.value.trim();
                const senha = senhaInput.value.trim();
                if (csenha === '') {
                    smartStockAlerts.showFieldError(this, 'O campo confirmar senha é obrigatório');
                } else if (csenha !== senha) {
                    smartStockAlerts.showFieldError(this, 'As senhas não coincidem');
                } else {
                    smartStockAlerts.clearFieldError(this);
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
                smartStockAlerts.clearFieldError(senhaInput);
                smartStockAlerts.clearFieldError(csenhaInput);
                smartStockAlerts.clearFieldError(codigoInput);
                
                // Validar formulário
                const validation = smartStockAlerts.validateCadastroForm(formData);
                
                if (!validation.isValid) {
                    // Mostrar primeiro erro
                    smartStockAlerts.showError('Campos obrigatórios', validation.errors[0]);
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
                smartStockAlerts.clearFieldError(this);
            });
            
            csenhaInput.addEventListener('input', function() {
                smartStockAlerts.clearFieldError(this);
            });
            
            codigoInput.addEventListener('input', function() {
                smartStockAlerts.clearFieldError(this);
            });
        });
    </script>
    
    <?php include 'includes/alerts.php'; ?>

</body>

</html>