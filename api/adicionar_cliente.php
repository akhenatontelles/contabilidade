<?php
session_start();
require_once '../config/db.php';

// Habilitar log de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

file_put_contents('debug_add_cliente.log', "Script acessado em: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
file_put_contents('debug_add_cliente.log', "Sessão: " . print_r($_SESSION, true) . "\n", FILE_APPEND);
file_put_contents('debug_add_cliente.log', "POST data: " . print_r($_POST, true) . "\n", FILE_APPEND);


header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Acesso não autorizado.'];

if (isset($_SESSION['user_id']) && ($_SESSION['user_tipo'] === 'gestor' || $_SESSION['user_tipo'] === 'master')) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $whatsapp = $_POST['whatsapp'] ?? '';
        $empresa = $_POST['empresa'] ?? '';

        if (!empty($nome) && !empty($email) && !empty($senha)) {
            $conexao = conectar();
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $tipo = 'cliente';
            $criado_por = $_SESSION['user_id'];

            $sql = "INSERT INTO users (nome, email, senha, telefone, whatsapp, empresa, tipo, criado_por) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexao->prepare($sql);
            if (!$stmt) {
                $error_message = "Erro na preparação da query: " . $conexao->error;
                file_put_contents('debug_add_cliente.log', $error_message . "\n", FILE_APPEND);
                $response['message'] = $error_message;
                echo json_encode($response);
                exit;
            }

            $stmt->bind_param('sssssssi', $nome, $email, $senha_hash, $telefone, $whatsapp, $empresa, $tipo, $criado_por);

            if ($stmt->execute()) {
                $response = ['success' => true];
                file_put_contents('debug_add_cliente.log', "Cliente '{$nome}' cadastrado com sucesso.\n", FILE_APPEND);
            } else {
                $error_message = 'Erro ao cadastrar o cliente: ' . $stmt->error;
                file_put_contents('debug_add_cliente.log', $error_message . "\n", FILE_APPEND);
                $response['message'] = $error_message;
            }

            $stmt->close();
            $conexao->close();
        } else {
            $response['message'] = 'Por favor, preencha todos os campos obrigatórios.';
            file_put_contents('debug_add_cliente.log', "Falha: Campos obrigatórios não preenchidos.\n", FILE_APPEND);
        }
    }
} else {
    file_put_contents('debug_add_cliente.log', "Falha: Acesso não autorizado ou sessão inválida.\n", FILE_APPEND);
}

echo json_encode($response);
?>
