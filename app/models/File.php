<?php

require_once '../config/database.php';

class File
{
    private $conn;

    public function __construct()
    {
        $this->conn = connect();
    }

    public function findByUserAndFolder($userId, $folderId)
    {
        // ... (código existente)
    }

    public function create($data)
    {
        // ... (código existente)
    }

    public function rename($id, $newName)
    {
        $sql = "UPDATE files SET nome = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $newName, $id);
        return $stmt->execute();
    }

    public function delete($id)
    {
        // First, get the file path to delete the actual file
        $file = $this->findById($id);
        if ($file) {
            unlink($file['path']);
            $sql = "DELETE FROM files WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('i', $id);
            return $stmt->execute();
        }
        return false;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM files WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
