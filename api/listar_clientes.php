<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

$response = [];

if (isset($_SESSION['user_id'])) {
    $conexao = conectar();
    $user_id = $_SESSION['user_id'];
    $user_tipo = $_SESSION['user_tipo'];

    $sql = "";
    if ($user_tipo === 'master' || $user_tipo === 'gestor') {
        // Master e Gestores podem ver todos os clientes
        $sql = "SELECT id, nome, email, empresa FROM users WHERE tipo = 'cliente'";
    }

    if (!empty($sql)) {
        $result = $conexao->query($sql);
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
    }

    $conexao->close();
}

echo json_encode($response);
?>
