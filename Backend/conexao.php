<?php 

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'smartstock';

try {

    $pdo = new PDO("mysql:host=$host;charset=utf8", $user, $pass);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS smartstock");
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ===== CRIAÇÃO DA TABELA PASSWORD_RESET_TOKENS =====
    $createPasswordResetTokensTable = "
    CREATE TABLE IF NOT EXISTS password_reset_tokens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_email VARCHAR(255) NOT NULL,
        token VARCHAR(255) NOT NULL UNIQUE,
        expires_at DATETIME NOT NULL,
        used TINYINT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_token (token),
        INDEX idx_email (user_email),
        INDEX idx_expires (expires_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";
    
    $pdo->exec($createPasswordResetTokensTable);

    // ===== CRIAÇÃO DA PROCEDURE DE LIMPEZA AUTOMÁTICA =====
    $createCleanExpiredTokensProcedure = "
    CREATE PROCEDURE IF NOT EXISTS CleanExpiredTokens()
    BEGIN
        DELETE FROM password_reset_tokens 
        WHERE expires_at < NOW() OR used = 1;
    END;
    ";
    
    try {
        $pdo->exec($createCleanExpiredTokensProcedure);
    } catch (PDOException $e) {
        // Procedure já existe ou erro na criação (não crítico)
        // error_log("Erro ao criar procedure CleanExpiredTokens: " . $e->getMessage());
    }

} catch (PDOException $e) {

    die("Erro na conexão: " . $e->getMessage());
    
}

?>