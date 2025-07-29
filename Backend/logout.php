<?php 
session_start();

// Verificar se o usuário estava logado
$wasLoggedIn = isset($_SESSION['id_user']);

// Destruir a sessão
session_destroy();

// Iniciar nova sessão para mensagem
session_start();

if ($wasLoggedIn) {
    $_SESSION['success_msg'] = ["Logout realizado com sucesso. Até logo!"];
} else {
    $_SESSION['info_msg'] = ["Você não estava logado."];
}

header('Location: ../Frontend/login.php');
exit;
?>