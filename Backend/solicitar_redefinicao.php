<?php
require_once "../Backend/conexao.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Verifica se o e-mail existe no banco
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount()) {
        $token = bin2hex(random_bytes(32));

        // Armazena token na tabela
        $stmt = $pdo->prepare("INSERT INTO redefinicao_senha (email, token) VALUES (?, ?)");
        $stmt->execute([$email, $token]);

        // Link de redefinição
        $url = "http://localhost/smartstock/Frontend/redefinir_senha.php?token=$token";

        // Aqui você poderia usar PHPMailer para enviar
        echo "<p style='text-align:center;'>Um link foi enviado: <a href='$url'>$url</a></p>";
    } else {
        echo "<p style='color:red;'>E-mail não encontrado.</p>";
    }
}
?>