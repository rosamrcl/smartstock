<?php
/**
 * Sistema Centralizado de Validação - SmartStock
 * 
 * Este arquivo contém funções de validação reutilizáveis
 * para todo o sistema, garantindo consistência e segurança.
 */

/**
 * Validação de Email
 * @param string $email Email a ser validado
 * @return array Array com 'valid' (boolean) e 'message' (string)
 */
function validateEmail($email) {
    $email = trim($email);
    
    if (empty($email)) {
        return ['valid' => false, 'message' => 'O campo email é obrigatório'];
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['valid' => false, 'message' => 'Por favor, insira um email válido'];
    }
    
    return ['valid' => true, 'message' => ''];
}

/**
 * Validação de Senha
 * @param string $senha Senha a ser validada
 * @param bool $strongPassword Se deve validar senha forte (padrão: false)
 * @return array Array com 'valid' (boolean) e 'message' (string)
 */
function validatePassword($senha, $strongPassword = false) {
    if (empty($senha)) {
        return ['valid' => false, 'message' => 'O campo senha é obrigatório'];
    }
    
    if (strlen($senha) < 6) {
        return ['valid' => false, 'message' => 'A senha deve ter pelo menos 6 caracteres'];
    }
    
    if ($strongPassword) {
        if (strlen($senha) < 8) {
            return ['valid' => false, 'message' => 'A senha deve ter pelo menos 8 caracteres'];
        }
        
        if (!preg_match('/[A-Z]/', $senha)) {
            return ['valid' => false, 'message' => 'A senha deve conter pelo menos uma letra maiúscula'];
        }
        
        if (!preg_match('/[a-z]/', $senha)) {
            return ['valid' => false, 'message' => 'A senha deve conter pelo menos uma letra minúscula'];
        }
        
        if (!preg_match('/[0-9]/', $senha)) {
            return ['valid' => false, 'message' => 'A senha deve conter pelo menos um número'];
        }
        
        if (!preg_match('/[!@#$%^&*]/', $senha)) {
            return ['valid' => false, 'message' => 'A senha deve conter pelo menos um símbolo (!@#$%^&*)'];
        }
    }
    
    return ['valid' => true, 'message' => ''];
}

/**
 * Validação de Nome/Sobrenome
 * @param string $nome Nome a ser validado
 * @param string $fieldName Nome do campo para mensagem de erro
 * @return array Array com 'valid' (boolean) e 'message' (string)
 */
function validateName($nome, $fieldName = 'nome') {
    $nome = trim($nome);
    
    if (empty($nome)) {
        return ['valid' => false, 'message' => "O campo $fieldName é obrigatório"];
    }
    
    if (strlen($nome) < 2) {
        return ['valid' => false, 'message' => "O $fieldName deve ter pelo menos 2 caracteres"];
    }
    
    if (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $nome)) {
        return ['valid' => false, 'message' => "O $fieldName deve conter apenas letras"];
    }
    
    return ['valid' => true, 'message' => ''];
}

/**
 * Validação de Quantidade/Número
 * @param mixed $quantidade Quantidade a ser validada
 * @param int $min Valor mínimo (padrão: 0)
 * @param int $max Valor máximo (padrão: null)
 * @return array Array com 'valid' (boolean) e 'message' (string)
 */
function validateQuantity($quantidade, $min = 0, $max = null) {
    if (empty($quantidade) && $quantidade !== '0') {
        return ['valid' => false, 'message' => 'A quantidade é obrigatória'];
    }
    
    if (!is_numeric($quantidade)) {
        return ['valid' => false, 'message' => 'A quantidade deve ser um número'];
    }
    
    $quantidade = (int)$quantidade;
    
    if ($quantidade < $min) {
        return ['valid' => false, 'message' => "A quantidade não pode ser menor que $min"];
    }
    
    if ($max !== null && $quantidade > $max) {
        return ['valid' => false, 'message' => "A quantidade não pode ser maior que $max"];
    }
    
    return ['valid' => true, 'message' => ''];
}

/**
 * Validação de Arquivo de Imagem
 * @param array $file Array $_FILES do arquivo
 * @param int $maxSize Tamanho máximo em bytes (padrão: 5MB)
 * @param array $allowedTypes Tipos permitidos (padrão: ['jpg', 'jpeg', 'png', 'gif', 'webp'])
 * @param int $maxWidth Largura máxima (padrão: 2048)
 * @param int $maxHeight Altura máxima (padrão: 2048)
 * @return array Array com 'valid' (boolean) e 'message' (string)
 */
function validateImageFile($file, $maxSize = 5242880, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'], $maxWidth = 2048, $maxHeight = 2048) {
    // Verificar se arquivo foi enviado
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return ['valid' => false, 'message' => 'Nenhum arquivo foi selecionado'];
    }
    
    // Verificar erros de upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'O arquivo excede o tamanho máximo permitido pelo servidor',
            UPLOAD_ERR_FORM_SIZE => 'O arquivo excede o tamanho máximo permitido pelo formulário',
            UPLOAD_ERR_PARTIAL => 'O arquivo foi enviado parcialmente',
            UPLOAD_ERR_NO_FILE => 'Nenhum arquivo foi enviado',
            UPLOAD_ERR_NO_TMP_DIR => 'Falta uma pasta temporária',
            UPLOAD_ERR_CANT_WRITE => 'Falha ao escrever o arquivo no disco',
            UPLOAD_ERR_EXTENSION => 'Uma extensão PHP parou o upload do arquivo'
        ];
        
        return ['valid' => false, 'message' => $errors[$file['error']] ?? 'Erro desconhecido no upload'];
    }
    
    // Verificar tamanho
    if ($file['size'] > $maxSize) {
        $maxSizeMB = $maxSize / 1024 / 1024;
        return ['valid' => false, 'message' => "O arquivo excede o tamanho máximo de {$maxSizeMB}MB"];
    }
    
    // Verificar tipo MIME
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $allowedMimes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp'
    ];
    
    if (!in_array($mimeType, $allowedMimes)) {
        return ['valid' => false, 'message' => 'Tipo de arquivo não permitido. Use apenas JPG, PNG, GIF ou WEBP'];
    }
    
    // Verificar extensão
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedTypes)) {
        return ['valid' => false, 'message' => 'Extensão de arquivo não permitida. Use apenas JPG, PNG, GIF ou WEBP'];
    }
    
    // Verificar se é uma imagem válida
    $imageInfo = getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        return ['valid' => false, 'message' => 'O arquivo não é uma imagem válida'];
    }
    
    // Verificar dimensões
    if ($imageInfo[0] > $maxWidth || $imageInfo[1] > $maxHeight) {
        return ['valid' => false, 'message' => "A imagem deve ter no máximo {$maxWidth}x{$maxHeight} pixels"];
    }
    
    return ['valid' => true, 'message' => ''];
}

/**
 * Sanitização de Dados
 * @param string $data Dados a serem sanitizados
 * @return string Dados sanitizados
 */
function sanitizeData($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Geração de Token Seguro
 * @param int $length Comprimento do token (padrão: 32)
 * @return string Token gerado
 */
function generateSecureToken($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Rate Limiting Simples
 * @param string $key Chave única para o limite (ex: IP, email)
 * @param int $maxAttempts Máximo de tentativas (padrão: 5)
 * @param int $timeWindow Janela de tempo em segundos (padrão: 300 = 5 min)
 * @return array Array com 'allowed' (boolean) e 'message' (string)
 */
function checkRateLimit($key, $maxAttempts = 5, $timeWindow = 300) {
    $cacheFile = sys_get_temp_dir() . '/rate_limit_' . md5($key) . '.txt';
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
        return [
            'allowed' => false, 
            'message' => "Muitas tentativas. Tente novamente em " . ceil($waitTime / 60) . " minutos"
        ];
    }
    
    // Adicionar nova tentativa
    $attempts[] = $currentTime;
    file_put_contents($cacheFile, json_encode($attempts));
    
    return ['allowed' => true, 'message' => ''];
}

/**
 * Validação de Campos Obrigatórios
 * @param array $data Array com os dados
 * @param array $requiredFields Array com os campos obrigatórios
 * @return array Array com 'valid' (boolean) e 'message' (string)
 */
function validateRequiredFields($data, $requiredFields) {
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            return ['valid' => false, 'message' => "O campo $field é obrigatório"];
        }
    }
    
    return ['valid' => true, 'message' => ''];
}

/**
 * Validação de Confirmação de Senha
 * @param string $senha Senha original
 * @param string $confirmacao Confirmação da senha
 * @return array Array com 'valid' (boolean) e 'message' (string)
 */
function validatePasswordConfirmation($senha, $confirmacao) {
    if (empty($confirmacao)) {
        return ['valid' => false, 'message' => 'O campo confirmar senha é obrigatório'];
    }
    
    if ($senha !== $confirmacao) {
        return ['valid' => false, 'message' => 'As senhas não coincidem'];
    }
    
    return ['valid' => true, 'message' => ''];
}

/**
 * Log de Erros Seguro
 * @param string $message Mensagem de erro
 * @param string $context Contexto do erro
 * @param array $data Dados adicionais (opcional)
 */
function logError($message, $context = '', $data = []) {
    $logFile = __DIR__ . '/../../logs/error.log';
    $logDir = dirname($logFile);
    
    // Criar diretório de logs se não existir
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    
    $logEntry = [
        'timestamp' => $timestamp,
        'message' => $message,
        'context' => $context,
        'ip' => $ip,
        'user_agent' => $userAgent,
        'data' => $data
    ];
    
    file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
}

/**
 * Verificação de Sessão Ativa
 * @return bool True se a sessão está ativa
 */
function isSessionActive() {
    return isset($_SESSION['id_user']) && !empty($_SESSION['id_user']);
}

/**
 * Redirecionamento Seguro
 * @param string $url URL para redirecionamento
 * @param array $params Parâmetros adicionais (opcional)
 */
function secureRedirect($url, $params = []) {
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    
    header("Location: $url");
    exit;
}

/**
 * Mensagem de Sessão
 * @param string $type Tipo da mensagem (success, error, warning, info)
 * @param string $message Mensagem
 */
function setSessionMessage($type, $message) {
    $_SESSION[$type . '_msg'] = [$message];
}

/**
 * Limpeza de Mensagens de Sessão
 */
function clearSessionMessages() {
    $messageTypes = ['success', 'error', 'warning', 'info'];
    foreach ($messageTypes as $type) {
        unset($_SESSION[$type . '_msg']);
    }
}
?> 