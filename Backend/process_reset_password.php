<?php
session_start();
require_once 'conexao.php';

// ===== CONFIGURAÇÕES DE SEGURANÇA =====
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// ===== FUNÇÃO PARA VALIDAR FORÇA DA SENHA =====
function validatePasswordStrength($password) {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = 'A senha deve ter pelo menos 8 caracteres.';
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'A senha deve conter pelo menos uma letra maiúscula.';
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'A senha deve conter pelo menos uma letra minúscula.';
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'A senha deve conter pelo menos um número.';
    }
    
    if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
        $errors[] = 'A senha deve conter pelo menos um símbolo especial.';
    }
    
    return $errors;
}

// ===== FUNÇÃO PARA VALIDAR TOKEN =====
function validateToken($pdo, $token) {
    $stmt = $pdo->prepare("
        SELECT id, user_email, expires_at, used 
        FROM password_reset_tokens 
        WHERE token = ? AND expires_at > NOW() AND used = 0
    ");
    $stmt->execute([$token]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// ===== FUNÇÃO PARA ATUALIZAR SENHA =====
function updatePassword($pdo, $email, $newPassword) {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
    return $stmt->execute([$hashedPassword, $email]);
}

// ===== FUNÇÃO PARA MARCAR TOKEN COMO USADO =====
function markTokenAsUsed($pdo, $tokenId) {
    $stmt = $pdo->prepare("UPDATE password_reset_tokens SET used = 1 WHERE id = ?");
    return $stmt->execute([$tokenId]);
}

// ===== FUNÇÃO PARA LIMPAR TOKENS EXPIRADOS =====
function cleanExpiredTokens($pdo) {
    $stmt = $pdo->prepare("DELETE FROM password_reset_tokens WHERE expires_at < NOW() OR used = 1");
    $stmt->execute();
}

// ===== PROCESSAMENTO PRINCIPAL =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validar CSRF token (se implementado)
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token'] ?? '') {
            throw new Exception('Token de segurança inválido.');
        }
        
        // Obter dados do formulário
        $token = trim($_POST['token'] ?? '');
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validações básicas
        if (empty($token)) {
            throw new Exception('Token de recuperação é obrigatório.');
        }
        
        if (empty($newPassword)) {
            throw new Exception('Nova senha é obrigatória.');
        }
        
        if (empty($confirmPassword)) {
            throw new Exception('Confirmação de senha é obrigatória.');
        }
        
        if ($newPassword !== $confirmPassword) {
            throw new Exception('As senhas não coincidem.');
        }
        
        // Validar força da senha
        $passwordErrors = validatePasswordStrength($newPassword);
        if (!empty($passwordErrors)) {
            throw new Exception('Senha fraca: ' . implode(' ', $passwordErrors));
        }
        
        // Limpar tokens expirados
        cleanExpiredTokens($pdo);
        
        // Validar token
        $tokenData = validateToken($pdo, $token);
        if (!$tokenData) {
            throw new Exception('Token inválido, expirado ou já utilizado.');
        }
        
        // Atualizar senha
        if (!updatePassword($pdo, $tokenData['user_email'], $newPassword)) {
            throw new Exception('Erro ao atualizar senha. Tente novamente.');
        }
        
        // Marcar token como usado
        if (!markTokenAsUsed($pdo, $tokenData['id'])) {
            // Log do erro, mas não falhar o processo
            error_log("Erro ao marcar token como usado: " . $tokenData['id']);
        }
        
        // Sucesso
        $response = [
            'success' => true,
            'message' => 'Senha atualizada com sucesso! Você será redirecionado para o login.',
            'redirect' => '../Frontend/login.php'
        ];
        
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
    
    // Retornar resposta JSON
    echo json_encode($response);
    exit;
}

// ===== SE NÃO FOR POST, REDIRECIONAR =====
header('Location: ../Frontend/reset_password.php');
exit;
?> 