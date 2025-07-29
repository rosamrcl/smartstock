-- ===== TABELA PARA TOKENS DE RECUPERAÇÃO DE SENHA =====
-- Sistema "Esqueci a Senha" - SMARTSTOCK

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
);

-- ===== PROCEDURE PARA LIMPEZA AUTOMÁTICA DE TOKENS EXPIRADOS =====
DELIMITER //
CREATE PROCEDURE CleanExpiredTokens()
BEGIN
    DELETE FROM password_reset_tokens 
    WHERE expires_at < NOW() OR used = 1;
END //
DELIMITER ;

-- ===== EVENTO PARA LIMPEZA AUTOMÁTICA (OPCIONAL) =====
-- Descomente se quiser limpeza automática a cada hora
-- CREATE EVENT IF NOT EXISTS clean_tokens_event
-- ON SCHEDULE EVERY 1 HOUR
-- DO CALL CleanExpiredTokens();

-- ===== INSERÇÃO DE DADOS DE TESTE (OPCIONAL) =====
-- INSERT INTO password_reset_tokens (user_email, token, expires_at) VALUES
-- ('teste@smartstock.com', 'teste_token_123', DATE_ADD(NOW(), INTERVAL 1 HOUR)); 