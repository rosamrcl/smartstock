<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="./ressources/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
        <?php
        include __DIR__ . '/includes/headerog.php';
        ?>

    <section class="esqueci ">
        <div class="form-update">
            <div class="form-container-update">
                <form action="../Backend/solicitar_redefinicao.php" method="post">
                    <h3>Esqueci minha senha</h3>
                    <label for="email">Digite seu e-mail</label>
                    <input type="email" name="email" placeholder="seu@email.com" required>
                    <input type="submit" value="Enviar link de redefinição" class="btn">
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