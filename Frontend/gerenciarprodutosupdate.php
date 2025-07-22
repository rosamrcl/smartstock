<?php
require_once("../Backend/conexao.php");
include '../Backend/alerts.php';

// Verifica se veio um ID na URL
if (!isset($_GET['id'])) {
    header("Location: ../Frontend/gerenciarprodutos.php");
    exit;
}

$id = $_GET['id'];

// Busca o produto pelo ID
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id_products = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não encontrar o produto
if (!$produto) {
    echo "Produto não encontrado.";
    exit;
}

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['productname'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];
    $quantidade = $_POST['quantidade'];

    $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, status = ?, quantidade = ? WHERE id_products = ?");
    $stmt->execute([$nome, $descricao, $status, $quantidade, $id]);

    header("Location: ../Frontend/gerenciarprodutos.php");
    exit;
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
</head>

<body>
    <?php
    include __DIR__ . '/includes/header.php';
    ?>

    <section class="gerenciarprodutos">
        <div class="logo">
            <img src="./ressources/img/smartstock.png" alt="">
        </div>
        <div class="form-produto">
            <div class="form-container-produto">
                <form action="" method="post">
                    <h3>Atualize o produto</h3>
                    <label for="productname">Nome produto</label>
                    <input type="text" name="productname" id="" value="<?= htmlspecialchars($produto['nome']); ?>">
                    <label for="descricao">Descrição</label>
                    <input type="text" name="descricao" value="<?= htmlspecialchars($produto['descricao']); ?>">
                    <label for="status">Status</label>
                    <select name="status" id="">
                        <option value="Estoque">Estoque</option>
                        <option value="Manutenção">Manutenção</option>
                        <option value="Em uso">Em uso</option>
                    </select>
                    <label for="quantidade">Quantidade</label>
                    <input type="number" name="quantidade" id="" value="<?= htmlspecialchars($produto['quantidade']); ?>">
                    <input type="submit" value="Atualizar" class="btn" name="atualizar">
                </form>
            </div>
        </div>       

    </section>
    <?php
    include __DIR__ . '/includes/footer.php';
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js">
    </script>
    <script src="./ressources/js/script.js"></script>

</body>

</html>