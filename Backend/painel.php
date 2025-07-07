<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// resto do código do painel
require_once __DIR__ . '/conexao.php';

$stmtChamados = $pdo->query("SELECT * FROM suporte ORDER BY data_envio DESC");
$chamados = $stmtChamados->fetchAll(PDO::FETCH_ASSOC);
?>