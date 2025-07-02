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

    <section class="cadastrogerencia">
        <div class="logo">
            <img src="./ressources/img/smartstock.png" alt="">
        </div>
        <div class="form">
            <div class="form-container">
                <form action="../Backend/cadastro.php" method="post">
                    <h3>Atualize seu perfil</h3>
                    <div class="foto-update-perfil">
                        <img src="./ressources/img/perfil.png" alt="">            
                    </div>
                    <input type="file" name="" id="">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" placeholder="Nome">
                    <label for="sobrenome">Sobrenome</label>
                    <input type="text" name="sobrenome" placeholder="Sobrenome">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="" placeholder="email@email.com">
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="" placeholder="•••••••••">
                    <label for="csenha">Confirmar senha</label>
                    <input type="password" name="csenha" id="" placeholder="•••••••••">                                 
                    <input type="submit" value="Enviar" class="btn">

                    <!-- Exibir erro ou sucesso -->
                    <?php
                    session_start();
                    if (!empty($_SESSION['erro_cadastro'])) {
                        echo "<p style='color:var(--cor4); text-align:center; font-size:3rem;'>" . $_SESSION['erro_cadastro'] . "</p>";
                        unset($_SESSION['erro_cadastro']);
                    }
                    if (!empty($_SESSION['sucesso_cadastro'])) {
                        echo "<p style='color:var(--cor4); text-align:center; font-size:3rem;'>" . $_SESSION['sucesso_cadastro'] . "</p>";
                        unset($_SESSION['sucesso_cadastro']);
                    }
                    ?>

                </form>
            </div>
        </div> 

    </section>
    <?php
    include __DIR__ . '/includes/footer.php';
    ?>

    <script src="./ressources/js/script.js"></script>

</body>

</html>