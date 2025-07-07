<?php

session_start();

?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock</title>
    <link rel="stylesheet" href="./ressources/css/style.css">
    <link rel="stylesheet" href="./ressources/css/header.css">
    <link rel="stylesheet" href="./ressources/css/updateperfil.css">
    <link rel="stylesheet" href="./ressources/css/media.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>

    <?php
    include __DIR__ . '/includes/header.php';
    ?>

    <section class="updateperfil">

        <div class="logo">

            <img src="./ressources/img/smartstock.png" alt="">

        </div>

        <div class="form-update">

            <div class="form-container-update">

                <form action="../Backend/atualizar.php" method="post" enctype="multipart/form-data">

                    <h3>Atualize seu perfil</h3>

                    <div class="foto-update-perfil">

                        <img src="./uploads/<?php echo $_SESSION['foto'] ?? 'perfil.png'; ?>" alt="Foto de Perfil">

                        <input type="file" name="foto">

                    </div>

                    <label for="nome">Nome</label>
                    <input type="text" name="nome" placeholder="Nome" value="<?php echo $usuario['nome'] ?? ''; ?>">

                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" name="sobrenome" placeholder="Sobrenome" value="<?php echo $usuario['sobrenome'] ?? ''; ?>">

                    <label for="email">E-mail</label>
                    <input type="email" name="email" placeholder="email@email.com" value="<?php echo $usuario['email'] ?? ''; ?>">

                    <label for="senha">Senha</label>
                    <input type="password" name="senha" placeholder="•••••••••">

                    <label for="csenha">Confirmar senha</label>
                    <input type="password" name="csenha" placeholder="•••••••••">

                    <input type="submit" value="Enviar" class="btn">

                </form>

            </div>

    </section>
    
    <?php
    include __DIR__ . '/includes/footer.php';
    ?>

    <script src="./ressources/js/script.js"></script>

</body>

</html>