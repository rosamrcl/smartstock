<?php
session_start();
require_once("conexao.php");

// ADICIONAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    $nome = trim($_POST['productname']);
    $descricao = trim($_POST['descricao']);
    $status = $_POST['status'];
    $quantidade = (int)$_POST['quantidade'];
    $aba_atual = isset($_POST['aba_atual']) ? $_POST['aba_atual'] : 'tab1';

    // Validações
    if (empty($nome)) {
        $_SESSION['error_msg'] = ["O nome do produto é obrigatório."];
        header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba_atual");
        exit;
    }

    if (empty($descricao)) {
        $_SESSION['error_msg'] = ["A descrição do produto é obrigatória."];
        header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba_atual");
        exit;
    }

    if (empty($status) || $status === "") {
        $_SESSION['error_msg'] = ["O status do produto é obrigatório."];
        header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba_atual");
        exit;
    }

    if ($quantidade < 0) {
        $_SESSION['error_msg'] = ["A quantidade não pode ser negativa."];
        header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba_atual");
        exit;
    }

    try {
        // Verificar se a tabela existe
        $stmt = $pdo->query("SHOW TABLES LIKE 'produtos'");
        if ($stmt->rowCount() == 0) {
            // Criar tabela se não existir
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
        }

        // Inserir o produto
        $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, status, quantidade) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$nome, $descricao, $status, $quantidade]);
        
        if ($result) {
            $_SESSION['success_msg'] = ["Produto '$nome' adicionado com sucesso!"];
        } else {
            $_SESSION['error_msg'] = ["Erro ao adicionar o produto. Tente novamente."];
        }
        
        header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba_atual");
        exit;
    } catch (PDOException $e) {
        // Log do erro para debug
        error_log("Erro ao inserir produto: " . $e->getMessage());
        $_SESSION['error_msg'] = ["Erro ao adicionar o produto. Tente novamente."];
        header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba_atual");
        exit;
    }
}

// EXCLUIR (Exclusão Real)
if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    $aba = isset($_GET['aba']) ? $_GET['aba'] : 'tab1';
    
    if ($id <= 0) {
        $_SESSION['error_msg'] = ["ID de produto inválido."];
        header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba");
        exit;
    }

    try {
        // Verificar se o produto existe
        $stmt = $pdo->prepare("SELECT id_products, nome FROM produtos WHERE id_products = ?");
        $stmt->execute([$id]);
        $produto = $stmt->fetch();
        
        if (!$produto) {
            $_SESSION['error_msg'] = ["Produto não encontrado."];
            header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba");
            exit;
        }

        // Exclusão real
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id_products = ?");
        $result = $stmt->execute([$id]);
        
        if ($result && $stmt->rowCount() > 0) {
            $_SESSION['success_msg'] = ["Produto '{$produto['nome']}' excluído com sucesso!"];
        } else {
            $_SESSION['error_msg'] = ["Erro ao excluir o produto. Tente novamente."];
        }
        
        header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba");
        exit;
    } catch (PDOException $e) {
        error_log("Erro ao excluir produto: " . $e->getMessage());
        $_SESSION['error_msg'] = ["Erro ao excluir o produto. Tente novamente."];
        header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba");
        exit;
    }
}

// EDITAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'editar') {
    $id = (int)$_POST['id'];
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $status = $_POST['status'];
    $quantidade = (int)$_POST['quantidade'];
    $aba = isset($_POST['aba']) ? $_POST['aba'] : 'tab1';

    // Validações
    if ($id <= 0) {
        $_SESSION['error_msg'] = ["ID de produto inválido."];
        header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba");
        exit;
    }

    if (empty($nome)) {
        $_SESSION['error_msg'] = ["O nome do produto é obrigatório."];
        header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba");
        exit;
    }

    if (empty($descricao)) {
        $_SESSION['error_msg'] = ["A descrição do produto é obrigatória."];
        header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba");
        exit;
    }

    if (empty($status) || $status === "") {
        $_SESSION['error_msg'] = ["O status do produto é obrigatório."];
        header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba");
        exit;
    }

    if ($quantidade < 0) {
        $_SESSION['error_msg'] = ["A quantidade não pode ser negativa."];
        header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba");
        exit;
    }

    try {
        // Verificar se o produto existe
        $stmt = $pdo->prepare("SELECT id_products FROM produtos WHERE id_products = ?");
        $stmt->execute([$id]);
        
        if (!$stmt->fetch()) {
            $_SESSION['error_msg'] = ["Produto não encontrado."];
            header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba");
            exit;
        }

        $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, status = ?, quantidade = ?, updated_at = NOW() WHERE id_products = ?");
        $result = $stmt->execute([$nome, $descricao, $status, $quantidade, $id]);
        
        if ($result && $stmt->rowCount() > 0) {
            $_SESSION['success_msg'] = ["Produto '$nome' atualizado com sucesso!"];
        } else {
            $_SESSION['error_msg'] = ["Erro ao atualizar o produto. Tente novamente."];
        }
        
        header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba");
        exit;
    } catch (PDOException $e) {
        error_log("Erro ao atualizar produto: " . $e->getMessage());
        $_SESSION['error_msg'] = ["Erro ao atualizar o produto. Tente novamente."];
        header("Location: ../Frontend/gerenciarprodutos.php?aba=$aba");
        exit;
    }
}

// Se chegou aqui, redirecionar para a página principal
header("Location: ../Frontend/gerenciarprodutos.php");
exit;
?>