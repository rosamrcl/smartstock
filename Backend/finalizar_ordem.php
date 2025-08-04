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

if (!$input || !isset($input['ordem_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID da ordem é obrigatório']);
    exit;
}

$ordem_id = (int)$input['ordem_id'];

try {
    // Verificar se a ordem existe e não está finalizada
    $stmt = $pdo->prepare("SELECT id_services, status FROM ordens_servico WHERE id_services = ? AND deleted_at IS NULL");
    $stmt->execute([$ordem_id]);
    $ordem = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$ordem) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Ordem de serviço não encontrada']);
        exit;
    }

    if ($ordem['status'] === 'Concluída') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Ordem de serviço já foi finalizada']);
        exit;
    }

    // Finalizar a ordem de serviço
    $stmt = $pdo->prepare("UPDATE ordens_servico SET status = 'Concluída', updated_at = CURRENT_TIMESTAMP WHERE id_services = ?");
    $result = $stmt->execute([$ordem_id]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Ordem de serviço finalizada com sucesso']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erro ao finalizar ordem de serviço']);
    }

} catch (PDOException $e) {
    error_log("Erro ao finalizar ordem: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
}
?> 