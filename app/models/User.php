<?php

require_once '../config/database.php';

class User
{
    private $conn;

    public function __construct()
    {
        $this->conn = connect();
    }

    public function findByEmailOrCpf($emailOrCpf)
    {
        $sql = "SELECT * FROM users WHERE email = ? OR nome = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $emailOrCpf, $emailOrCpf);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($data)
    {
        $hashedPassword = password_hash($data['senha'], PASSWORD_DEFAULT);
        $managerId = isset($data['manager_id']) ? $data['manager_id'] : null;
        $sql = "INSERT INTO users (nome, email, senha, tipo, telefone, whatsapp, nome_empresa, manager_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sssssssi', $data['nome'], $data['email'], $hashedPassword, $data['tipo'], $data['telefone'], $data['whatsapp'], $data['nome_empresa'], $managerId);
        return $stmt->execute();
    }

    public function findClientsByManagerId($managerId)
    {
        $sql = "SELECT * FROM users WHERE manager_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $managerId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findAll()
    {
        // ... (código existente)
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE users SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $status, $id);
        return $stmt->execute();
    }

    public function updatePassword($id, $password)
    {
        // ... (código existente)
    }

    public function updateTheme($id, $theme)
    {
        $sql = "UPDATE users SET theme = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $theme, $id);
        return $stmt->execute();
    }
}
