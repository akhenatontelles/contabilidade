<?php

require_once '../config/database.php';

class Folder
{
    private $conn;

    public function __construct()
    {
        $this->conn = connect();
    }

    public function findByUserAndParent($userId, $parentId)
    {
        // ... (código existente)
    }

    public function create($data)
    {
        // ... (código existente)
    }

    public function rename($id, $newName)
    {
        $sql = "UPDATE folders SET nome = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $newName, $id);
        return $stmt->execute();
    }

    public function delete($id)
    {
        // ... (código existente)
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM folders WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
