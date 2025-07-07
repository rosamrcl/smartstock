<?php

require_once("../Backend/conexao.php");

// ADICIONAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'adicionar') {
    $nome = $_POST['productname'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];
    $quantidade = $_POST['quantidade'];

    $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, status, quantidade) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nome, $descricao, $status, $quantidade]);
    header("Location: ../Frontend/gerenciarprodutos.php");
    exit;
}

// EXCLUIR
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id_products = ?");
    $stmt->execute([$id]);
    header("Location: ../Frontend/gerenciarprodutos.php");
    exit;
}

// EDITAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'editar') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];
    $quantidade = $_POST['quantidade'];

    $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, status = ?, quantidade = ? WHERE id_products = ?");
    $stmt->execute([$nome, $descricao, $status, $quantidade, $id]);
    header("Location: ../Frontend/gerenciarprodutos.php");
    exit;
}
?>