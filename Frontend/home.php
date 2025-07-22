<?php

session_start();

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>

    <?php
    include __DIR__ . '/includes/header.php';
    ?>

    <section class="usuario">

        <div class="logo_foto">

            <img src="./ressources/img/smartstock.png">

            <div class="perfil">

                <div class="image">

                    <img src="<?= htmlspecialchars($fotoPerfil) ?>" alt="Foto de Perfil">

                </div>

                <p class="welcome">Bem Vindo, <strong><?= $_SESSION['nome'] ?> <?= $_SESSION['sobrenome'] ?></strong>.</p>

                <div>

                    <a class="btn" href="updateperfil.php">Editar <i class="fa-solid fa-pencil"></i></a>

                    <?php if (isset($_SESSION['id_user'])): ?>

                        <form action="../Backend/logout.php" method="post">

                            <button type="submit" class="btn-delete">Sair <i class="fa-solid fa-right-to-bracket"></i></button>

                        </form>

                    <?php endif; ?>

                </div>

            </div>

        </div>

        <div class="service">

            <div class="gerenciar">

                <h5>Gerenciar Produtos</h5>

                <a href="gerenciarprodutos.php"> <i class="fa-solid fa-boxes-stacked"></i> </a>

            </div>

            <div class="ordem-servico">

                <h5>Ordem de Serviço</h5>
                <a href="ordemdeserviço.php"><i class="fa-solid fa-users-gear"></i></a>

            </div>

            <div class="manutencao">

                <h5>Manutenção</h5>
                <a href="gerenciarprodutos.php"><i class="fa-solid fa-screwdriver-wrench"></i></a>

            </div>

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