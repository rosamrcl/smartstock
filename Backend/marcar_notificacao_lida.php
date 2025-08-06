<?php
session_start();
require_once('conexao.php');

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
$notificacao_id = $input['notificacao_id'] ?? null;

if (!$notificacao_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID da notificação é obrigatório']);
    exit;
}

try {
    // Marcar notificação como lida
    $stmt = $pdo->prepare("UPDATE notificacoes SET lida = 1 WHERE id = ?");
    $result = $stmt->execute([$notificacao_id]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Notificação marcada como lida']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao marcar notificação como lida']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
}
?> 