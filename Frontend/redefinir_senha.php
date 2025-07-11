<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock - Redefinir Senha</title>
    <link rel="stylesheet" href="./ressources/css/style.css">
    <link rel="stylesheet" href="./ressources/css/header.css">
    <link rel="stylesheet" href="./ressources/css/updateperfil.css">
    <link rel="stylesheet" href="./ressources/css/media.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <?php include __DIR__ . '/includes/header.php'; ?>

    <?php
    $token = $_GET['token'] ?? '';

    if (empty($token)) {
        echo "<p style='text-align: center; color: red;'>Token inválido ou ausente.</p>";
        exit;
    }
    ?>

    <section class="updateperfil">

        <div class="logo">
            <img src="./ressources/img/smartstock.png" alt="Logo SmartStock">
        </div>

        <div class="form-update">
            <div class="form-container-update">

                <form action="../Backend/atualizar_senha.php" method="post">
                    <h3>Atualize sua senha</h3>

                    <!-- TOKEN HIDDEN -->
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                    <!-- E-MAIL APENAS PARA DISPLAY (opcional) -->
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="" placeholder="email@email.com" disabled>

                    <!-- SENHA NOVA -->
                    <label for="senha">Nova Senha</label>
                    <input type="password" name="senha" id="senha" placeholder="•••••••••" required>

                    <!-- CONFIRMAR SENHA -->
                    <label for="csenha">Confirmar senha</label>
                    <input type="password" name="csenha" id="csenha" placeholder="•••••••••" required>

                    <!-- BOTÃO -->
                    <input type="submit" value="Enviar" class="btn">
                </form>

            </div>
        </div>

    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script src="./ressources/js/script.js"></script>

</body>

</html>