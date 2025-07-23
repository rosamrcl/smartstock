<?php
session_start();
require_once("/laragon/www/smartstock/Backend/conexao.php");

$id_user = $_SESSION['id_user'];

$diretorioUploads = __DIR__ . "/../Frontend/uploads";
if (!file_exists($diretorioUploads)) {
    mkdir($diretorioUploads, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmtOld = $pdo->prepare("SELECT nome, sobrenome, email FROM usuarios WHERE id_user = :id");
    $stmtOld->bindParam(':id', $id_user);
    $stmtOld->execute();
    $dadosAntigos = $stmtOld->fetch(PDO::FETCH_ASSOC);

    $nome = !empty($_POST['nome']) ? $_POST['nome'] : $dadosAntigos['nome'];
    $sobrenome = !empty($_POST['sobrenome']) ? $_POST['sobrenome'] : $dadosAntigos['sobrenome'];
    $email = !empty($_POST['email']) ? $_POST['email'] : $dadosAntigos['email'];
    $senha = $_POST['senha'];
    $csenha = $_POST['csenha'];

    if (!empty($senha) && $senha !== $csenha) {
    $warning_msg[] ="As senhas não coincidem.";
    }

    $verificaEmail = $pdo->prepare("SELECT id_user FROM usuarios WHERE email = :email AND id_user != :id_user");
    $verificaEmail->execute([':email' => $email, ':id_user' => $id_user]);
    if ($verificaEmail->rowCount() > 0) {
        $warning_msg[] = "E-mail já está sendo usado por outro usuário.";
    }

    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nomeFoto = uniqid() . '.' . $ext;
        $caminho = $diretorioUploads . "/" . $nomeFoto;

        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        if (!in_array($_FILES['foto']['type'], $tiposPermitidos)) {
            $warning_msg[] ="Tipo de imagem inválido.";
        }

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho)) {
            $foto = $nomeFoto;
        }
    }


    $query = "UPDATE usuarios SET nome = :nome, sobrenome = :sobrenome, email = :email";

    if (!empty($senha)) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $query .= ", senha = :senha";
    }

    if ($foto) {
        $query .= ", foto = :foto";
    }

    $query .= ", updated_at = NOW() WHERE id_user = :id_user";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':sobrenome', $sobrenome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id_user', $id_user);

    if (!empty($senha)) {
        $stmt->bindParam(':senha', $senhaHash);
    }

    if ($foto) {
        $stmt->bindParam(':foto', $foto);
    }

    if ($stmt->execute()) {
        $stmtUser = $pdo->prepare("SELECT nome, sobrenome, foto FROM usuarios WHERE id_user = :id");
        $stmtUser->bindParam(':id', $id_user);
        $stmtUser->execute();
        $usuarioAtualizado = $stmtUser->fetch(PDO::FETCH_ASSOC);

        $_SESSION['nome'] = $usuarioAtualizado['nome'];
        $_SESSION['sobrenome'] = $usuarioAtualizado['sobrenome'];
        $_SESSION['foto'] = $usuarioAtualizado['foto'];

        header("Location: ../Frontend/home.php");
        exit;
    } else {
        $info_msg[] = 'Erro ao atualizar o perfil.';
    }
}
?>