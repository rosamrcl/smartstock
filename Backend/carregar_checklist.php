<?php
session_start();
require_once("conexao.php");

// Verificar se o usuário está logado
if (!isset($_SESSION['id_user'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

// Verificar se é uma requisição GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Obter ID da ordem
$ordem_id = isset($_GET['ordem_id']) ? (int)$_GET['ordem_id'] : 0;

if (!$ordem_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID da ordem é obrigatório']);
    exit;
}

try {
    // Buscar dados do checklist
    $stmt = $pdo->prepare("SELECT checklist_status FROM ordens_servico WHERE id_services = ? AND deleted_at IS NULL");
    $stmt->execute([$ordem_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Ordem de serviço não encontrada']);
        exit;
    }

    $checklist_data = null;
    if ($result['checklist_status']) {
        $checklist_data = json_decode($result['checklist_status'], true);
    }

    echo json_encode([
        'success' => true, 
        'checklist_data' => $checklist_data
    ]);

} catch (PDOException $e) {
    error_log("Erro ao carregar checklist: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
}
?> 