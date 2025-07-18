<?php

require_once '../app/models/User.php';

class GerenteController
{
    public function dashboard()
    {
        // ... (código existente)
    }

    public function createClient()
    {
        // ... (código existente)
    }

    public function deleteClient()
    {
        $clientId = $_GET['id'];
        $userModel = new User();
        // Security check: ensure the client belongs to the manager
        $client = $userModel->findById($clientId);
        if ($client && $client['manager_id'] == $_SESSION['user_id']) {
            $userModel->delete($clientId);
        }
        header('Location: /gerente/dashboard');
    }

    public function toggleBlockClient()
    {
        $clientId = $_GET['id'];
        $userModel = new User();
        // Security check
        $client = $userModel->findById($clientId);
        if ($client && $client['manager_id'] == $_SESSION['user_id']) {
            $newStatus = $client['status'] === 'ativo' ? 'bloqueado' : 'ativo';
            $userModel->updateStatus($clientId, $newStatus);
        }
        header('Location: /gerente/dashboard');
    }

    public function resetClientPassword()
    {
        $clientId = $_GET['id'];
        $userModel = new User();
        // Security check
        $client = $userModel->findById($clientId);
        if ($client && $client['manager_id'] == $_SESSION['user_id']) {
            $newPassword = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1,10))),1,10);
            $userModel->updatePassword($clientId, $newPassword);
        }
        header('Location: /gerente/dashboard');
    }
}
