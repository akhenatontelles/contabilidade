<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

$response = [];

if (isset($_SESSION['user_id']) && $_SESSION['user_tipo'] === 'master') {
    $conexao = conectar();
    $sql = "SELECT id, nome, email, empresa FROM users WHERE tipo = 'gestor'";
    $result = $conexao->query($sql);

    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }

    $conexao->close();
}

echo json_encode($response);
?>
