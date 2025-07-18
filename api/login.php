<?php
session_start();
require_once '../config/db.php';
require_once '../config/logger.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Email ou senha inválidos.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if (!empty($email) && !empty($senha)) {
        $conexao = conectar();

        $sql = "SELECT * FROM users WHERE email = ? AND ativo = 1";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($senha, $user['senha'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_tipo'] = $user['tipo'];

                registrar_log($user['id'], 'Login bem-sucedido');

                $redirect = '';
                switch ($user['tipo']) {
                    case 'master':
                        $redirect = 'master_dashboard.php';
                        break;
                    case 'gestor':
                        $redirect = 'gestor_dashboard.php';
                        break;
                    case 'cliente':
                        $redirect = 'cliente_dashboard.php';
                        break;
                }

                $response = ['success' => true, 'redirect' => $redirect];
            }
        }

        $stmt->close();
        $conexao->close();
    }
}

echo json_encode($response);
?>
