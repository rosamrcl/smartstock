<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock</title>
    <link rel="stylesheet" href="./ressources/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>


    <body>
        <?php
        include __DIR__ . '/includes/headerog.php';
        ?>
        <section class="container">

            <div class="logo">
                <img src="./ressources/img/smartstock.png" alt="">
            </div>

            <div class="form">
                <div class="form-container">
                    <form action="../Backend/login.php" method="post">
                        <h3>Login</h3>
                        <label for="email">E-mail</label>
                        <input type="email" name="email"  placeholder="email@email.com" required>
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" placeholder="•••••••••" required>
                        <p>Esqueci minha senha <a href="esqueci_senha.php">Clique aqui</a></p>
                        <p>Ainda não tem cadastro? <a href="cadastro.php">Clique aqui</a></p>
                        <input type="submit" value="Enviar" class="btn">
                    </form>
                </div>
            </div>


        </section>
        <?php
        include __DIR__ . '/includes/footer.php';
        ?>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
        <script src="./ressources/js/script.js"></script>
        <?php
        include __DIR__ . './includes/alerts.php';
        ?>

    </body>

</html>