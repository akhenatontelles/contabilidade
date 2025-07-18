<?php

require_once '../app/models/User.php';

class UserController
{
    public function updateTheme()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['theme'])) {
            $userId = $_SESSION['user_id'];
            $theme = $_POST['theme'];

            $userModel = new User();
            $userModel->updateTheme($userId, $theme);

            // Store theme in session as well for immediate effect
            $_SESSION['user_theme'] = $theme;

            // Redirect back or return JSON
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }
}
