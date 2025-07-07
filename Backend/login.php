<?php

session_start();

include __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (empty($email) || empty($senha)) {

        $_SESSION['erro_login'] = "Preencha todos os campos.";
        header('Location: ../Frontend/login.php');
        exit;

    }

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['sobrenome'] = $user['sobrenome'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['foto'] = !empty($usuario['foto']) ? $usuario['foto'] : 'perfil.png';

        header('Location: ../Frontend/home.php');
        exit;

    } else {

        $_SESSION['erro_login'] = "Usuário ou senha inválidos.";
        header('Location: ../Frontend/login.php');
        exit;

    }

} else {

    header('Location: ../Frontend/login.php');
    exit;

}

?>