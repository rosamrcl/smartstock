<?php
session_start();
require_once 'conexao.php';

// ===== CONFIGURAÇÕES DE SEGURANÇA =====
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// ===== FUNÇÃO PARA GERAR TOKEN SEGURO =====
function generateSecureToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

// ===== FUNÇÃO PARA LIMPAR TOKENS EXPIRADOS =====
function cleanExpiredTokens($pdo) {
    $stmt = $pdo->prepare("DELETE FROM password_reset_tokens WHERE expires_at < NOW() OR used = 1");
    $stmt->execute();
}

// ===== FUNÇÃO PARA VALIDAR EMAIL =====
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) && 
           strlen($email) <= 255 &&
           preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
}

// ===== FUNÇÃO PARA VERIFICAR SE EMAIL EXISTE =====
function emailExists($pdo, $email) {
    $stmt = $pdo->prepare("SELECT id_user FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch() !== false;
}

// ===== FUNÇÃO PARA CRIAR TOKEN DE RESET =====
function createResetToken($pdo, $email) {
    // Limpar tokens expirados primeiro
    cleanExpiredTokens($pdo);
    
    // Verificar se já existe um token válido para este email
    $stmt = $pdo->prepare("SELECT id FROM password_reset_tokens WHERE user_email = ? AND expires_at > NOW() AND used = 0");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Já existe uma solicitação de reset pendente para este email. Aguarde 1 hora ou verifique seu email.'];
    }
    
    // Gerar token único
    $token = generateSecureToken(32);
    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // Inserir token no banco
    $stmt = $pdo->prepare("INSERT INTO password_reset_tokens (user_email, token, expires_at) VALUES (?, ?, ?)");
    
    try {
        $stmt->execute([$email, $token, $expiresAt]);
        
        // Para localhost, retornar o link diretamente
        $resetLink = "http://localhost/smartstock/Frontend/reset_password.php?token=" . $token;
        
        return [
            'success' => true, 
            'message' => 'Link de recuperação gerado com sucesso!',
            'reset_link' => $resetLink,
            'token' => $token // Apenas para desenvolvimento
        ];
        
    } catch (PDOException $e) {
        error_log("Erro ao criar token de reset: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erro interno do servidor. Tente novamente.'];
    }
}

// ===== PROCESSAMENTO PRINCIPAL =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validar CSRF token (se implementado)
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token'] ?? '') {
            throw new Exception('Token de segurança inválido.');
        }
        
        // Validar e sanitizar email
        $email = trim($_POST['email'] ?? '');
        
        if (empty($email)) {
            throw new Exception('Email é obrigatório.');
        }
        
        if (!validateEmail($email)) {
            throw new Exception('Email inválido.');
        }
        
        // Verificar se email existe no sistema
        if (!emailExists($pdo, $email)) {
            // Por segurança, não revelar se email existe ou não
            $response = [
                'success' => true,
                'message' => 'Se o email estiver cadastrado em nosso sistema, você receberá um link de recuperação.',
                'reset_link' => null
            ];
        } else {
            // Email existe, criar token
            $result = createResetToken($pdo, $email);
            
            if ($result['success']) {
                $response = [
                    'success' => true,
                    'message' => 'Link de recuperação enviado com sucesso!',
                    'reset_link' => $result['reset_link'],
                    'token' => $result['token'] // Apenas para desenvolvimento
                ];
            } else {
                $response = $result;
            }
        }
        
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
header('Location: ../Frontend/esqueci_senha.php');
exit;
?> 