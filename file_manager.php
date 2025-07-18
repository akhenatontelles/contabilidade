<?php
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_tipo'], ['master', 'gestor'])) {
    header('Location: index.html');
    exit;
}

if (!isset($_GET['cliente_id'])) {
    echo "ID do cliente não especificado.";
    exit;
}

$cliente_id = $_GET['cliente_id'];

require_once 'config/db.php';
$conexao = conectar();
$query = "SELECT nome FROM users WHERE id = ?";
$stmt = $conexao->prepare($query);
$stmt->bind_param('i', $cliente_id);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();
$nome_cliente = $cliente['nome'] ?? 'Cliente não encontrado';
$stmt->close();
$conexao->close();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Arquivos - <?php echo htmlspecialchars($nome_cliente); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <link rel="stylesheet" href="assets/css/file_manager.css">
</head>
<body>
    <div class="sidebar">
        <h3 class="text-white text-center mt-3">Contabilidade</h3>
        <ul class="nav flex-column mt-4">
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <i class="fas fa-cloud"></i> Drive do Cliente
                </a>
            </li>
        </ul>
        <div class="user-area">
            <a href="#" class="nav-link">
                <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['user_tipo']); ?>
            </a>
        </div>
    </div>
    <div class="main-content" id="main-content-dropzone">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <div class="d-flex align-items-center">
                    <input class="form-control me-2" type="search" placeholder="Buscar em Drive do Cliente" id="search-input">
                </div>
                <div class="d-flex align-items-center">
                    <div class="form-check form-switch me-3">
                        <input class="form-check-input" type="checkbox" id="theme-switch">
                        <label class="form-check-label" for="theme-switch"><i class="fas fa-moon"></i></label>
                    </div>
                     <div class="btn-group me-2">
                        <button type="button" class="btn btn-primary" id="upload-btn"><i class="fas fa-upload"></i> Upload</button>
                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" id="upload-folder-btn">Upload de Pasta</a></li>
                        </ul>
                    </div>
                     <button class="btn btn-outline-secondary" id="new-folder-btn"><i class="fas fa-folder-plus"></i> Nova Pasta</button>
                </div>
            </div>
        </nav>
        <div class="container-fluid mt-4">
            <div id="file-manager">
                <div class="path-bar mb-3">
                    <span id="current-path">Drive do Cliente</span>
                </div>
                <div class="table-responsive">
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
             <div class="dz-message dropzone-message">
                <i class="fas fa-cloud-upload-alt"></i>
                <h3>Arraste e solte arquivos aqui para fazer o upload</h3>
            </div>
        </div>
    </div>

    <!-- Input oculto para upload de pastas -->
    <input type="file" id="folder-upload-input" webkitdirectory directory multiple style="display: none;" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script>
        // Passar o ID do cliente para o JavaScript
        const CLIENTE_ID = <?php echo json_encode($cliente_id); ?>;
    </script>
    <script src="assets/js/file_manager.js"></script>
</body>
</html>
