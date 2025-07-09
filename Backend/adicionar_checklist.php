<?php
require_once("/laragon/www/smartstock/Backend/conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etapas = $_POST['etapas'] ?? [];
    $cliente = $_POST['cliente'] ?? '';
    $local = $_POST['local'] ?? '';

    if (empty($etapas) || empty($cliente) || empty($local)) {
        die("Todos os campos devem ser preenchidos.");
    }

    $etapasJson = json_encode($etapas);

    $stmt = $pdo->prepare("INSERT INTO checklist (etapas, cliente, local_servico) VALUES (?, ?, ?)");
    $stmt->execute([$etapasJson, $cliente, $local]);

    header("Location: ../Frontend/home.php#tab3"); // ou outra página de destino
    exit;
}
?>