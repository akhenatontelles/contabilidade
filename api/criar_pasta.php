<?php
session_start();
require_once '../config/db.php';
require_once '../config/logger.php';

// Habilitar log de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

file_put_contents('debug_criar_pasta.log', "Script acessado em: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
file_put_contents('debug_criar_pasta.log', "Sessão: " . print_r($_SESSION, true) . "\n", FILE_APPEND);

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Acesso não autorizado.'];

if (isset($_SESSION['user_id']) && ($_SESSION['user_tipo'] === 'gestor' || $_SESSION['user_tipo'] === 'master')) {
    $data = json_decode(file_get_contents('php://input'), true);
    file_put_contents('debug_criar_pasta.log', "Dados recebidos: " . print_r($data, true) . "\n", FILE_APPEND);

    $nome = $data['nome'] ?? '';
    $cliente_id = $data['cliente_id'] ?? null;
    $pai_id = $data['pai_id'] ?? null;

    if (!empty($nome) && !empty($cliente_id)) {
        $conexao = conectar();

        $sql = "INSERT INTO pastas (user_id, nome, pai_id) VALUES (?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        if (!$stmt) {
            $error_message = "Erro na preparação da query: " . $conexao->error;
            file_put_contents('debug_criar_pasta.log', $error_message . "\n", FILE_APPEND);
            $response['message'] = $error_message;
            echo json_encode($response);
            exit;
        }

        $stmt->bind_param('isi', $cliente_id, $nome, $pai_id);

        if ($stmt->execute()) {
            registrar_log($_SESSION['user_id'], "Criou a pasta '{$nome}' para o cliente ID {$cliente_id}");
            $response = ['success' => true];
            file_put_contents('debug_criar_pasta.log', "Pasta '{$nome}' criada com sucesso para o cliente {$cliente_id}.\n", FILE_APPEND);
        } else {
            $error_message = 'Erro ao criar a pasta: ' . $stmt->error;
            file_put_contents('debug_criar_pasta.log', $error_message . "\n", FILE_APPEND);
            $response['message'] = $error_message;
        }

        $stmt->close();
        $conexao->close();
    } else {
        $response['message'] = 'Nome da pasta ou ID do cliente não especificado.';
        file_put_contents('debug_criar_pasta.log', "Falha: Nome da pasta ou ID do cliente não especificado.\n", FILE_APPEND);
    }
} else {
    file_put_contents('debug_criar_pasta.log', "Falha: Acesso não autorizado ou sessão inválida.\n", FILE_APPEND);
}

echo json_encode($response);
?>
