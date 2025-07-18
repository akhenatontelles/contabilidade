<?php
require_once 'config/db.php';

$conexao = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Criar o banco de dados
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conexao->query($sql) === TRUE) {
    echo "Banco de dados criado com sucesso ou já existente.<br>";
} else {
    echo "Erro ao criar o banco de dados: " . $conexao->error . "<br>";
}

$conexao->select_db(DB_NAME);

// Criar a tabela de usuários
$sql = "CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  senha VARCHAR(255),
  telefone VARCHAR(20),
  whatsapp VARCHAR(20),
  empresa VARCHAR(100),
  tipo ENUM('master', 'gestor', 'cliente') DEFAULT 'cliente',
  criado_por INT NULL,
  ativo BOOLEAN DEFAULT 1,
  FOREIGN KEY (criado_por) REFERENCES users(id)
)";

if ($conexao->query($sql) === TRUE) {
    echo "Tabela 'users' criada com sucesso.<br>";
} else {
    echo "Erro ao criar a tabela 'users': " . $conexao->error . "<br>";
}

// Criar a tabela de arquivos
$sql = "CREATE TABLE IF NOT EXISTS arquivos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  nome_original VARCHAR(255),
  caminho VARCHAR(255),
  tipo VARCHAR(100),
  tamanho BIGINT,
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conexao->query($sql) === TRUE) {
    echo "Tabela 'arquivos' criada com sucesso.<br>";
} else {
    echo "Erro ao criar a tabela 'arquivos': " . $conexao->error . "<br>";
}

// Criar a tabela de pastas
$sql = "CREATE TABLE IF NOT EXISTS pastas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  nome VARCHAR(255),
  pai_id INT NULL,
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (pai_id) REFERENCES pastas(id) ON DELETE CASCADE
)";

if ($conexao->query($sql) === TRUE) {
    echo "Tabela 'pastas' criada com sucesso.<br>";
} else {
    echo "Erro ao criar a tabela 'pastas': " . $conexao->error . "<br>";
}

// Inserir usuário master
$senha_master = password_hash('master123', PASSWORD_DEFAULT);
$sql = "INSERT INTO users (nome, email, senha, tipo) VALUES ('Gestor Master', 'master@contabilidade.com', '$senha_master', 'master')";

if ($conexao->query($sql) === TRUE) {
    echo "Usuário master inserido com sucesso.<br>";
} else {
    echo "Erro ao inserir o usuário master: " . $conexao->error . "<br>";
}


$conexao->close();
?>
