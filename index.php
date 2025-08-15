<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar se o usuário já está logado
if (isset($_SESSION['id_user']) && !empty($_SESSION['id_user'])) {
    // Se já está logado, redirecionar para o painel
    header("Location: Frontend/home.php");
    exit();
} else {
    // Se não está logado, redirecionar para o login
    header("Location: Frontend/login.php");
    exit();
}
?> 