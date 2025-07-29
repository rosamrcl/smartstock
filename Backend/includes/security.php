<?php
/**
 * Sistema Centralizado de Segurança - SmartStock
 * 
 * Este arquivo contém funções de segurança reutilizáveis
 * para todo o sistema, garantindo proteção contra ataques comuns.
 */

// Configurações de segurança
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

/**
 * Inicialização Segura da Sessão
 */
function secureSessionStart() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Regenerar ID da sessão periodicamente
    if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
    } elseif (time() - $_SESSION['last_regeneration'] > 1800) { // 30 minutos
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}

/**
 * Verificação de Autenticação
 * @param bool $redirect Se deve redirecionar em caso de não autenticado
 * @return bool True se autenticado
 */
function requireAuth($redirect = true) {
    secureSessionStart();
    
    if (!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
        if ($redirect) {
            setSessionMessage('error', 'Você precisa estar logado para acessar esta página');
            secureRedirect('../Frontend/login.php');
        }
        return false;
    }
    
    return true;
}

/**
 * Verificação de CSRF Token
 * @param string $token Token recebido
 * @return bool True se válido
 */
function validateCSRFToken($token) {
    secureSessionStart();
    
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Geração de CSRF Token
 * @return string Token gerado
 */
function generateCSRFToken() {
    secureSessionStart();
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Sanitização de Input
 * @param mixed $input Input a ser sanitizado
 * @param string $type Tipo de sanitização (string, int, float, email)
 * @return mixed Input sanitizado
 */
function sanitizeInput($input, $type = 'string') {
    if (is_array($input)) {
        return array_map(function($item) use ($type) {
            return sanitizeInput($item, $type);
        }, $input);
    }
    
    switch ($type) {
        case 'int':
            return (int) filter_var($input, FILTER_SANITIZE_NUMBER_INT);
        case 'float':
            return (float) filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        case 'email':
            return filter_var($input, FILTER_SANITIZE_EMAIL);
        case 'url':
            return filter_var($input, FILTER_SANITIZE_URL);
        case 'string':
        default:
            return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Validação de Input
 * @param mixed $input Input a ser validado
 * @param string $type Tipo de validação
 * @param array $options Opções adicionais
 * @return array Array com 'valid' (boolean) e 'message' (string)
 */
function validateInput($input, $type, $options = []) {
    switch ($type) {
        case 'email':
            if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
                return ['valid' => false, 'message' => 'Email inválido'];
            }
            break;
            
        case 'url':
            if (!filter_var($input, FILTER_VALIDATE_URL)) {
                return ['valid' => false, 'message' => 'URL inválida'];
            }
            break;
            
        case 'int':
            if (!is_numeric($input) || (int)$input != $input) {
                return ['valid' => false, 'message' => 'Valor deve ser um número inteiro'];
            }
            if (isset($options['min']) && (int)$input < $options['min']) {
                return ['valid' => false, 'message' => "Valor mínimo é {$options['min']}"];
            }
            if (isset($options['max']) && (int)$input > $options['max']) {
                return ['valid' => false, 'message' => "Valor máximo é {$options['max']}"];
            }
            break;
            
        case 'string':
            if (isset($options['min_length']) && strlen($input) < $options['min_length']) {
                return ['valid' => false, 'message' => "Mínimo de {$options['min_length']} caracteres"];
            }
            if (isset($options['max_length']) && strlen($input) > $options['max_length']) {
                return ['valid' => false, 'message' => "Máximo de {$options['max_length']} caracteres"];
            }
            if (isset($options['pattern']) && !preg_match($options['pattern'], $input)) {
                return ['valid' => false, 'message' => $options['pattern_message'] ?? 'Formato inválido'];
            }
            break;
    }
    
    return ['valid' => true, 'message' => ''];
}

/**
 * Proteção contra SQL Injection
 * @param PDO $pdo Conexão PDO
 * @param string $sql Query SQL
 * @param array $params Parâmetros para bind
 * @return PDOStatement Statement preparado
 */
function secureQuery($pdo, $sql, $params = []) {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        logError('SQL Error: ' . $e->getMessage(), 'Database', [
            'sql' => $sql,
            'params' => $params
        ]);
        throw new Exception('Erro interno do servidor');
    }
}

/**
 * Hash Seguro de Senha
 * @param string $password Senha em texto plano
 * @return string Hash da senha
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_ARGON2ID, [
        'memory_cost' => 65536,
        'time_cost' => 4,
        'threads' => 3
    ]);
}

/**
 * Verificação de Senha
 * @param string $password Senha em texto plano
 * @param string $hash Hash da senha
 * @return bool True se a senha está correta
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Geração de Token de Recuperação
 * @param int $length Comprimento do token
 * @return string Token gerado
 */
function generateRecoveryToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Rate Limiting Avançado
 * @param string $key Chave única
 * @param int $maxAttempts Máximo de tentativas
 * @param int $timeWindow Janela de tempo
 * @param string $action Ação específica
 * @return array Resultado da verificação
 */
function advancedRateLimit($key, $maxAttempts = 5, $timeWindow = 300, $action = 'default') {
    $cacheFile = sys_get_temp_dir() . '/rate_limit_' . md5($key . $action) . '.txt';
    $currentTime = time();
    
    // Ler tentativas existentes
    $attempts = [];
    if (file_exists($cacheFile)) {
        $attempts = json_decode(file_get_contents($cacheFile), true) ?: [];
    }
    
    // Remover tentativas antigas
    $attempts = array_filter($attempts, function($attempt) use ($currentTime, $timeWindow) {
        return $attempt > ($currentTime - $timeWindow);
    });
    
    // Verificar se excedeu o limite
    if (count($attempts) >= $maxAttempts) {
        $oldestAttempt = min($attempts);
        $waitTime = $timeWindow - ($currentTime - $oldestAttempt);
        
        logError("Rate limit exceeded", "Security", [
            'key' => $key,
            'action' => $action,
            'attempts' => count($attempts),
            'wait_time' => $waitTime
        ]);
        
        return [
            'allowed' => false,
            'message' => "Muitas tentativas. Tente novamente em " . ceil($waitTime / 60) . " minutos",
            'wait_time' => $waitTime
        ];
    }
    
    // Adicionar nova tentativa
    $attempts[] = $currentTime;
    file_put_contents($cacheFile, json_encode($attempts), LOCK_EX);
    
    return ['allowed' => true, 'message' => '', 'attempts_remaining' => $maxAttempts - count($attempts)];
}

/**
 * Log de Segurança
 * @param string $event Evento de segurança
 * @param string $level Nível (info, warning, error, critical)
 * @param array $data Dados adicionais
 */
function logSecurityEvent($event, $level = 'info', $data = []) {
    $logFile = __DIR__ . '/../../logs/security.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $userId = $_SESSION['id_user'] ?? 'guest';
    
    $logEntry = [
        'timestamp' => $timestamp,
        'level' => $level,
        'event' => $event,
        'ip' => $ip,
        'user_agent' => $userAgent,
        'user_id' => $userId,
        'data' => $data
    ];
    
    file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
}

/**
 * Verificação de IP Suspeito
 * @param string $ip IP a ser verificado
 * @return bool True se suspeito
 */
function isSuspiciousIP($ip) {
    // Lista de IPs suspeitos (exemplo)
    $suspiciousIPs = [
        // Adicione IPs suspeitos aqui
    ];
    
    return in_array($ip, $suspiciousIPs);
}

/**
 * Headers de Segurança
 */
function setSecurityHeaders() {
    // Proteção contra XSS
    header('X-XSS-Protection: 1; mode=block');
    
    // Proteção contra clickjacking
    header('X-Frame-Options: DENY');
    
    // Proteção contra MIME sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Referrer Policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Content Security Policy
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self' https://cdnjs.cloudflare.com;");
}

/**
 * Verificação de Sessão Expirada
 * @param int $timeout Tempo limite em segundos (padrão: 3600 = 1 hora)
 * @return bool True se a sessão expirou
 */
function isSessionExpired($timeout = 3600) {
    secureSessionStart();
    
    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time();
        return false;
    }
    
    if (time() - $_SESSION['last_activity'] > $timeout) {
        session_destroy();
        return true;
    }
    
    $_SESSION['last_activity'] = time();
    return false;
}

/**
 * Limpeza de Sessão
 */
function cleanSession() {
    secureSessionStart();
    
    // Remover dados sensíveis
    unset($_SESSION['temp_password']);
    unset($_SESSION['recovery_token']);
    
    // Manter apenas dados essenciais
    $essentialData = [
        'id_user' => $_SESSION['id_user'] ?? null,
        'nome' => $_SESSION['nome'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'foto' => $_SESSION['foto'] ?? null,
        'last_activity' => $_SESSION['last_activity'] ?? null,
        'csrf_token' => $_SESSION['csrf_token'] ?? null
    ];
    
    session_unset();
    foreach ($essentialData as $key => $value) {
        if ($value !== null) {
            $_SESSION[$key] = $value;
        }
    }
}

/**
 * Verificação de Permissões
 * @param string $permission Permissão necessária
 * @return bool True se tem permissão
 */
function hasPermission($permission) {
    secureSessionStart();
    
    // Implementar sistema de permissões aqui
    // Por enquanto, retorna true para usuários logados
    return isset($_SESSION['id_user']) && !empty($_SESSION['id_user']);
}

/**
 * Logout Seguro
 */
function secureLogout() {
    secureSessionStart();
    
    // Log do logout
    logSecurityEvent('User logout', 'info', [
        'user_id' => $_SESSION['id_user'] ?? 'unknown',
        'email' => $_SESSION['email'] ?? 'unknown'
    ]);
    
    // Limpar sessão
    session_unset();
    session_destroy();
    
    // Deletar cookie da sessão
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
}

/**
 * Inicialização de Segurança
 */
function initializeSecurity() {
    // Configurar headers de segurança
    setSecurityHeaders();
    
    // Iniciar sessão segura
    secureSessionStart();
    
    // Verificar se a sessão expirou
    if (isSessionExpired()) {
        setSessionMessage('warning', 'Sua sessão expirou. Faça login novamente.');
        secureRedirect('../Frontend/login.php');
    }
    
    // Limpar sessão periodicamente
    if (isset($_SESSION['last_cleanup'])) {
        if (time() - $_SESSION['last_cleanup'] > 1800) { // 30 minutos
            cleanSession();
            $_SESSION['last_cleanup'] = time();
        }
    } else {
        $_SESSION['last_cleanup'] = time();
    }
    
    // Log de IP suspeito
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    if (isSuspiciousIP($ip)) {
        logSecurityEvent('Suspicious IP detected', 'warning', ['ip' => $ip]);
    }
}
?> 