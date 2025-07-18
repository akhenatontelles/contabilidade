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
    // ... (código das rotas do superadmin)
}

// Rotas do Gerente
if ($_SESSION['user_type'] === 'gerente') {
    // ... (código das rotas do gerente)
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
