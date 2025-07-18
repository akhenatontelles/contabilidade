<?php
session_start();
require_once '../config/db.php';
require_once '../config/logger.php';

// Habilitar log de erros para depuração
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

file_put_contents('debug_upload.log', "Script acessado em: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
file_put_contents('debug_upload.log', "Sessão: " . print_r($_SESSION, true) . "\n", FILE_APPEND);
file_put_contents('debug_upload.log', "POST data: " . print_r($_POST, true) . "\n", FILE_APPEND);
file_put_contents('debug_upload.log', "FILES data: " . print_r($_FILES, true) . "\n", FILE_APPEND);

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Acesso não autorizado.'];

if (isset($_SESSION['user_id']) && ($_SESSION['user_tipo'] === 'gestor' || $_SESSION['user_tipo'] === 'master')) {
    if (!empty($_FILES['file']) && isset($_POST['cliente_id'])) {
        $cliente_id = $_POST['cliente_id'];
        $file = $_FILES['file'];

        // Lógica para criar o caminho do arquivo com base na pasta pai
        $pasta_id = $_POST['pasta_id'] ?? null;
        $upload_dir = '../uploads/' . $cliente_id . '/';

        // Se uma pasta_id for fornecida, você precisaria de uma lógica para encontrar o caminho completo da subpasta.
        // Por simplicidade, vamos manter o upload na raiz do cliente por enquanto.

        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                 $response['message'] = 'Falha ao criar o diretório de uploads.';
                 file_put_contents('debug_upload.log', "Erro: Falha ao criar o diretório {$upload_dir}\n", FILE_APPEND);
                 echo json_encode($response);
                 exit;
            }
        }

        $file_path = $upload_dir . basename($file['name']);

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            $conexao = conectar();
            $nome_original = $conexao->real_escape_string($file['name']);
            $caminho = $conexao->real_escape_string('uploads/' . $cliente_id . '/' . basename($file['name']));
            $tipo = $file['type'];
            $tamanho = $file['size'];

            $sql = "INSERT INTO arquivos (user_id, nome_original, caminho, tipo, tamanho) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param('isssi', $cliente_id, $nome_original, $caminho, $tipo, $tamanho);

            if ($stmt->execute()) {
                registrar_log($_SESSION['user_id'], "Fez upload do arquivo '{$nome_original}' para o cliente ID {$cliente_id}");
                $response = ['success' => true, 'message' => 'Arquivo enviado com sucesso.'];
                file_put_contents('debug_upload.log', "Sucesso: Arquivo {$nome_original} salvo para o cliente {$cliente_id}\n", FILE_APPEND);
            } else {
                $response['message'] = 'Erro ao salvar informações do arquivo no banco de dados: ' . $stmt->error;
                file_put_contents('debug_upload.log', "Erro DB: " . $stmt->error . "\n", FILE_APPEND);
            }

            $stmt->close();
            $conexao->close();
        } else {
            $response['message'] = 'Erro ao mover o arquivo para o diretório de uploads.';
            file_put_contents('debug_upload.log', "Erro: Falha ao mover o arquivo de {$file['tmp_name']} para {$file_path}\n", FILE_APPEND);
        }
    } else {
        $response['message'] = 'Nenhum arquivo enviado ou ID do cliente não especificado.';
        file_put_contents('debug_upload.log', "Erro: Condição inicial não atendida. FILES ou POST incompletos.\n", FILE_APPEND);
    }
} else {
     $response['message'] = 'Acesso não autorizado ou sessão inválida.';
     file_put_contents('debug_upload.log', "Erro: Acesso não autorizado ou sessão inválida.\n", FILE_APPEND);
}

echo json_encode($response);
?>
