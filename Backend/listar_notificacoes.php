<?php
session_start();
require_once('conexao.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['id_user'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado']);
    exit;
}

try {
    // Buscar notificações não lidas primeiro, depois as lidas
    $stmt = $pdo->prepare("
        SELECT n.*, os.solicitante, os.categoria, os.urgencia 
        FROM notificacoes n 
        INNER JOIN ordens_servico os ON n.chamado_id = os.id_services 
        ORDER BY n.lida ASC, n.data_criacao DESC 
        LIMIT 20
    ");
    $stmt->execute();
    $notificacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Contar notificações não lidas
    $stmt_count = $pdo->prepare("SELECT COUNT(*) as total FROM notificacoes WHERE lida = 0");
    $stmt_count->execute();
    $count_result = $stmt_count->fetch(PDO::FETCH_ASSOC);
    $nao_lidas = $count_result['total'];
    
    echo json_encode([
        'success' => true, 
        'notificacoes' => $notificacoes,
        'nao_lidas' => $nao_lidas
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
}
?> 