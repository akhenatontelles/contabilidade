<?php

require_once '../config/database.php';

class SharedLink
{
    private $conn;

    public function __construct()
    {
        $this->conn = connect();
    }

    public function create($data)
    {
        $sql = "INSERT INTO shared_links (file_id, folder_id, tipo, token) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iiss', $data['file_id'], $data['folder_id'], $data['tipo'], $data['token']);
        return $stmt->execute();
    }

    public function findByToken($token)
    {
        $sql = "SELECT * FROM shared_links WHERE token = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
