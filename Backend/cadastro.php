<?php

session_start();
include __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $sobrenome = trim($_POST['sobrenome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $csenha = $_POST['csenha'] ?? '';
    $codigo_superior = trim($_POST['codigo_superior'] ?? '');

    // Validação de campos obrigatórios
    if (empty($nome) || empty($sobrenome) || empty($email) || empty($senha) || empty($csenha) || empty($codigo_superior)) {
        $_SESSION['error_msg'] = ["Por favor, preencha todos os campos obrigatórios."];
        header('Location: ../Frontend/cadastro.php');
        exit;
    }

    // Validação de formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_msg'] = ["Por favor, insira um email válido."];
        header('Location: ../Frontend/cadastro.php');
        exit;
    }

    // Validação de senha (mesmas regras do alterar_senha.php)
    if (strlen($senha) < 8) {
        $_SESSION['error_msg'] = ["A senha deve ter pelo menos 8 caracteres."];
        header('Location: ../Frontend/cadastro.php');
        exit;
    }

    if (!preg_match('/[A-Z]/', $senha)) {
        $_SESSION['error_msg'] = ["A senha deve conter pelo menos 1 letra maiúscula."];
        header('Location: ../Frontend/cadastro.php');
        exit;
    }

    if (!preg_match('/[a-z]/', $senha)) {
        $_SESSION['error_msg'] = ["A senha deve conter pelo menos 1 letra minúscula."];
        header('Location: ../Frontend/cadastro.php');
        exit;
    }

    if (!preg_match('/[0-9]/', $senha)) {
        $_SESSION['error_msg'] = ["A senha deve conter pelo menos 1 número."];
        header('Location: ../Frontend/cadastro.php');
        exit;
    }

    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $senha)) {
        $_SESSION['error_msg'] = ["A senha deve conter pelo menos 1 símbolo (!@#$%^&*)."];
        header('Location: ../Frontend/cadastro.php');
        exit;
    }

    // Validação de confirmação de senha
    if ($senha !== $csenha) {
        $_SESSION['error_msg'] = ["As senhas não coincidem. Tente novamente."];
        header('Location: ../Frontend/cadastro.php');
        exit;
    }

    // Verifica se o código do superior é exatamente '1010'
    if ($codigo_superior !== '1010') {
        $_SESSION['error_msg'] = ["Código do superior inválido. Verifique o código e tente novamente."];
        header('Location: ../Frontend/cadastro.php');
        exit;
    }

    try {
        // Verifica se o e-mail já está em uso
        $stmt = $pdo->prepare("SELECT id_user FROM usuarios WHERE email = :email AND deleted_at IS NULL");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetch()) {
            $_SESSION['error_msg'] = ["Este e-mail já está em uso. Tente fazer login ou use outro email."];
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
            $_SESSION['success_msg'] = ["Usuário cadastrado com sucesso! Agora você pode fazer login."];
            header('Location: ../Frontend/login.php');
            exit;
        } else {
            $_SESSION['error_msg'] = ["Erro ao cadastrar. Tente novamente mais tarde."];
            header('Location: ../Frontend/cadastro.php');
            exit;
        }

    } catch (PDOException $e) {
        $_SESSION['error_msg'] = ["Erro interno do sistema. Tente novamente mais tarde."];
        header('Location: ../Frontend/cadastro.php');
        exit;
    }

} else {
    // Método não permitido
    header('Location: ../Frontend/cadastro.php');
    exit;
}
?>