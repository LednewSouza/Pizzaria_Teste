<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'pizzaria';

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
    // Configurar o PDO para lançar exceções em caso de erros
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Conexão bem-sucedida!";
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}
?>