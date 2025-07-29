<?php
session_start();
require_once("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['token'] ?? '';
    $novaSenha = $_POST['senha'] ?? '';
    $confirmaSenha = $_POST['csenha'] ?? '';

    // Validação de campos obrigatórios
    if (empty($token) || empty($novaSenha) || empty($confirmaSenha)) {
        $_SESSION['error_msg'] = ["Por favor, preencha todos os campos."];
        header('Location: ../Frontend/redefinir_senha.php?token=' . urlencode($token));
        exit;
    }

    // Validação de tamanho da senha
    if (strlen($novaSenha) < 6) {
        $_SESSION['error_msg'] = ["A senha deve ter pelo menos 6 caracteres."];
        header('Location: ../Frontend/redefinir_senha.php?token=' . urlencode($token));
        exit;
    }

    // Verifica se as senhas coincidem
    if ($novaSenha !== $confirmaSenha) {
        $_SESSION['error_msg'] = ["As senhas não coincidem. Tente novamente."];
        header('Location: ../Frontend/redefinir_senha.php?token=' . urlencode($token));
        exit;
    }

    try {
        // Busca token na tabela
        $stmt = $pdo->prepare("SELECT * FROM redefinicao_senha WHERE token = ? AND usado = 0 AND expira_em > NOW() LIMIT 1");
        $stmt->execute([$token]);
        $dados = $stmt->fetch();

        if (!$dados) {
            $_SESSION['error_msg'] = ["Token inválido, expirado ou já utilizado. Solicite um novo link de redefinição."];
            header('Location: ../Frontend/esqueci_senha.php');
            exit;
        }

        $email = $dados['email'];

        // Verifica se o usuário ainda existe
        $stmt = $pdo->prepare("SELECT id_user, nome FROM usuarios WHERE email = ? AND deleted_at IS NULL");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if (!$usuario) {
            $_SESSION['error_msg'] = ["Usuário não encontrado. O email pode ter sido removido."];
            header('Location: ../Frontend/esqueci_senha.php');
            exit;
        }

        // Criptografa a nova senha
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

        // Atualiza senha na tabela de usuários
        $update = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
        $update->execute([$senhaHash, $email]);

        // Marca token como usado
        $stmt = $pdo->prepare("UPDATE redefinicao_senha SET usado = 1 WHERE token = ?");
        $stmt->execute([$token]);

        // Remove outros tokens para este email
        $stmt = $pdo->prepare("DELETE FROM redefinicao_senha WHERE email = ? AND token != ?");
        $stmt->execute([$email, $token]);

        $_SESSION['success_msg'] = [
            "Senha atualizada com sucesso!",
            "Você já pode fazer login com sua nova senha."
        ];
        
        // Redireciona para a página de login
        header('Location: ../Frontend/login.php');
        exit;

    } catch (PDOException $e) {
        $_SESSION['error_msg'] = ["Erro interno do sistema. Tente novamente mais tarde."];
        header('Location: ../Frontend/redefinir_senha.php?token=' . urlencode($token));
        exit;
    }

} else {
    // Método não permitido
    header('Location: ../Frontend/esqueci_senha.php');
    exit;
}
?>
