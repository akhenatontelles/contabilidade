<?php
session_start();
require_once '../config/db.php';
require_once '../config/logger.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Acesso não autorizado.'];

if (isset($_SESSION['user_id']) && ($_SESSION['user_tipo'] === 'gestor' || $_SESSION['user_tipo'] === 'master')) {
    if (!empty($_FILES['file']) && isset($_POST['cliente_id'])) {
        $cliente_id = $_POST['cliente_id'];
        $file = $_FILES['file'];

        $upload_dir = '../uploads/' . $cliente_id . '/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_path = $upload_dir . basename($file['name']);

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            $conexao = conectar();
            $nome_original = $file['name'];
            $caminho = 'uploads/' . $cliente_id . '/' . basename($file['name']);
            $tipo = $file['type'];
            $tamanho = $file['size'];

            $sql = "INSERT INTO arquivos (user_id, nome_original, caminho, tipo, tamanho) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param('isssi', $cliente_id, $nome_original, $caminho, $tipo, $tamanho);

            if ($stmt->execute()) {
                registrar_log($_SESSION['user_id'], "Fez upload do arquivo '{$nome_original}' para o cliente ID {$cliente_id}");
                $response = ['success' => true, 'message' => 'Arquivo enviado com sucesso.'];
            } else {
                $response['message'] = 'Erro ao salvar informações do arquivo no banco de dados.';
            }

            $stmt->close();
            $conexao->close();
        } else {
            $response['message'] = 'Erro ao mover o arquivo para o diretório de uploads.';
        }
    } else {
        $response['message'] = 'Nenhum arquivo enviado ou ID do cliente não especificado.';
    }
}

echo json_encode($response);
?>
