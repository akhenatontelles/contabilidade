<?php
session_start();
// require_once '../vendor/autoload.php'; // Descomente após rodar composer install
require_once '../config/db.php';

// use PHPMailer\PHPMailer\PHPMailer; // Descomente após rodar composer install
// use PHPMailer\PHPMailer\Exception; // Descomente após rodar composer install

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Acesso não autorizado.'];

if (isset($_SESSION['user_id'])) {
    $data = json_decode(file_get_contents('php://input'), true);
    $arquivo_id = $data['arquivo_id'] ?? null;
    $email_destinatario = $data['email'] ?? '';

    if ($arquivo_id && filter_var($email_destinatario, FILTER_VALIDATE_EMAIL)) {
        $conexao = conectar();
        // Lógica para gerar um link de compartilhamento seguro (token temporário)
        $token = bin2hex(random_bytes(16));
        // Salvar o token no banco associado ao arquivo e com um tempo de expiração

        $link_compartilhamento = "http://seusite.com/download_compartilhado.php?token=$token";

        // $mail = new PHPMailer(true); // Descomente após rodar composer install

        try {
            // Configurações do servidor de e-mail (exemplo com SMTP do Gmail)
            /*
            $mail->isSMTP();
            $mail->Host = 'smtp.example.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'seu_email@example.com';
            $mail->Password = 'sua_senha';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Remetente e destinatário
            $mail->setFrom('no-reply@contabilidade.com', 'Sistema de Contabilidade');
            $mail->addAddress($email_destinatario);

            // Conteúdo do e-mail
            $mail->isHTML(true);
            $mail->Subject = 'Compartilhamento de Arquivo';
            $mail->Body    = "Olá,<br><br>Um arquivo foi compartilhado com você. Clique no link abaixo para fazer o download:<br><br><a href='$link_compartilhamento'>$link_compartilhamento</a><br><br>Atenciosamente,<br>Sistema de Contabilidade";
            $mail->AltBody = "Olá,\n\nUm arquivo foi compartilhado com você. Copie e cole o link abaixo no seu navegador para fazer o download:\n\n$link_compartilhamento\n\nAtenciosamente,\nSistema de Contabilidade";

            $mail->send();
            */
            $response = ['success' => true, 'message' => 'E-mail de compartilhamento enviado com sucesso (simulado).'];
        } catch (Exception $e) {
            // $response['message'] = "O e-mail não pôde ser enviado. Mailer Error: {$mail->ErrorInfo}"; // Descomente após rodar composer install
            $response['message'] = "O e-mail não pôde ser enviado (simulado).";
        }

        $conexao->close();
    } else {
        $response['message'] = 'ID do arquivo ou e-mail inválido.';
    }
}

echo json_encode($response);
?>
