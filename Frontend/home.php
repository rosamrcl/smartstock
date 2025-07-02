<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header('Location: ../Frontend/login.php');
    exit;
}

$nome = $_SESSION['usuario']['nome'];
$sobrenome = $_SESSION['usuario']['sobrenome'];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock</title>
    <link rel="stylesheet" href="./ressources/css/style.css">
    <link rel="stylesheet" href="./ressources/css/header.css">
    <link rel="stylesheet" href="./ressources/css/home.css">
    <link rel="stylesheet" href="./ressources/css/media.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php
    include __DIR__ . '/includes/header.php';
    ?>
    <section class="usuario">
        <div class="logo_foto">
            <img src="./ressources/img/smartstock.png" alt="" style="height: 50%; width: 50%; margin-top: 13%;">
            <div class="perfil">
                <div class="image">
                    <img src="./ressources/img/perfil.png" alt="">
                </div>
                <p style="text-align: center;">Bem Vindo, <strong><?= $nome; ?> <?= $sobrenome; ?><strong>.</p>
                <?php if (isset($_SESSION['id_user'])): ?>
                    <form action="../Backend/logout.php" method="post">
                        <button type="submit" class="btn-delete">Sair <i class="fa-solid fa-right-to-bracket"></i></button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <div class="service">
            <div class="gerenciar">
                <h5>Gerenciar Produtos</h5>
                <a href="#"> <i class="fa-solid fa-boxes-stacked"></i> </a>

            </div>
            <div class="ordem-servico">
                <h5>Ordem de Serviço</h5>
                <a href="#"><i class="fa-solid fa-users-gear"></i></a>
            </div>
            <div class="manutencao">
                <h5>Manutenção</h5>
                <a href="#"><i class="fa-solid fa-screwdriver-wrench"></i></a>
            </div>
        </div>
    </section>
    <?php
    include __DIR__ . '/includes/footer.php';
    ?>


    <script src="./ressources/js/script.js"></script>

</body>

</html>