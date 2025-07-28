<?php

require_once __DIR__ . '/../Backend/conexao.php'; // ajuste o caminho conforme seu projeto


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $mensagem = $_POST['mensagem'] ?? '';
    $arquivo_nome = null;

    if (!empty($nome) && !empty($email) && !empty($mensagem)) {

        // Lidar com o arquivo se existir
        if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {

            $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'pdf'];
            $info = pathinfo($_FILES['arquivo']['name']);
            $extensao = strtolower($info['extension']);

            if (in_array($extensao, $extensoes_permitidas)) {

                $novo_nome = uniqid('arquivo_') . '.' . $extensao;
                $caminho_destino = __DIR__ . '/uploads/' . $novo_nome;

                if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminho_destino)) {

                    $arquivo_nome = $novo_nome;
                }
            }
        }

        // Inserir no banco
        $stmt = $pdo->prepare("INSERT INTO suporte (nome, email, mensagem, arquivo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $mensagem, $arquivo_nome]);

        echo "<script>alert('Mensagem enviada com sucesso!');</script>";
    } else {

        echo "<script>alert('Por favor, preencha todos os campos obrigatórios.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartStock</title>
    <link rel="stylesheet" href="./ressources/css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <?php
    include __DIR__ . '/includes/header.php';
    ?>
    <section class="suporte">
        <div class="heading">
            <h1> Como podemos ajudar você?</h1>
        </div>

        <div class="container">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="input-group">
                    <input name="nome" type="text" placeholder="Digite seu nome">
                    <input name="email" type="email" placeholder="Digite seu email">
                </div>
                <textarea name="mensagem" placeholder="Digite seu problema"></textarea>
                
                <input type="file" id="arquivo" name="arquivo" accept=".jpg,.jpeg,.png,.pdf">

                <input type="submit" class="btn" value="Enviar">
            </form>
        </div>
    </section>
    <?php
    include __DIR__ . '/includes/footer.php';
    ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
<script src="/Frontend/ressources/js/script.js"></script>
<?php
include __DIR__ . './includes/alerts.php';
?>

</html>