<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

$response = ['pastas' => [], 'arquivos' => []];

if (isset($_SESSION['user_id']) && ($_SESSION['user_tipo'] === 'gestor' || $_SESSION['user_tipo'] === 'master')) {
    if (isset($_GET['cliente_id'])) {
        $conexao = conectar();
        $cliente_id = $_GET['cliente_id'];
        $pasta_id = $_GET['pasta_id'] ?? null;

        // Adicionar verificação se o gestor tem permissão para ver este cliente

        // Buscar Pastas
        $sql_pastas = "SELECT id, nome, criado_em FROM pastas WHERE user_id = ? AND pai_id " . ($pasta_id ? "= ?" : "IS NULL");
        $stmt_pastas = $conexao->prepare($sql_pastas);
        if ($pasta_id) {
            $stmt_pastas->bind_param('ii', $cliente_id, $pasta_id);
        } else {
            $stmt_pastas->bind_param('i', $cliente_id);
        }
        $stmt_pastas->execute();
        $result_pastas = $stmt_pastas->get_result();
        while ($row = $result_pastas->fetch_assoc()) {
            $response['pastas'][] = $row;
        }
        $stmt_pastas->close();

        // Buscar Arquivos (mesma lógica do listar_arquivos.php)
        if (!$pasta_id) {
            $sql_arquivos = "SELECT id, nome_original, tipo, tamanho, criado_em FROM arquivos WHERE user_id = ?";
            $stmt_arquivos = $conexao->prepare($sql_arquivos);
            $stmt_arquivos->bind_param('i', $cliente_id);
            $stmt_arquivos->execute();
            $result_arquivos = $stmt_arquivos->get_result();
            while ($row = $result_arquivos->fetch_assoc()) {
                $response['arquivos'][] = $row;
            }
            $stmt_arquivos->close();
        }

        $conexao->close();
    }
}

echo json_encode($response);
?>
