<?php

require_once '../config/database.php';
require_once '../app/models/User.php';

class AuthController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $emailOrCpf = $_POST['email_or_cpf'];
            $password = $_POST['password'];

            $userModel = new User();
            $user = $userModel->findByEmailOrCpf($emailOrCpf);

            if ($user && password_verify($password, $user['senha'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = $user['tipo'];

                switch ($user['tipo']) {
                    case 'superadmin':
                        header('Location: /superadmin/dashboard');
                        break;
                    case 'gerente':
                        header('Location: /gerente/dashboard');
                        break;
                    case 'cliente':
                        header('Location: /cliente/files');
                        break;
                }
            } else {
                // handle login error
                header('Location: /login?error=1');
            }
        } else {
            require_once '../app/views/auth/login.php';
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /login');
    }
}
