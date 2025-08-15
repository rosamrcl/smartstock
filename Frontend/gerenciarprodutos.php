<?php
session_start();

if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

require_once("../Backend/conexao.php");

// Detectar aba ativa
$aba_ativa = isset($_GET['aba']) ? $_GET['aba'] : 'tab1';

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock - Gerenciar Produtos</title>
    <link rel="stylesheet" href="./ressources/css/style.css">
    <link rel="stylesheet" href="./ressources/css/alerts.css">
    <link rel="stylesheet" href="./ressources/css/login-visibility-fix.css">
    <link rel="stylesheet" href="./ressources/css/login-alignment.css">
    <link rel="stylesheet" href="./ressources/css/header-improvements.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- JavaScript para comportamento do header -->
    <script src="./ressources/js/header-scroll.js"></script>
    <script src="./ressources/js/side-bar.js"></script>
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <section class="gerenciarprodutos">
        <!-- ===== FORMULÁRIO DE CADASTRO ===== -->
        <div class="form-produto">
            <div class="form-container-produto">
                <form action="../Backend/produtos.php" method="post" id="addProductForm" novalidate>
                    <h3>Cadastro do Produto</h3>

                    <input type="hidden" name="acao" value="adicionar">
                    <input type="hidden" name="aba_atual" value="<?= $aba_ativa ?>">

                    <div class="form-group">
                        <label for="productname">Nome do Produto</label>
                        <input type="text" name="productname" id="productname" required>
                        <span class="error-message" id="productname-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <input type="text" name="descricao" id="descricao" required>
                        <span class="error-message" id="descricao-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" required>
                            <option value="Selecione um status">Selecione um status</option>
                            <option value="Estoque">Estoque</option>
                            <option value="Manutenção">Manutenção</option>
                            <option value="Em uso">Em uso</option>
                        </select>
                        <span class="error-message" id="status-error"></span>
                    </div>

                    <div class="form-group">
                        <label for="quantidade">Quantidade</label>
                        <input type="number" name="quantidade" id="quantidade" min="0" required>
                        <span class="error-message" id="quantidade-error"></span>
                    </div>

                    <button type="submit" class="btn" id="submitBtn">
                        <span class="btn-text">Adicionar Produto</span>
                        <span class="btn-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Processando...
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <!-- ===== CONTAINER DA TABELA ===== -->
        <div class="table-container">
            <h3>Gerenciar Produtos</h3>

            <div class="tabela">
                <!-- ===== BOTÕES DE ABA ===== -->
                <div class="tab-buttons">
                    <button class="tab-button <?= $aba_ativa === 'tab1' ? 'active' : '' ?>" onclick="openTab('tab1')">
                        <i class="fas fa-box"></i>
                        <span>Estoque</span>
                    </button>

                    <button class="tab-button <?= $aba_ativa === 'tab2' ? 'active' : '' ?>" onclick="openTab('tab2')">
                        <i class="fas fa-tools"></i>
                        <span>Manutenção</span>
                    </button>

                    <button class="tab-button <?= $aba_ativa === 'tab3' ? 'active' : '' ?>" onclick="openTab('tab3')">
                        <i class="fas fa-check-circle"></i>
                        <span>Em uso</span>
                    </button>
                </div>

                <!-- ===== ABA ESTOQUE ===== -->
                <div id="tab1" class="tab-content <?= $aba_ativa === 'tab1' ? 'active' : '' ?>">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Status</th>
                                <th>Quantidade</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once("../Backend/conexao.php");

                            $stmt = $pdo->query("SELECT * FROM produtos WHERE status = 'Estoque'");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
                            ?>
                                <tr>
                                    <td data-label="ID"><?= $row['id_products'] ?></td>
                                    <td data-label="Nome"><?= htmlspecialchars($row['nome']) ?></td>
                                    <td data-label="Descrição"><?= htmlspecialchars($row['descricao']) ?></td>
                                    <td data-label="Status">
                                        <span class="status-badge status-estoque"><?= $row['status'] ?></span>
                                    </td>
                                    <td data-label="Quantidade"><?= $row['quantidade'] ?></td>
                                    <td data-label="Ações">
                                        <button  onclick='preencherForm(<?= json_encode($row) ?>)' title="Editar">
                                            <a href="gerenciarprodutosupdate.php?id=<?= $row['id_products'] ?>" class="btn-edit">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                        </button>

                                        <a href="../Backend/produtos.php?excluir=<?= $row['id_products'] ?>&aba=tab1"
                                            onclick="return confirm('Deseja excluir este produto?')"
                                            class="btn-delete" title="Excluir">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php
                            endwhile;
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- ===== ABA MANUTENÇÃO ===== -->
                <div id="tab2" class="tab-content <?= $aba_ativa === 'tab2' ? 'active' : '' ?>">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Status</th>
                                <th>Quantidade</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once("../Backend/conexao.php");

                            $stmt = $pdo->query("SELECT * FROM produtos WHERE status = 'Manutenção'");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
                            ?>
                                <tr>
                                    <td data-label="ID"><?= $row['id_products'] ?></td>
                                    <td data-label="Nome"><?= htmlspecialchars($row['nome']) ?></td>
                                    <td data-label="Descrição"><?= htmlspecialchars($row['descricao']) ?></td>
                                    <td data-label="Status">
                                        <span class="status-badge status-manutencao"><?= $row['status'] ?></span>
                                    </td>
                                    <td data-label="Quantidade"><?= $row['quantidade'] ?></td>
                                    <td data-label="Ações">
                                        <button  onclick='preencherForm(<?= json_encode($row) ?>)' title="Editar">
                                            <a href="gerenciarprodutosupdate.php?id=<?= $row['id_products'] ?>" class="btn-edit">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                        </button>

                                        <a href="../Backend/produtos.php?excluir=<?= $row['id_products'] ?>&aba=tab2"
                                            onclick="return confirm('Deseja excluir este produto?')"
                                            class="btn-delete" title="Excluir">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php
                            endwhile;
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- ===== ABA EM USO ===== -->
                <div id="tab3" class="tab-content <?= $aba_ativa === 'tab3' ? 'active' : '' ?>">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Status</th>
                                <th>Quantidade</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require_once("../Backend/conexao.php");

                            $stmt = $pdo->query("SELECT * FROM produtos WHERE status = 'Em uso'");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
                            ?>
                                <tr>
                                    <td data-label="ID"><?= $row['id_products'] ?></td>
                                    <td data-label="Nome"><?= htmlspecialchars($row['nome']) ?></td>
                                    <td data-label="Descrição"><?= htmlspecialchars($row['descricao']) ?></td>
                                    <td data-label="Status">
                                        <span class="status-badge status-em-uso"><?= $row['status'] ?></span>
                                    </td>
                                    <td data-label="Quantidade"><?= $row['quantidade'] ?></td>
                                    <td data-label="Ações">
                                        <button onclick='preencherForm(<?= json_encode($row) ?>)' title="Editar">
                                            <a href="gerenciarprodutosupdate.php?id=<?= $row['id_products'] ?>" class="btn-edit">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                        </button>

                                        <a href="../Backend/produtos.php?excluir=<?= $row['id_products'] ?>&aba=tab3"
                                            onclick="return confirm('Deseja excluir este produto?')"
                                            class="btn-delete" title="Excluir">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php
                            endwhile;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
    <script src="./ressources/js/script.js"></script>

    <?php include 'includes/alerts.php'; ?>

    <script>
        // ===== FUNÇÃO PARA ABRIR ABAS =====
        function openTab(tabId) {
            // Esconde todas as tabelas
            var tabContents = document.getElementsByClassName('tab-content');
            for (var i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove('active');
            }

            // Remove a classe 'active' de todos os botões
            var tabButtons = document.getElementsByClassName('tab-button');
            for (var i = 0; i < tabButtons.length; i++) {
                tabButtons[i].classList.remove('active');
            }

            // Mostra a tabela selecionada e marca o botão como ativo
            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        // ===== FUNÇÃO PARA PREENCHER FORMULÁRIO =====
        function preencherForm(produto) {
            const form = document.querySelector('form');
            form.action = "../Backend/produtos.php";
            form.querySelector('input[name="acao"]').value = 'editar';
            form.querySelector('input[name="productname"]').value = produto.nome;
            form.querySelector('input[name="descricao"]').value = produto.descricao;
            form.querySelector('select[name="status"]').value = produto.status;
            form.querySelector('input[name="quantidade"]').value = produto.quantidade;

            let inputId = document.querySelector('input[name="id"]');
            if (!inputId) {
                inputId = document.createElement('input');
                inputId.type = "hidden";
                inputId.name = "id";
                form.appendChild(inputId);
            }
            inputId.value = produto.id_products;

            // Scroll para o formulário
            document.querySelector('.form-produto').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        // ===== VALIDAÇÃO DE FORMULÁRIO =====
        document.getElementById('addProductForm').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                const errorElement = document.getElementById(field.id + '-error');
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                    if (errorElement) {
                        errorElement.textContent = 'Este campo é obrigatório';
                    }
                } else {
                    field.classList.remove('error');
                    if (errorElement) {
                        errorElement.textContent = '';
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
                if (window.SmartStock) {
                    window.SmartStock.showAlert('Por favor, preencha todos os campos obrigatórios.', 'error');
                }
            }
        });
    </script>
</body>

</html>