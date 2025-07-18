<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

$response = ['pastas' => [], 'arquivos' => []];

if (isset($_SESSION['user_id'])) {
    $conexao = conectar();
    $user_id = $_SESSION['user_id'];
    $pasta_id = $_GET['pasta_id'] ?? null;

    // Buscar Pastas
    $sql_pastas = "SELECT id, nome, criado_em FROM pastas WHERE user_id = ? AND pai_id " . ($pasta_id ? "= ?" : "IS NULL");
    $stmt_pastas = $conexao->prepare($sql_pastas);
    if ($pasta_id) {
        $stmt_pastas->bind_param('ii', $user_id, $pasta_id);
    } else {
        $stmt_pastas->bind_param('i', $user_id);
    }
    $stmt_pastas->execute();
    $result_pastas = $stmt_pastas->get_result();
    while ($row = $result_pastas->fetch_assoc()) {
        $response['pastas'][] = $row;
    }
    $stmt_pastas->close();

    // Buscar Arquivos
    // A lógica de associação de arquivos a pastas precisa ser implementada.
    // Por enquanto, vamos listar todos os arquivos do usuário.
    if (!$pasta_id) { // Apenas na raiz por enquanto
        $sql_arquivos = "SELECT id, nome_original, tipo, tamanho, criado_em FROM arquivos WHERE user_id = ?";
        $stmt_arquivos = $conexao->prepare($sql_arquivos);
        $stmt_arquivos->bind_param('i', $user_id);
        $stmt_arquivos->execute();
        $result_arquivos = $stmt_arquivos->get_result();
        while ($row = $result_arquivos->fetch_assoc()) {
            $response['arquivos'][] = $row;
        }
        $stmt_arquivos->close();
    }


    $conexao->close();
}

echo json_encode($response);
?>
