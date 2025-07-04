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

} catch (PDOException $e) {

    die("Erro na conexão: " . $e->getMessage());
    
}

?>