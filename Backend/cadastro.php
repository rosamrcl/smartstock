<?php

session_start();
include __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $sobrenome = trim($_POST['sobrenome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $codigo_superior = trim($_POST['codigo_superior'] ?? '');

    // Verifica se todos os campos foram preenchidos
    if (empty($nome) || empty($sobrenome) || empty($email) || empty($senha) || empty($codigo_superior)) {
        $error_msg[] = "Preencha todos os campos.";
        header('Location: ../Frontend/cadastro.php');
        exit;
    }

    // Verifica se o código do superior é exatamente '1010'
    if ($codigo_superior !== '1010') {
        $error_msg[] =  "Código do superior inválido.";
        header('Location: ../Frontend/cadastro.php');
        exit;
    }

    // Verifica se o e-mail já está em uso
    $stmt = $pdo->prepare("SELECT id_user FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->fetch()) {
        $_SESSION['erro_cadastro'] = "Este e-mail já está em uso.";
        header('Location: ../Frontend/cadastro.php');
        exit;
    }

    // Criptografa a senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Insere o usuário no banco
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, sobrenome, email, senha) VALUES (:nome, :sobrenome, :email, :senha)");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':sobrenome', $sobrenome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senhaHash);

    if ($stmt->execute()) {
        $sucess_msg[] =  "Usuário cadastrado com sucesso!";
        header('Location: ../Frontend/login.php');
        exit;
    } else {
        $error_msg[] =  "Erro ao cadastrar. Tente novamente.";
        header('Location: ../Frontend/cadastro.php');
        exit;
    }

} else {
    header('Location: ../Frontend/cadastro.php');
    exit;
}
?>