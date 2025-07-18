<?php
function registrar_log($user_id, $acao) {
    $conexao = conectar(); // Reutiliza a função de conexão do db.php
    $ip_address = $_SERVER['REMOTE_ADDR'];

    $sql = "INSERT INTO logs (user_id, acao, ip_address) VALUES (?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('iss', $user_id, $acao, $ip_address);
    $stmt->execute();
    $stmt->close();
    $conexao->close();
}
?>
