<?php

require_once("../Backend/conexao.php");
include '../Backend/alerts.php';

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock</title>
    <link rel="stylesheet" href="./ressources/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <?php
    include __DIR__ . '/includes/header.php';
    ?>

    <section class="gerenciarprodutos">

        <div class="form-produto">

            <div class="form-container-produto">

                <form action="../Backend/produtos.php" method="post">

                    <h3>Cadastro do produto</h3>

                    <input type="hidden" name="acao" value="adicionar">

                    <label for="productname">Nome produto</label>
                    <input type="text" name="productname" id="">

                    <label for="descricao">Descrição</label>
                    <input type="text" name="descricao">

                    <label for="status">Status</label>

                    <select name="status" id="">
                        <option value="Vazio">----</option>
                        <option value="Estoque">Estoque</option>
                        <option value="Manutenção">Manutenção</option>
                        <option value="Em uso">Em uso</option>
                    </select>

                    <label for="quantidade">Quantidade</label>
                    <input type="number" name="quantidade" id="">

                    <input type="submit" value="Adicionar" class="btn">

                </form>

            </div>

        </div>

        <div class="table-container">

            <h3>Gerenciar Produtos</h3>

            <div class="tabela">

                <div class="tab-buttons">

                    <button class="tab-button active" onclick="openTab('tab1')">Estoque</button>

                    <button class="tab-button" onclick="openTab('tab2')">Manutenção</button>

                    <button class="tab-button" onclick="openTab('tab3')">Em uso</button>

                </div>

                <div id="tab1" class="tab-content active">

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

                            <?php

                            require_once("../Backend/conexao.php");
                            
                            $stmt = $pdo->query("SELECT * FROM produtos WHERE status = 'Estoque'");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :

                            ?>
                                <tr>

                                    <td data-label="Nome"><?= $row['id_products'] ?></td>
                                    <td data-label="Descrição"><?= htmlspecialchars($row['nome']) ?></td>
                                    <td><?= htmlspecialchars($row['descricao']) ?></td>
                                    <td data-label="Status"><?= $row['status'] ?></td>
                                    <td data-label="Quantidade"><?= $row['quantidade'] ?></td>
                                    <td>

                                        <button class="btn-edit btn" onclick='preencherForm(<?= json_encode($row) ?>)'>

                                            <a href="gerenciarprodutosupdate.php?id=<?= $row['id_products'] ?>" class="btn-edit" style="color: white;">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                            
                                        </button>

                                        <a href="../Backend/produtos.php?excluir=<?= $row['id_products'] ?>" onclick="return confirm('Deseja excluir?')" class="btn-delete">
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

                            <?php
                            require_once("../Backend/conexao.php");
                            
                            $stmt = $pdo->query("SELECT * FROM produtos WHERE status = 'Manutenção'");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
                            ?>

                                <tr>

                                    <td data-label="Nome"><?= $row['id_products'] ?></td>
                                    <td><?= htmlspecialchars($row['nome']) ?></td>
                                    <td data-label="Descrição"><?= htmlspecialchars($row['descricao']) ?></td>
                                    <td data-label="Status"><?= $row['status'] ?></td>
                                    <td data-label="quantidade"><?= $row['quantidade'] ?></td>
                                    <td>

                                        <button class="btn-edit btn" onclick='preencherForm(<?= json_encode($row) ?>)'>

                                            <a href="gerenciarprodutosupdate.php?id=<?= $row['id_products'] ?>" class="btn-edit" style="color: white;">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>

                                        </button>

                                        <a href="../Backend/produtos.php?excluir=<?= $row['id_products'] ?>" onclick="return confirm('Deseja excluir?')" class="btn-delete">
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

                <div id="tab3" class="tab-content">

                    <table>

                        <thead>

                            <tr>

                                <th>ID</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Status</th>
                                <th>Quantidade</th>
                                <th>Quantidade</th>
                                
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
                                    <td data-label="Status"><?= $row['status'] ?></td>
                                    <td data-label="Quantidade"><?= $row['quantidade'] ?></td>
                                    <td>

                                        <button class="btn-edit btn" onclick='preencherForm(<?= json_encode($row) ?>)'>

                                            <a href="gerenciarprodutosupdate.php?id=<?= $row['id_products'] ?>" class="btn-edit" style="color: white;">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>

                                        </button>

                                        <a href="../Backend/produtos.php?excluir=<?= $row['id_products'] ?>" onclick="return confirm('Deseja excluir?')" class="btn-delete">
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

    </section>

    <?php
    include __DIR__ . '/includes/footer.php';
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js">
    </script>
    <script src="./ressources/js/script.js"></script>

</body>

</html>