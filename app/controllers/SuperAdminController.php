<?php

require_once '../app/models/User.php';

class SuperAdminController
{
    public function dashboard()
    {
        // ... (código existente)
    }

    public function createUser()
    {
        // ... (código existente)
    }

    public function deleteUser()
    {
        $userId = $_GET['id'];
        $userModel = new User();
        $userModel->delete($userId);
        header('Location: /superadmin/dashboard');
    }

    public function toggleBlockUser()
    {
        $userId = $_GET['id'];
        $userModel = new User();
        $user = $userModel->findById($userId);
        $newStatus = $user['status'] === 'ativo' ? 'bloqueado' : 'ativo';
        $userModel->updateStatus($userId, $newStatus);
        header('Location: /superadmin/dashboard');
    }

    public function resetPassword()
    {
        $userId = $_GET['id'];
        // For simplicity, generating a new random password.
        // In a real application, you might want to send a reset link via email.
        $newPassword = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', mt_rand(1,10))),1,10);
        $userModel = new User();
        $userModel->updatePassword($userId, $newPassword);

        // You should probably show the new password to the admin.
        // For now, just redirecting.
        header('Location: /superadmin/dashboard');
    }
}
