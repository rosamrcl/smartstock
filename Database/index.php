<?php 

try {

    require_once("/laragon/www/smartstock/Backend/conexao.php");

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Usuários
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id_user INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        sobrenome VARCHAR(100) NOT NULL,
        foto VARCHAR(255),
        email VARCHAR(100) UNIQUE NOT NULL,
        senha VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL
    )");

    // 2. Produtos
    $pdo->exec("CREATE TABLE IF NOT EXISTS produtos (
        id_products INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        descricao TEXT,
        status ENUM('Estoque', 'Manutenção', 'Em uso'),
        quantidade INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL
    )");

    // 3. Ordens de Serviço
    $pdo->exec("CREATE TABLE IF NOT EXISTS ordens_servico (
        id_services INT AUTO_INCREMENT PRIMARY KEY,
        id_responsible INT,
        solicitante VARCHAR(100) NOT NULL,
        categoria VARCHAR(100) NOT NULL,
        agendamento DATE,
        observacoes TEXT,
        status VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL,
        FOREIGN KEY (id_responsible) REFERENCES usuarios(id_user)
    )");

    // 4. Ordem Estoque
    $pdo->exec("CREATE TABLE IF NOT EXISTS ordem_estoque (
        id_stock INT AUTO_INCREMENT PRIMARY KEY,
        id_ordem INT,
        produto VARCHAR(100) NOT NULL,
        descricao VARCHAR(100) NOT NULL,
        quantidade INT NOT NULL,
        unidade VARCHAR(20) NOT NULL,
        localizacao VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL,
        FOREIGN KEY (id_ordem) REFERENCES ordens_servico(id_services)
    )");

    // 5. Check-list
    $pdo->exec("CREATE TABLE IF NOT EXISTS checklist (
        id_checklist INT AUTO_INCREMENT PRIMARY KEY,
        id_ordem INT,
        manutencao VARCHAR(100) NOT NULL,
        status VARCHAR(50) NOT NULL,
        etapa VARCHAR(100) NOT NULL,
        descricao_tarefa TEXT,
        data_verificacao DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL,
        FOREIGN KEY (id_ordem) REFERENCES ordens_servico(id_services)
    )");

    // 6. Suporte
    $pdo->exec("CREATE TABLE IF NOT EXISTS suporte (
        id_suport INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100),
        email VARCHAR(100),
        mensagem TEXT,
        arquivo VARCHAR(255),
        data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL
    )");

} catch (PDOException $e) {

    echo "Erro ao criar tabelas: " . $e->getMessage();

}

?>