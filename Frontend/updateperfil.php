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
    <title>SmartStock - Editar Perfil</title>
    <?php include 'includes/head.php'; ?>
</head>

<body>
    <?php
    include __DIR__ . '/includes/header.php';
    ?>

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


                    <a class="btn-secundario" href="alterar_senha.php">Alterar Senha</a>


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

    

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script src="./ressources/js/script.js"></script>
    <script src="./ressources/js/validation.js"></script>

    <!-- ===== SCRIPT ESPECÍFICO DA PÁGINA ===== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('updateForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

            // Função para mostrar loading
            function showLoading() {
                btnText.style.display = 'none';
                btnLoading.style.display = 'flex';
                submitBtn.disabled = true;
            }

            // Função para validar campos básicos
            function validateForm() {
                const nome = document.getElementById('nome').value.trim();
                const sobrenome = document.getElementById('sobrenome').value.trim();
                const email = document.getElementById('email').value.trim();
                let isValid = true;

                // Limpar erros anteriores
                document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
                document.querySelectorAll('.form-group input').forEach(input => input.classList.remove('error'));

                // Validar nome
                if (!nome) {
                    document.getElementById('nome-error').textContent = 'O campo nome é obrigatório';
                    document.getElementById('nome').classList.add('error');
                    isValid = false;
                }

                // Validar sobrenome
                if (!sobrenome) {
                    document.getElementById('sobrenome-error').textContent = 'O campo sobrenome é obrigatório';
                    document.getElementById('sobrenome').classList.add('error');
                    isValid = false;
                }

                // Validar email
                if (!email) {
                    document.getElementById('email-error').textContent = 'O campo email é obrigatório';
                    document.getElementById('email').classList.add('error');
                    isValid = false;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    document.getElementById('email-error').textContent = 'Por favor, insira um email válido';
                    document.getElementById('email').classList.add('error');
                    isValid = false;
                }

                return isValid;
            }

            // Validação do formulário
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (validateForm()) {
                    showLoading();
                    form.submit();
                }
            });

            // Preview de imagem
            const fotoInput = document.getElementById('foto');
            const previewImage = document.getElementById('previewImage');
            const fileInfo = document.getElementById('fileInfo');

            if (fotoInput && previewImage) {
                fotoInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        // Validar tipo de arquivo
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                        if (!allowedTypes.includes(file.type)) {
                            fileInfo.textContent = 'Tipo de arquivo não permitido. Use: JPG, PNG, GIF, WEBP';
                            fileInfo.className = 'file-info error';
                            return;
                        }

                        // Validar tamanho (5MB)
                        if (file.size > 5 * 1024 * 1024) {
                            fileInfo.textContent = 'Arquivo muito grande. Máximo 5MB';
                            fileInfo.className = 'file-info error';
                            return;
                        }

                        // Mostrar preview
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            fileInfo.textContent = `Arquivo: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                            fileInfo.className = 'file-info success';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
    <?php
    include __DIR__ . '/includes/footer.php';
    ?>

    <?php include 'includes/alerts.php'; ?>


</body>

</html>