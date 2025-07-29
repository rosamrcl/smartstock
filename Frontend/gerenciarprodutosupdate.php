<?php
session_start();
require_once("../Backend/conexao.php");

// Verifica se veio um ID na URL
if (!isset($_GET['id'])) {
    header("Location: ../Frontend/gerenciarprodutos.php");
    exit;
}

$id = (int)$_GET['id'];
$aba = isset($_GET['aba']) ? $_GET['aba'] : 'tab1';

// Busca o produto pelo ID
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id_products = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não encontrar o produto
if (!$produto) {
    $_SESSION['error_msg'] = ["Produto não encontrado."];
    header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock - Atualizar Produto</title>
    <?php include 'includes/head.php'; ?>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <section class="gerenciarprodutos">
        <div class="logo">
            <img src="./ressources/img/smartstock.png" alt="SmartStock Logo">
        </div>
        <div class="form-produto">
            <div class="form-container-produto">
                <form action="../Backend/produtos.php" method="post" id="updateForm" novalidate>
                    <h3>Atualizar Produto</h3>
                    <p class="form-info">Edite apenas os campos que deseja alterar</p>
                    
                    <input type="hidden" name="acao" value="editar">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    
                    <div class="form-group">
                        <label for="nome">Nome do Produto</label>
                        <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($produto['nome']); ?>" required>
                        <span class="error-message" id="nome-error"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <input type="text" name="descricao" id="descricao" value="<?= htmlspecialchars($produto['descricao']); ?>" required>
                        <span class="error-message" id="descricao-error"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" required>
                            <option value="">Selecione um status</option>
                            <option value="Estoque" <?= $produto['status'] === 'Estoque' ? 'selected' : '' ?>>Estoque</option>
                            <option value="Manutenção" <?= $produto['status'] === 'Manutenção' ? 'selected' : '' ?>>Manutenção</option>
                            <option value="Em uso" <?= $produto['status'] === 'Em uso' ? 'selected' : '' ?>>Em uso</option>
                        </select>
                        <span class="error-message" id="status-error"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantidade">Quantidade</label>
                        <input type="number" name="quantidade" id="quantidade" value="<?= htmlspecialchars($produto['quantidade']); ?>" min="0" required>
                        <span class="error-message" id="quantidade-error"></span>
                    </div>
                    
                    <button type="submit" class="btn" id="submitBtn">
                        <span class="btn-text">Atualizar Produto</span>
                        <span class="btn-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Processando...
                        </span>
                    </button>
                    
                    <div class="form-links">
                        <p><a href="gerenciarprodutos.php">Voltar para Gerenciar Produtos</a></p>
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
            const form = document.getElementById('updateForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');
            
            // Valores originais para comparação
            const valoresOriginais = {
                nome: '<?= htmlspecialchars($produto['nome']); ?>',
                descricao: '<?= htmlspecialchars($produto['descricao']); ?>',
                status: '<?= htmlspecialchars($produto['status']); ?>',
                quantidade: '<?= htmlspecialchars($produto['quantidade']); ?>'
            };
            
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
            
            function validateField(input) {
                const value = input.value.trim();
                
                if (input.hasAttribute('required') && value === '') {
                    showError(input, 'Este campo é obrigatório');
                    return false;
                }
                
                if (input.type === 'number' && value !== '' && parseInt(value) < 0) {
                    showError(input, 'A quantidade não pode ser negativa');
                    return false;
                }
                
                if (input.id === 'status' && value === '') {
                    showError(input, 'Selecione um status');
                    return false;
                }
                
                clearError(input);
                return true;
            }
            
            // Validação em tempo real
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                input.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        clearError(this);
                    }
                });
            });
            
            // Validação do formulário
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Validar todos os campos
                inputs.forEach(input => {
                    if (!validateField(input)) {
                        isValid = false;
                    }
                });
                
                if (isValid) {
                    // Mostrar loading
                    btnText.style.display = 'none';
                    btnLoading.style.display = 'flex';
                    submitBtn.disabled = true;
                    
                    // Permitir envio do formulário
                    return true;
                } else {
                    e.preventDefault();
                }
            });
        });
    </script>
    
    <?php include 'includes/alerts.php'; ?>

</body>
</html>