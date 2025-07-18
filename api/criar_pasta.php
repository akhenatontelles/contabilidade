<?php
session_start();
require_once '../config/db.php';
require_once '../config/logger.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Acesso não autorizado.'];

if (isset($_SESSION['user_id']) && ($_SESSION['user_tipo'] === 'gestor' || $_SESSION['user_tipo'] === 'master')) {
    $data = json_decode(file_get_contents('php://input'), true);

    $nome = $data['nome'] ?? '';
    $cliente_id = $data['cliente_id'] ?? null;
    $pai_id = $data['pai_id'] ?? null;

    if (!empty($nome) && !empty($cliente_id)) {
        $conexao = conectar();

        $sql = "INSERT INTO pastas (user_id, nome, pai_id) VALUES (?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('isi', $cliente_id, $nome, $pai_id);

        if ($stmt->execute()) {
            registrar_log($_SESSION['user_id'], "Criou a pasta '{$nome}' para o cliente ID {$cliente_id}");
            $response = ['success' => true];
        } else {
            $response['message'] = 'Erro ao criar a pasta.';
        }

        $stmt->close();
        $conexao->close();
    } else {
        $response['message'] = 'Nome da pasta ou ID do cliente não especificado.';
    }
}

echo json_encode($response);
?>
