<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock</title>
    <link rel="stylesheet" href="./ressources/css/style.css">
    <link rel="stylesheet" href="./ressources/css/header.css">
    <link rel="stylesheet" href="./ressources/css/form.css">
    <link rel="stylesheet" href="./ressources/css/cadastrogerencia.css">
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
        <div class="form-produto">
            <div class="form-container-produto">
                <form action="" method="post">
                    <h3>Atualize o produto</h3>
                    <label for="productname">Nome produto</label>
                    <input type="text" name="productname" id="">
                    <label for="descricao">Descrição</label>
                    <input type="text" name="descricao">
                    <label for="status">Status</label>
                    <select name="status" id="">
                        <option value="funcionando">Funcionando</option>
                        <option value="funcionando">Com defeito</option>
                        <option value="funcionando">Em uso</option>
                    </select>
                    <label for="quantidade">Quantidade</label>
                    <input type="number" name="quantidade" id="">
                    <input type="submit" value="Adicionar" class="btn" name="adicionar">
                </form>
            </div>
        </div>       

    </section>

    <script src="./ressources/js/script.js"></script>

</body>

</html>