<?php
require_once("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['token'] ?? '';
    $novaSenha = $_POST['senha'] ?? '';
    $confirmaSenha = $_POST['csenha'] ?? '';

    // Verifica se as senhas coincidem
    if ($novaSenha !== $confirmaSenha) {
        die("<p style='color:red;'>As senhas não coincidem.</p>");
    }

    // Busca token na tabela
    $stmt = $pdo->prepare("SELECT * FROM redefinicao_senha WHERE token = ? LIMIT 1");
    $stmt->execute([$token]);
    $dados = $stmt->fetch();

    if (!$dados) {
        die("<p style='color:red;'>Token inválido ou expirado.</p>");
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

    echo "<p style='color:green; text-align:center;'>Senha atualizada com sucesso! Você já pode fazer login.</p>";
}
?>
