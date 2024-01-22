<?php 
include 'conect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pedido'])) {
    echo json_encode(['status' => 'entrou']);
    exit;
}
?>