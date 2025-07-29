<?php

session_start();

include __DIR__ . '/conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Validação de campos obrigatórios
    if (empty($email) || empty($senha)) {
        $_SESSION['error_msg'] = ["Por favor, preencha todos os campos obrigatórios."];
        header('Location: ../Frontend/login.php');
        exit;
    }

    // Validação de formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_msg'] = ["Por favor, insira um email válido."];
        header('Location: ../Frontend/login.php');
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND deleted_at IS NULL");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            // Email não existe
            $_SESSION['error_msg'] = ["Email não encontrado. Verifique se o email está correto ou faça um cadastro."];
            header('Location: ../Frontend/login.php');
            exit;
        }

        // Verificar se a senha está correta
        if (!password_verify($senha, $user['senha'])) {
            // Senha incorreta
            $_SESSION['error_msg'] = ["Senha incorreta. Tente novamente."];
            header('Location: ../Frontend/login.php');
            exit;
        }

        // Login bem-sucedido
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['sobrenome'] = $user['sobrenome'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['foto'] = !empty($user['foto_perfil']) ? $user['foto_perfil'] : 'perfil.png';

        // Mensagem de sucesso
        $_SESSION['success_msg'] = ["Login realizado com sucesso! Bem-vindo, " . $user['nome'] . "."];
        
        header('Location: ../Frontend/home.php');
        exit;

    } catch (PDOException $e) {
        // Erro de banco de dados
        $_SESSION['error_msg'] = ["Erro interno do sistema. Tente novamente mais tarde."];
        header('Location: ../Frontend/login.php');
        exit;
    }

} else {
    // Método não permitido
    header('Location: ../Frontend/login.php');
    exit;
}

?>