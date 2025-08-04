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

        try {
            // Iniciar transação
            $pdo->beginTransaction();

            // Inserir no banco - suporte
            $stmt = $pdo->prepare("INSERT INTO suporte (nome, email, mensagem, arquivo) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $email, $mensagem, $arquivo_nome]);
            
            // Obter o ID do suporte recém-criado
            $id_suporte = $pdo->lastInsertId();

            // Criar ordem de serviço automaticamente
            $stmt_os = $pdo->prepare("INSERT INTO ordens_servico (solicitante, categoria, observacoes, status, id_suporte_origem) VALUES (?, ?, ?, ?, ?)");
            $stmt_os->execute([$nome, 'Suporte Técnico', $mensagem, 'Pendente', $id_suporte]);

            // Commit da transação
            $pdo->commit();

            echo "<script>alert('Mensagem enviada com sucesso! Uma ordem de serviço foi criada automaticamente.');</script>";
        } catch (Exception $e) {
            // Rollback em caso de erro
            $pdo->rollBack();
            echo "<script>alert('Erro ao processar solicitação. Tente novamente.');</script>";
        }
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
    <title>SmartStock - Suporte</title>
    <?php include 'includes/head.php'; ?>
</head>

<body>
    <?php
    include __DIR__ . '/includes/header.php';
    ?>
    <section class="suporte">
        <div class="suporte-container">
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
        </div>
    </section>
    <?php
    include __DIR__ . '/includes/footer.php';
    ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.2/dist/sweetalert2.all.min.js"></script>
<script src="/Frontend/ressources/js/script.js"></script>
<?php
include 'includes/alerts.php';
?>

</html>