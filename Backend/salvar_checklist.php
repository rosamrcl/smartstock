<?php
session_start();
require_once("conexao.php");

// Verificar se o usuário está logado
if (!isset($_SESSION['id_user'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

// Verificar se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

// Obter dados do JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['ordem_id']) || !isset($input['checklist_data'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

$ordem_id = (int)$input['ordem_id'];
$checklist_data = $input['checklist_data'];

try {
    // Verificar se a ordem existe
    $stmt = $pdo->prepare("SELECT id_services FROM ordens_servico WHERE id_services = ? AND deleted_at IS NULL");
    $stmt->execute([$ordem_id]);
    
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Ordem de serviço não encontrada']);
        exit;
    }

    // Salvar dados do checklist
    $checklist_json = json_encode($checklist_data);
    
    $stmt = $pdo->prepare("UPDATE ordens_servico SET checklist_status = ?, updated_at = CURRENT_TIMESTAMP WHERE id_services = ?");
    $result = $stmt->execute([$checklist_json, $ordem_id]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Checklist salvo com sucesso']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar checklist']);
    }

} catch (PDOException $e) {
    error_log("Erro ao salvar checklist: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
}
?> 