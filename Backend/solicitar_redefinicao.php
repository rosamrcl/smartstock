<?php
session_start();
require_once "conexao.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    // Validação de campos obrigatórios
    if (empty($email)) {
        $_SESSION['error_msg'] = ["Por favor, insira seu email."];
        header('Location: ../Frontend/esqueci_senha.php');
        exit;
    }

    // Validação de formato de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_msg'] = ["Por favor, insira um email válido."];
        header('Location: ../Frontend/esqueci_senha.php');
        exit;
    }

    try {
        // Verifica se o e-mail existe no banco
        $stmt = $pdo->prepare("SELECT id_user, nome FROM usuarios WHERE email = ? AND deleted_at IS NULL");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Remove tokens antigos para este email
            $stmt = $pdo->prepare("DELETE FROM redefinicao_senha WHERE email = ?");
            $stmt->execute([$email]);

            // Gera novo token
            $token = bin2hex(random_bytes(32));
            $expira_em = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expira em 1 hora

            // Armazena token na tabela
            $stmt = $pdo->prepare("INSERT INTO redefinicao_senha (email, token, expira_em) VALUES (?, ?, ?)");
            $stmt->execute([$email, $token, $expira_em]);

            // Link de redefinição
            $url = "http://localhost/smartstock/Frontend/redefinir_senha.php?token=$token";

            $_SESSION['success_msg'] = [
                "Link de redefinição enviado com sucesso!",
                "Verifique seu email ou clique no link abaixo:",
                "<a href='$url' target='_blank'>$url</a>",
                "O link expira em 1 hora."
            ];
            
        } else {
            $_SESSION['error_msg'] = ["Email não encontrado. Verifique se o email está correto ou faça um cadastro."];
        }
        
    } catch (PDOException $e) {
        $_SESSION['error_msg'] = ["Erro interno do sistema. Tente novamente mais tarde."];
    }
    
    header('Location: ../Frontend/esqueci_senha.php');
    exit;
    
} else {
    // Método não permitido
    header('Location: ../Frontend/esqueci_senha.php');
    exit;
}
?>