<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'contabilidade');

function conectar() {
    $conexao = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conexao->connect_error) {
        die("Erro de conexão: " . $conexao->connect_error);
    }

    return $conexao;
}
?>
