<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'cliente') {
    header('Location: index.html');
    exit;
}

require_once 'config/db.php';
$conexao = conectar();
$user_id = $_SESSION['user_id'];
$query = "SELECT nome FROM users WHERE id = ?";
$stmt = $conexao->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$nome_usuario = $user['nome'];
$stmt->close();
$conexao->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Cliente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/cliente.css">
</head>
<body>
    <div class="sidebar">
        <h3 class="text-white text-center mt-3">Contabilidade</h3>
        <ul class="nav flex-column mt-4">
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <i class="fas fa-cloud"></i> Meu Drive
                </a>
            </li>
        </ul>
        <div class="logout-area">
            <a href="logout.php" class="nav-link">
                <i class="fas fa-sign-out-alt"></i> Sair
            </a>
        </div>
    </div>
    <div class="main-content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <input class="form-control me-2" type="search" placeholder="Buscar em Meu Drive" id="search-input">
                </div>
                <div class="d-flex align-items-center">
                    <span class="navbar-text me-3">
                        Bem-vindo(a), <?php echo htmlspecialchars($nome_usuario); ?>
                    </span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="theme-switch">
                        <label class="form-check-label" for="theme-switch"><i class="fas fa-moon"></i></label>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container-fluid mt-4">
            <div id="file-manager">
                <div class="path-bar mb-3">
                    <span id="current-path">Meu Drive</span>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tamanho</th>
                            <th>Última modificação</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="file-list">
                        <!-- Arquivos e pastas serão listados aqui -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/cliente.js"></script>
</body>
</html>
