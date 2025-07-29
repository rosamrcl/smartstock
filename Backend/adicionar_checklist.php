<?php
session_start();
require_once("conexao.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etapas = $_POST['etapas'] ?? [];
    $cliente = trim($_POST['cliente'] ?? '');
    $local = trim($_POST['local'] ?? '');

    // Validações
    if (empty($etapas)) {
        $_SESSION['error_msg'] = ["Selecione pelo menos uma etapa do checklist."];
        header("Location: ../Frontend/ordemdeserviço.php#tab3");
        exit;
    }

    if (empty($cliente)) {
        $_SESSION['error_msg'] = ["Nome do cliente é obrigatório."];
        header("Location: ../Frontend/ordemdeserviço.php#tab3");
        exit;
    }

    if (empty($local)) {
        $_SESSION['error_msg'] = ["Local do serviço é obrigatório."];
        header("Location: ../Frontend/ordemdeserviço.php#tab3");
        exit;
    }

    try {
        $etapasJson = json_encode($etapas);

        $stmt = $pdo->prepare("INSERT INTO checklist (etapas, cliente, local_servico) VALUES (?, ?, ?)");
        $result = $stmt->execute([$etapasJson, $cliente, $local]);
        
        if ($result) {
            $_SESSION['success_msg'] = ["Item adicionado com sucesso!"];
        } else {
            $_SESSION['error_msg'] = ["Erro ao adicionar item. Tente novamente."];
        }
        
        header("Location: ../Frontend/ordemdeserviço.php#tab3");
        exit;
    } catch (PDOException $e) {
        error_log("Erro ao inserir checklist: " . $e->getMessage());
        $_SESSION['error_msg'] = ["Erro ao adicionar item. Tente novamente."];
        header("Location: ../Frontend/ordemdeserviço.php#tab3");
        exit;
    }
}
?>