<?php
require_once("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['token'] ?? '';
    $novaSenha = $_POST['senha'] ?? '';
    $confirmaSenha = $_POST['csenha'] ?? '';

    // Verifica se as senhas coincidem
    if ($novaSenha !== $confirmaSenha) {
        $warning_msg[] = 'As senhas não coincidem.';
    }

    // Busca token na tabela
    $stmt = $pdo->prepare("SELECT * FROM redefinicao_senha WHERE token = ? LIMIT 1");
    $stmt->execute([$token]);
    $dados = $stmt->fetch();

    if (!$dados) {
        $info_msg[] = 'Token inválido ou expirado.';
    }

    $email = $dados['email'];

    // Criptografa a nova senha
    $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

    // Atualiza senha na tabela de usuários
    $update = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
    $update->execute([$senhaHash, $email]);

    // Remove token após uso
    $delete = $pdo->prepare("DELETE FROM redefinicao_senha WHERE email = ?");
    $delete->execute([$email]);

    $sucess_msg[] = 'Senha atualizada com sucesso! Você já pode fazer login';
}
?>
