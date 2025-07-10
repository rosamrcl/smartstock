<?php
session_start();
include '../Backend/painel.php';


if (!isset($_SESSION['id_user'])) {
    header('Location: ../Frontend/login.php');
    exit;
}

require_once('../Backend/conexao.php');

$id = $_SESSION['id_user'];

$stmt = $pdo->prepare("SELECT foto FROM usuarios WHERE id_user = ?");
$stmt->execute([$id]);
$dados = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se tem foto cadastrada
if (!empty($dados['foto'])) {
    $fotoPerfil = './uploads/' . $dados['foto']; // imagem enviada pelo usuário
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
    <title>SmartStock</title>
    <link rel="stylesheet" href="./ressources/css/style.css">
    <link rel="stylesheet" href="./ressources/css/header.css">
    <link rel="stylesheet" href="./ressources/css/form.css">
    <link rel="stylesheet" href="./ressources/css/home.css">
    <link rel="stylesheet" href="./ressources/css/media.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php
    include __DIR__ . '/includes/header.php';
    ?>



    <section class="gerenciarprodutos">

        <div class="perfil-cadastro">
            <div class="image">
                <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de Perfil">
            </div>
            <p class="welcome">Bem Vindo, <strong><?= $_SESSION['nome'] ?> <?= $_SESSION['sobrenome'] ?></strong>.</p>
            <button type="submit" class="btn-delete">Sair <i class="fa-solid fa-right-to-bracket"></i></button>
        </div>
        <div class="table-container">
            <h3>Área do funcionário</h3>
            <div class="tabela">
                <div class="tab-buttons">
                    <button class="tab-button active" onclick="openTab('tab1')">Lista de Chamados</button>
                    <button class="tab-button" onclick="openTab('tab2')">Estoque</button>
                    <button class="tab-button" onclick="openTab('tab3')">Check-List</button>
                </div>
                <div id="tab1" class="tab-content active">
                    <table>
                        <thead>
                            <tr>
                                <th>OS</th>
                                <th>Nome</th>
                                <th>Mensagem</th>
                                <th>Anexo</th>
                                <th>Data</th>
                                <th>Ação</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (count($chamados) > 0): ?>
                                <?php foreach ($chamados as $chamado): ?>
                                    <tr class="<?= $chamado['status_sup'] === 'concluido' ? 'tr-concluida' : '' ?>">
                                        <td>#<?= $chamado['id_suport'] ?></td>
                                        <td><?= htmlspecialchars($chamado['nome']) ?></td>
                                        <td><?= htmlspecialchars($chamado['mensagem']) ?></td>
                                        <td>
                                            <?= $chamado['arquivo'] ? '<a href="../Frontend/uploads/' . $chamado['arquivo'] . '" target="_blank">Ver Anexo</a>' : 'Sem arquivo' ?>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($chamado['data_envio'])) ?></td>
                                        <td>
                                            <?php if ($chamado['status_sup'] === 'concluido'): ?>
                                                <button class="btn" disabled><i class="fa-solid fa-check-double"></i></button>
                                            <?php else: ?>
                                                <button class="btn concluir-btn"
                                                    onclick="marcarConcluido(this, <?= $chamado['id_suport'] ?>)">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">Nenhum chamado registrado.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>


                    </table>
                </div>

                <div id="tab2" class="tab-content">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Status</th>
                                <th>Quantidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($produtosEstoque) > 0): ?>
                                <?php foreach ($produtosEstoque as $produto): ?>
                                    <tr>
                                        <td>#<?= $produto['id_products'] ?></td>
                                        <td><?= htmlspecialchars($produto['nome']) ?></td>
                                        <td><?= htmlspecialchars($produto['descricao']) ?></td>
                                        <td><?= $produto['status'] ?></td>
                                        <td><?= $produto['quantidade'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">Nenhum produto em estoque.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>


                <div id="tab3" class="tab-content">
                    <table>
                        <thead>
                            <tr>
                                <th>Etapas realizadas</th>
                                <th>Cliente</th>
                                <th>Local</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <form action="../Backend/adicionar_checklist.php" method="post">
                                <td>
                                        <label><input type="checkbox" name="etapas[]" value="Verificar conectividade">
                                            Verificar conectividade</label><br>
                                        <label><input type="checkbox" name="etapas[]"
                                                value="Testar alimentação elétrica"> Testar alimentação
                                            elétrica</label><br>
                                        <label><input type="checkbox" name="etapas[]" value="Atualizar firmware">
                                            Atualizar firmware</label><br>
                                        <label><input type="checkbox" name="etapas[]" value="Checar sinal da rede">
                                            Checar sinal da rede</label><br>
                                </td>
                                <td><input type="text" name="cliente" placeholder="Nome do cliente" required></td>
                                <td><input type="text" name="local" placeholder="Local do serviço" required></td>
                                <td><button type="submit" class="btn"><i class="fa-solid fa-check"></i></button></td>
                                </form>
                            </tr>
                        </tbody>
                    </table>
                    <div class="relatorio">
                        <h4>Registros de Check-lists</h4>
                        <?php
                        $stmtChecks = $pdo->query("SELECT * FROM checklist ORDER BY data_execucao DESC");
                        $checklists = $stmtChecks->fetchAll(PDO::FETCH_ASSOC);
                        ?>

                        <!-- Exibir checklists já cadastrados -->
                        <table>
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Local</th>
                                    <th>Etapas</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($checklists as $c): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($c['cliente']) ?></td>
                                        <td><?= htmlspecialchars($c['local_servico']) ?></td>
                                        <td>
                                            <ul>
                                                <?php foreach (json_decode($c['etapas']) as $etapa): ?>
                                                    <li><?= htmlspecialchars($etapa) ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($c['data_execucao'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                </div>



    </section>
    </main>
    <?php
    include __DIR__ . '/includes/footer.php';
    ?>

    <script src="./ressources/js/script.js"></script>

</body>

</html>