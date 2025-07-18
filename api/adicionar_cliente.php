<?php
session_start();
require_once '../config/db.php';

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
            $stmt->bind_param('sssssssi', $nome, $email, $senha_hash, $telefone, $whatsapp, $empresa, $tipo, $criado_por);

            if ($stmt->execute()) {
                $response = ['success' => true];
            } else {
                $response['message'] = 'Erro ao cadastrar o cliente.';
            }

            $stmt->close();
            $conexao->close();
        } else {
            $response['message'] = 'Por favor, preencha todos os campos obrigatórios.';
        }
    }
}

echo json_encode($response);
?>
