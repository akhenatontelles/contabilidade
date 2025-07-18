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

// Public share link access
if (strpos($request_uri, '/share') === 0 && isset($_GET['token'])) {
    require_once '../app/controllers/ShareController.php';
    $shareController = new ShareController();
    $shareController->accessLink();
    exit;
}

// Middleware de autenticação
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

// --- Rotas Autenticadas ---

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

// Rotas de Arquivos e Compartilhamento (para todos os usuários logados)
require_once '../app/controllers/FileController.php';
require_once '../app/controllers/ShareController.php';
$fileController = new FileController();
$shareController = new ShareController();

switch ($request_uri) {
    case '/cliente/files':
        $fileController->index();
        break;
    case '/folders/download':
        $fileController->downloadFolder();
        break;
    case '/share/create':
        $shareController->createLink();
        break;
    // Admin-only file actions
    case '/files/upload':
    case '/folders/create':
    case '/files/rename':
    case '/files/delete':
    case '/folders/rename':
    case '/folders/delete':
        if ($_SESSION['user_type'] === 'superadmin' || $_SESSION['user_type'] === 'gerente') {
            // A simple way to call the correct method based on the route
            $method = str_replace('/', '', $request_uri); // e.g., 'files/upload' becomes 'filesupload'
            $method = lcfirst(str_replace(' ', '', ucwords(str_replace('/', ' ', $method)))); // becomes 'filesUpload'
            if (method_exists($fileController, $method)) {
                $fileController->$method();
            }
        }
        break;
}
