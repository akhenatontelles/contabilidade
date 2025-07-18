<?php

require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/SuperAdminController.php';

$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

session_start();

$authController = new AuthController();
$superAdminController = new SuperAdminController();

// Rotas de autenticação
if ($request_uri === '/login') {
    $authController->login();
    exit;
}

if ($request_uri === '/logout') {
    $authController->logout();
    exit;
}

// Middleware de autenticação e Roteamento principal
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
} else {
    // Rota de Usuário
    require_once '../app/controllers/UserController.php';
    $userController = new UserController();
    if ($request_uri === '/user/theme') {
        $userController->updateTheme();
    }

    // Rotas do SuperAdmin
    if ($_SESSION['user_type'] === 'superadmin') {
        switch ($request_uri) {
            case '/superadmin/dashboard':
                $superAdminController->dashboard();
                break;
            case '/superadmin/users/create':
                $superAdminController->createUser();
                break;
            case '/superadmin/users/delete':
                $superAdminController->deleteUser();
                break;
            case '/superadmin/users/toggle-block':
                $superAdminController->toggleBlockUser();
                break;
            case '/superadmin/users/reset-password':
                $superAdminController->resetPassword();
                break;
        }
    }

    // Rotas do Gerente
    if ($_SESSION['user_type'] === 'gerente') {
        require_once '../app/controllers/GerenteController.php';
        $gerenteController = new GerenteController();
        switch ($request_uri) {
            case '/gerente/dashboard':
                $gerenteController->dashboard();
                break;
            case '/gerente/clients/create':
                $gerenteController->createClient();
                break;
            case '/gerente/clients/delete':
                $gerenteController->deleteClient();
                break;
            case '/gerente/clients/toggle-block':
                $gerenteController->toggleBlockClient();
                break;
            case '/gerente/clients/reset-password':
                $gerenteController->resetClientPassword();
                break;
        }
    }

    // Rotas de Arquivos (para todos os tipos de usuário logados)
    require_once '../app/controllers/FileController.php';
    $fileController = new FileController();
    switch ($request_uri) {
        case '/cliente/files':
            $fileController->index();
            break;
        case '/files/upload':
            if ($_SESSION['user_type'] === 'superadmin' || $_SESSION['user_type'] === 'gerente') {
                $fileController->uploadFile();
            }
            break;
        case '/folders/create':
            if ($_SESSION['user_type'] === 'superadmin' || $_SESSION['user_type'] === 'gerente') {
                $fileController->createFolder();
            }
            break;
        case '/files/rename':
            if ($_SESSION['user_type'] === 'superadmin' || $_SESSION['user_type'] === 'gerente') {
                $fileController->renameFile();
            }
            break;
        case '/files/delete':
            if ($_SESSION['user_type'] === 'superadmin' || $_SESSION['user_type'] === 'gerente') {
                $fileController->deleteFile();
            }
            break;
        case '/folders/rename':
            if ($_SESSION['user_type'] === 'superadmin' || $_SESSION['user_type'] === 'gerente') {
                $fileController->renameFolder();
            }
            break;
        case '/folders/delete':
            if ($_SESSION['user_type'] === 'superadmin' || $_SESSION['user_type'] === 'gerente') {
                $fileController->deleteFolder();
            }
            break;
        case '/folders/download':
            $fileController->downloadFolder();
            break;
    }

    // Rotas de Compartilhamento (para todos os tipos de usuário logados)
    require_once '../app/controllers/ShareController.php';
    $shareController = new ShareController();
    if ($request_uri === '/share/create') {
        $shareController->createLink();
    }
    if (strpos($request_uri, '/share') === 0 && isset($_GET['token'])) {
        $shareController->accessLink();
    }
}
