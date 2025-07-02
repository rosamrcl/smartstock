<?php

session_start();

include __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $sobrenome = trim($_POST['sobrenome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Verificar se todos os campos estão preenchidos
    if (empty($nome) || empty($sobrenome) || empty($email) || empty($senha)) {

        $_SESSION['erro_cadastro'] = "Preencha todos os campos.";
        header('Location: ../Frontend/cadastro.php');
        exit;

    }

    // Verificar se o e-mail já está cadastrado
    $stmt = $pdo->prepare("SELECT id_user FROM usuarios WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->fetch()) {

        $_SESSION['erro_cadastro'] = "Este e-mail já está em uso.";
        header('Location: ../Frontend/cadastro.php');
        exit;

    }

    // Criptografar a senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserir no banco de dados
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, sobrenome, email, senha) VALUES (:nome, :sobrenome, :email, :senha)");
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':sobrenome', $sobrenome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senhaHash);

    if ($stmt->execute()) {

        $_SESSION['sucesso_cadastro'] = "Usuário cadastrado com sucesso!";
        header('Location: ../Frontend/login.php');
        exit;

    } else {

        $_SESSION['erro_cadastro'] = "Erro ao cadastrar. Tente novamente.";
        header('Location: ../Frontend/cadastro.php');
        exit;

    }

} else {

    header('Location: ../Frontend/cadastro.php');
    exit;

}