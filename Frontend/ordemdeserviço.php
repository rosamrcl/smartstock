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
                                    <tr>
                                        <td>#<?= $chamado['id_suport'] ?></td>
                                        <td><?= htmlspecialchars($chamado['nome']) ?></td>
                                        <td><?= htmlspecialchars($chamado['mensagem']) ?></td>
                                        <td>
                                            <?= $chamado['arquivo'] ? '<a href="../Frontend/uploads/' . $chamado['arquivo'] . '" target="_blank">Ver Anexo</a>' : 'Sem arquivo' ?>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($chamado['data_envio'])) ?></td>
                                        <td><a href="#" class="btn"><i class="fa-solid fa-check"></i></a></td>
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
                            <tr>
                                <td>Info A2</td>
                                <td>Info B2</td>
                                <td>Info B2</td>
                                <td>Info B2</td>
                                <td>Info B2</td>

                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="tab3" class="tab-content">
                    <table>
                        <thead>
                            <tr>
                                <th>Manutenção</th>
                                <th>Status</th>
                                <th>Estapa</th>

                                <th>Descrição da tarefa</th>
                                <th>Data da verificação</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="" id="">
                                        <option value="">Preventiva</option>
                                        <option value="">Corretiva</option>
                                        <option value="">Instalação</option>
                                        <option value="">Configuração</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="" id="">
                                        <option value="">Pendente</option>
                                        <option value="">Em andamento</option>
                                        <option value="">Concluído</option>
                                    </select>
                                </td>
                                <td><select name="" id="">
                                        <option value="">
                                            Verificar conectividade
                                        </option>
                                        <option value="">
                                            Testar alimentação alétrica
                                        </option>
                                    </select></td>
                                <td><input type="file" name="" id=""></td>
                                <td><input type="date" name="" id=""></td>
                                <td><a href="#" class="btn"><i class="fa-solid fa-check"></i></a></td>
                            </tr>

                        </tbody>
                    </table>
                </div>

    </section>
    <?php
    include __DIR__ . '/includes/footer.php';
    ?>

    <script src="./ressources/js/script.js"></script>

</body>

</html>