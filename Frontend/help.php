<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock</title>
    <link rel="stylesheet" href="./ressources/css/style.css">
    <link rel="stylesheet" href="./ressources/css/header.css">
    <link rel="stylesheet" href="./ressources/css/help.css">
    <link rel="stylesheet" href="./ressources/css/media.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php
    include __DIR__ . '/includes/header.php';
    ?>
    <div class="flex">
        <div class="heading">
            <h1> Como podemos ajudar você?</h1>
        </div>

        <div class="container">
            <form action="" method="post">
                <div class="input-group">
                    <input type="text" placeholder="Digite seu nome">
                    <input type="email" placeholder="Digite seu email">
                </div>
                <textarea placeholder="Digite seu problema"></textarea>
                <div class="file-upload-group">
                    <label for="arquivo" class="custom-file-upload">
                        <i class="fa-solid fa-upload"></i> Enviar foto ou PDF
                    </label>
                    <input type="file" id="arquivo" name="arquivo" class="hidden-file-input">
                </div>
                <input type="submit" class="btn" value="Enviar">
            </form>
        </div>
    </div>
    <?php
    include __DIR__ . '/includes/footer.php';
    ?>
</body>
<script src="/Frontend/ressources/js/script.js"></script>

</html>