<?php
session_start();
include '../Backend/painel.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: ../Frontend/login.php');
    exit;
}

require_once('../Backend/conexao.php');

$id = $_SESSION['id_user'];

$stmt = $pdo->prepare("SELECT foto_perfil FROM usuarios WHERE id_user = ?");
$stmt->execute([$id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se tem foto cadastrada
if (!empty($dados['foto_perfil'])) {
    $fotoPerfil = './uploads/' . $dados['foto_perfil']; // imagem enviada pelo usuário
} else {
    $fotoPerfil = "./ressources/img/perfil.png"; // imagem padrão
}

// Busca apenas produtos que estão no ESTOQUE.
$stmtEstoque = $pdo->prepare("SELECT * FROM produtos WHERE status = 'Estoque'");
$stmtEstoque->execute();
$produtosEstoque = $stmtEstoque->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock - Ordem de Serviço</title>
    <?php include 'includes/head.php'; ?>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="checklist-wrapper">
        <!-- Header da Checklist -->
        <div class="checklist-header">
            <div class="checklist-header-content">
                <div class="checklist-header-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="checklist-header-text">
                    <h2>Ordem de Serviço</h2>
                    <p>Complete o checklist para finalizar a ordem de serviço</p>
                </div>
            </div>
        </div>

        <!-- Container do Formulário -->
        <div class="checklist-form-container">
            <div class="checklist-form-grid">
                <!-- Seção de Categorias -->
                <div class="categories-section">
                    <div class="section-title">
                        <i class="fas fa-list-check"></i>
                        <span>Categorias de Verificação</span>
                    </div>

                    <div class="categories-grid">
                        <!-- Categoria: Equipamentos -->
                        <div class="category-card">
                            <div class="category-header">
                                <i class="fas fa-tools"></i>
                                <h4>Equipamentos</h4>
                            </div>
                            <div class="category-items">
                                <label class="checkbox-item">
                                    <input type="checkbox" name="equipamentos[]" value="verificar_funcionamento">
                                    <span class="checkmark"></span>
                                    Verificar funcionamento do equipamento
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="equipamentos[]" value="limpar_componentes">
                                    <span class="checkmark"></span>
                                    Limpar componentes
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="equipamentos[]" value="verificar_temperatura">
                                    <span class="checkmark"></span>
                                    Verificar temperatura
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="equipamentos[]" value="testar_perifericos">
                                    <span class="checkmark"></span>
                                    Testar periféricos
                                </label>
                            </div>
                        </div>

                        <!-- Categoria: Software -->
                        <div class="category-card">
                            <div class="category-header">
                                <i class="fas fa-laptop-code"></i>
                                <h4>Software</h4>
                            </div>
                            <div class="category-items">
                                <label class="checkbox-item">
                                    <input type="checkbox" name="software[]" value="atualizar_firmware">
                                    <span class="checkmark"></span>
                                    Atualizar firmware
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="software[]" value="verificar_atualizacoes">
                                    <span class="checkmark"></span>
                                    Verificar atualizações do sistema
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="software[]" value="testar_aplicacoes">
                                    <span class="checkmark"></span>
                                    Testar aplicações críticas
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="software[]" value="verificar_antivirus">
                                    <span class="checkmark"></span>
                                    Verificar antivírus
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="software[]" value="fazer_backup">
                                    <span class="checkmark"></span>
                                    Fazer backup de dados
                                </label>
                            </div>
                        </div>

                        <!-- Categoria: Segurança -->
                        <div class="category-card">
                            <div class="category-header">
                                <i class="fas fa-shield-alt"></i>
                                <h4>Segurança</h4>
                            </div>
                            <div class="category-items">
                                <label class="checkbox-item">
                                    <input type="checkbox" name="seguranca[]" value="verificar_cameras">
                                    <span class="checkmark"></span>
                                    Verificar câmeras de segurança
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="seguranca[]" value="testar_alarme">
                                    <span class="checkmark"></span>
                                    Testar sistema de alarme
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="seguranca[]" value="verificar_controle_acesso">
                                    <span class="checkmark"></span>
                                    Verificar controle de acesso
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="seguranca[]" value="testar_sensores">
                                    <span class="checkmark"></span>
                                    Testar sensores
                                </label>
                            </div>
                        </div>

                        <!-- Categoria: Documentação -->
                        <div class="category-card">
                            <div class="category-header">
                                <i class="fas fa-file-alt"></i>
                                <h4>Documentação</h4>
                            </div>
                            <div class="category-items">
                                <label class="checkbox-item">
                                    <input type="checkbox" name="documentacao[]" value="atualizar_documentacao">
                                    <span class="checkmark"></span>
                                    Atualizar documentação técnica
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="documentacao[]" value="registrar_alteracoes">
                                    <span class="checkmark"></span>
                                    Registrar alterações realizadas
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="documentacao[]" value="fotografar_instalacao">
                                    <span class="checkmark"></span>
                                    Fotografar instalação
                                </label>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="documentacao[]" value="gerar_relatorio">
                                    <span class="checkmark"></span>
                                    Gerar relatório de serviço
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seção de Informações -->
                <div class="info-section">
                    <div class="section-title">
                        <i class="fas fa-info-circle"></i>
                        <span>Informações do Serviço</span>
                    </div>

                    <form action="../Backend/marcar_concluido.php" method="post" id="checklistForm">
                        <div class="form-group">
                            <label for="cliente">Cliente</label>
                            <input type="text" name="cliente" id="cliente" placeholder="Nome do cliente" required>
                            <span class="error-message" id="cliente-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="local">Local</label>
                            <input type="text" name="local" id="local" placeholder="Endereço do serviço" required>
                            <span class="error-message" id="local-error"></span>
                        </div>

                        <div class="divider"></div>

                        <div class="form-group">
                            <label for="observacoes">Observações</label>
                            <textarea name="observacoes" id="observacoes" placeholder="Observações adicionais" rows="4"></textarea>
                        </div>

                        <button type="submit" class="btn" id="submitBtn">
                            <span class="btn-text">Finalizar OS</span>
                            <span class="btn-loading" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i> Finalizando...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script src="./ressources/js/script.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('checklistForm');
            const clienteInput = document.getElementById('cliente');
            const localInput = document.getElementById('local');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnLoading = submitBtn.querySelector('.btn-loading');

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

            // Validação do cliente
            clienteInput.addEventListener('blur', function() {
                const cliente = this.value.trim();
                if (cliente === '') {
                    showError(this, 'O campo cliente é obrigatório');
                } else {
                    clearError(this);
                }
            });

            // Validação do local
            localInput.addEventListener('blur', function() {
                const local = this.value.trim();
                if (local === '') {
                    showError(this, 'O campo local é obrigatório');
                } else {
                    clearError(this);
                }
            });

            // Validação do formulário
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const cliente = clienteInput.value.trim();
                const local = localInput.value.trim();
                let isValid = true;

                // Limpar erros anteriores
                clearError(clienteInput);
                clearError(localInput);

                // Validar cliente
                if (cliente === '') {
                    showError(clienteInput, 'O campo cliente é obrigatório');
                    isValid = false;
                }

                // Validar local
                if (local === '') {
                    showError(localInput, 'O campo local é obrigatório');
                    isValid = false;
                }

                // Verificar se pelo menos uma etapa foi selecionada
                const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
                if (checkboxes.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Nenhuma Etapa Selecionada',
                        text: 'Selecione pelo menos uma etapa do checklist para continuar.'
                    });
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