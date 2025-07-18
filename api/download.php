<?php
session_start();
require_once '../config/db.php';

if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
    $conexao = conectar();
    $user_id = $_SESSION['user_id'];
    $arquivo_id = $_GET['id'];

    $sql = "SELECT * FROM arquivos WHERE id = ? AND user_id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('ii', $arquivo_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $arquivo = $result->fetch_assoc();
        $caminho_arquivo = '../' . $arquivo['caminho'];

        if (file_exists($caminho_arquivo)) {
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $arquivo['tipo']);
            header('Content-Disposition: attachment; filename="' . basename($arquivo['nome_original']) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($caminho_arquivo));
            readfile($caminho_arquivo);
            exit;
        }
    }

    $stmt->close();
    $conexao->close();
}

http_response_code(404);
echo 'Arquivo não encontrado.';
?>
