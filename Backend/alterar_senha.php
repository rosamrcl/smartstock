<?php
session_start();
require_once("conexao.php");

// Verificar se o usuário está logado
if (!isset($_SESSION['id_user'])) {
    $_SESSION['error_msg'] = ["Você precisa estar logado para alterar sua senha."];
    header('Location: ../Frontend/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_SESSION['id_user'];
    $senha_atual = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    // Validação de campos obrigatórios
    if (empty($senha_atual) || empty($nova_senha) || empty($confirmar_senha)) {
        $_SESSION['error_msg'] = ["Por favor, preencha todos os campos."];
        header('Location: ../Frontend/alterar_senha.php');
        exit;
    }

    try {
        // Buscar dados do usuário
        $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id_user = ? AND deleted_at IS NULL");
        $stmt->execute([$id_user]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            $_SESSION['error_msg'] = ["Usuário não encontrado."];
            header('Location: ../Frontend/alterar_senha.php');
            exit;
        }

        // Verificar senha atual
        if (!password_verify($senha_atual, $usuario['senha'])) {
            $_SESSION['error_msg'] = ["Senha atual incorreta. Tente novamente."];
            header('Location: ../Frontend/alterar_senha.php');
            exit;
        }

        // Validação de força da nova senha
        $requirements = validatePasswordStrength($nova_senha);
        if (!$requirements['valid']) {
            $_SESSION['error_msg'] = ["A nova senha não atende aos requisitos mínimos de segurança."];
            header('Location: ../Frontend/alterar_senha.php');
            exit;
        }

        // Verificar se a nova senha é diferente da atual
        if (password_verify($nova_senha, $usuario['senha'])) {
            $_SESSION['error_msg'] = ["A nova senha deve ser diferente da senha atual."];
            header('Location: ../Frontend/alterar_senha.php');
            exit;
        }

        // Verificar confirmação de senha
        if ($nova_senha !== $confirmar_senha) {
            $_SESSION['error_msg'] = ["As senhas não coincidem. Tente novamente."];
            header('Location: ../Frontend/alterar_senha.php');
            exit;
        }

        // Criptografar nova senha
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        // Atualizar senha no banco
        $stmt = $pdo->prepare("UPDATE usuarios SET senha = ?, updated_at = NOW() WHERE id_user = ?");
        $stmt->execute([$nova_senha_hash, $id_user]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success_msg'] = [
                "Senha alterada com sucesso!",
                "Sua nova senha foi salva com segurança."
            ];
            
            // Redirecionar para a página inicial
            header('Location: ../Frontend/home.php');
            exit;
        } else {
            $_SESSION['error_msg'] = ["Erro ao alterar a senha. Tente novamente."];
            header('Location: ../Frontend/alterar_senha.php');
            exit;
        }

    } catch (PDOException $e) {
        $_SESSION['error_msg'] = ["Erro interno do sistema. Tente novamente mais tarde."];
        header('Location: ../Frontend/alterar_senha.php');
        exit;
    }

} else {
    // Método não permitido
    header('Location: ../Frontend/alterar_senha.php');
    exit;
}

/**
 * Valida a força da senha
 * @param string $password
 * @return array
 */
function validatePasswordStrength($password) {
    $requirements = [
        'length' => strlen($password) >= 8,
        'uppercase' => preg_match('/[A-Z]/', $password),
        'lowercase' => preg_match('/[a-z]/', $password),
        'number' => preg_match('/[0-9]/', $password),
        'symbol' => preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)
    ];
    
    $valid = array_sum($requirements) === count($requirements);
    
    return [
        'valid' => $valid,
        'requirements' => $requirements
    ];
}
?> 