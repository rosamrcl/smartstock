<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="./ressources/css/style.css">
</head>
<body>

    <section class="updateperfil">
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

</body>
</html>
