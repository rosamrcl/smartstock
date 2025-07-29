<?php

session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

require_once('../Backend/conexao.php');

$id = $_SESSION['id_user'];

$stmt = $pdo->prepare("SELECT nome, sobrenome, email, foto FROM usuarios WHERE id_user = ?");
$stmt->execute([$id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se tem foto cadastrada
if (!empty($dados['foto'])) {
    $fotoPerfil = './uploads/' . $dados['foto'];
} else {
    $fotoPerfil = "./ressources/img/perfil.png";
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock - Editar Perfil</title>
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
                <form action="../Backend/atualizar.php" method="post" enctype="multipart/form-data" id="updateForm" novalidate>
                    <h3>Editar Perfil</h3>

                    <div class="foto-update-perfil">
                        <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de Perfil" id="previewImage">
                        
                        <div class="upload-container">
                            <label for="foto" class="upload-label">
                                <i class="fas fa-camera"></i> Escolher Foto
                            </label>
                            <input type="file" name="foto" id="foto" accept=".jpg,.jpeg,.png,.gif,.webp" style="display: none;">
                            <div class="file-info" id="fileInfo"></div>
                        </div>
                    </div>

                    <div class="form-group">
                    <label for="nome">Nome</label>
                        <input type="text" name="nome" id="nome" placeholder="Nome" value="<?= htmlspecialchars($dados['nome'] ?? '') ?>" required>
                        <span class="error-message" id="nome-error"></span>
                    </div>

                    <div class="form-group">
                    <label for="sobrenome">Sobrenome</label>
                        <input type="text" name="sobrenome" id="sobrenome" placeholder="Sobrenome" value="<?= htmlspecialchars($dados['sobrenome'] ?? '') ?>" required>
                        <span class="error-message" id="sobrenome-error"></span>
                    </div>

                    <div class="form-group">
                    <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" placeholder="email@email.com" value="<?= htmlspecialchars($dados['email'] ?? '') ?>" required>
                        <span class="error-message" id="email-error"></span>
                    </div>

                    <div class="form-links">
                        <p><a href="alterar_senha.php" class="btn-secundario">Alterar Senha</a></p>
                    </div>

                    <button type="submit" class="btn" id="submitBtn">
                        <span class="btn-text">Atualizar Perfil</span>
                        <span class="btn-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Atualizando...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script src="./ressources/js/script.js"></script>
    
    <!-- ===== SCRIPT ESPECÍFICO DA PÁGINA ===== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('updateForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');
            
            // Validação do formulário
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Usar funções do ValidationSystem
                if (window.ValidationSystem) {
                    const nome = document.getElementById('nome').value.trim();
                    const sobrenome = document.getElementById('sobrenome').value.trim();
                    const email = document.getElementById('email').value.trim();
                    const file = document.getElementById('foto').files[0];
                    let isValid = true;
                    
                    // Limpar erros anteriores
                    window.ValidationSystem.clearAllErrors(form);
                    
                    // Validar campos usando funções do ValidationSystem
                    if (!window.ValidationSystem.validateName(nome)) {
                        window.ValidationSystem.showError(document.getElementById('nome'), 'O campo nome é obrigatório');
                        isValid = false;
                    }
                    
                    if (!window.ValidationSystem.validateName(sobrenome)) {
                        window.ValidationSystem.showError(document.getElementById('sobrenome'), 'O campo sobrenome é obrigatório');
                        isValid = false;
                    }
                    
                    if (!window.ValidationSystem.validateEmail(email)) {
                        window.ValidationSystem.showError(document.getElementById('email'), 'Por favor, insira um email válido');
                        isValid = false;
                    }
                    
                    // Validar arquivo se selecionado
                    if (file) {
                        const validation = window.ValidationSystem.validateImageFile(file);
                        if (!validation.valid) {
                            document.getElementById('fileInfo').textContent = validation.message;
                            document.getElementById('fileInfo').className = 'file-info error';
                            isValid = false;
                        }
                    }
                    
                    if (isValid) {
                        // Mostrar loading usando função do ValidationSystem
                        window.ValidationSystem.showLoadingState(submitBtn, 'Atualizando...');
                        
                        // Enviar formulário
                        form.submit();
                    }
                }
            });
        });
    </script>
    
    <?php include 'includes/alerts.php'; ?>

</body>
</html>