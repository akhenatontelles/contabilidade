<?php

// Inclui todos os controllers necessários
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/SuperAdminController.php';
require_once '../app/controllers/GerenteController.php';
require_once '../app/controllers/FileController.php';
require_once '../app/controllers/ShareController.php';
require_once '../app/controllers/UserController.php';

// Inicia a sessão
session_start();

// Pega a URI sem a query string
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

// Instancia os controllers
$authController = new AuthController();
$superAdminController = new SuperAdminController();
$gerenteController = new GerenteController();
$fileController = new FileController();
$shareController = new ShareController();
$userController = new UserController();

// --- Roteamento ---

// 1. Rotas Públicas (acessíveis sem login)
if ($request_uri === '/login') {
    $authController->login();
    exit;
}
if ($request_uri === '/logout') {
    $authController->logout();
    exit;
}
if (strpos($request_uri, '/share') === 0 && isset($_GET['token'])) {
    $shareController->accessLink();
    exit;
}

// 2. Middleware de Autenticação (daqui para baixo, todas as rotas exigem login)
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

// 3. Rotas Autenticadas
$user_type = $_SESSION['user_type'];

// Rotas específicas por tipo de usuário
if ($user_type === 'superadmin') {
    switch ($request_uri) {
        case '/superadmin/dashboard': $superAdminController->dashboard(); break;
        case '/superadmin/users/create': $superAdminController->createUser(); break;
        case '/superadmin/users/delete': $superAdminController->deleteUser(); break;
        case '/superadmin/users/toggle-block': $superAdminController->toggleBlockUser(); break;
        case '/superadmin/users/reset-password': $superAdminController->resetPassword(); break;
    }
}

if ($user_type === 'gerente') {
    switch ($request_uri) {
        case '/gerente/dashboard': $gerenteController->dashboard(); break;
        case '/gerente/clients/create': $gerenteController->createClient(); break;
        case '/gerente/clients/delete': $gerenteController->deleteClient(); break;
        case '/gerente/clients/toggle-block': $gerenteController->toggleBlockClient(); break;
        case '/gerente/clients/reset-password': $gerenteController->resetClientPassword(); break;
    }
}

// Rotas comuns para usuários logados
switch ($request_uri) {
    case '/user/theme': $userController->updateTheme(); break;
    case '/cliente/files': $fileController->index(); break;
    case '/folders/download': $fileController->downloadFolder(); break;
    case '/share/create': $shareController->createLink(); break;
}

// Rotas de gerenciamento de arquivos (apenas para admin/gerente)
if ($user_type === 'superadmin' || $user_type === 'gerente') {
    switch ($request_uri) {
        case '/files/upload': $fileController->uploadFile(); break;
        case '/folders/create': $fileController->createFolder(); break;
        case '/files/rename': $fileController->renameFile(); break;
        case '/files/delete': $fileController->deleteFile(); break;
        case '/folders/rename': $fileController->renameFolder(); break;
        case '/folders/delete': $fileController->deleteFolder(); break;
    }
}

// Se nenhuma rota corresponder, pode-se adicionar um fallback, como um erro 404
// Ex: require_once '../app/views/errors/404.php';
