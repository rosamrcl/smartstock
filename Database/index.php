<?php 

try {

    require_once("/laragon/www/smartstock/Backend/conexao.php");

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Usuários
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100),
        sobrenome VARCHAR(100),
        email VARCHAR(100) UNIQUE,
        senha VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL
    )");

    // 2. Produtos
    $pdo->exec("CREATE TABLE IF NOT EXISTS produtos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100),
        descricao TEXT,
        status VARCHAR(50),
        quantidade INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL
    )");

    // 3. Ordens de Serviço
    $pdo->exec("CREATE TABLE IF NOT EXISTS ordens_servico (
        id INT AUTO_INCREMENT PRIMARY KEY,
        solicitante VARCHAR(100),
        categoria VARCHAR(100),
        responsavel VARCHAR(100),
        agendamento DATE,
        observacoes TEXT,
        status VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL
    )");

    // 4. Ordem Estoque
    $pdo->exec("CREATE TABLE IF NOT EXISTS ordem_estoque (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_ordem INT,
        produto VARCHAR(100),
        descricao VARCHAR(100),
        quantidade INT,
        unidade VARCHAR(20),
        localizacao VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL,
        FOREIGN KEY (id_ordem) REFERENCES ordens_servico(id)
    )");

    // 5. Check-list
    $pdo->exec("CREATE TABLE IF NOT EXISTS checklist (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_ordem INT,
        manutencao VARCHAR(100),
        status VARCHAR(50),
        etapa VARCHAR(100),
        descricao_tarefa TEXT,
        data_verificacao DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
        deleted_at TIMESTAMP NULL,
        FOREIGN KEY (id_ordem) REFERENCES ordens_servico(id)
    )");

    // 6. Suporte
    $pdo->exec("CREATE TABLE IF NOT EXISTS suporte (
        id INT AUTO_INCREMENT PRIMARY KEY,
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