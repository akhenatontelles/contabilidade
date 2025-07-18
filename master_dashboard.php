<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'master') {
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Gestor Master</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Gestor Master</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="gestores-tab" data-bs-toggle="tab" data-bs-target="#gestores" type="button" role="tab">Gestores</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="clientes-tab" data-bs-toggle="tab" data-bs-target="#clientes" type="button" role="tab">Clientes</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="gestores" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Gerenciar Gestores</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGestorModal">
                        <i class="fas fa-plus"></i> Adicionar Gestor
                    </button>
                </div>
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Empresa</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="gestores-lista">
                        <!-- A lista de gestores será inserida aqui via JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="clientes" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h2>Gerenciar Clientes</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClienteModal">
                        <i class="fas fa-plus"></i> Adicionar Cliente
                    </button>
                </div>
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Empresa</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="clientes-lista-master">
                        <!-- A lista de clientes será inserida aqui via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar Gestor -->
    <div class="modal fade" id="addGestorModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Novo Gestor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="add-gestor-form">
                        <!-- Campos do formulário de gestor -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar Cliente -->
    <div class="modal fade" id="addClienteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Novo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="add-cliente-form-master">
                         <div class="mb-3">
                            <label for="nome_cliente" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome_cliente" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="email_cliente" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email_cliente" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="senha_cliente" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senha_cliente" name="senha" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefone_cliente" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="telefone_cliente" name="telefone">
                        </div>
                        <div class="mb-3">
                            <label for="whatsapp_cliente" class="form-label">WhatsApp</label>
                            <input type="text" class="form-control" id="whatsapp_cliente" name="whatsapp">
                        </div>
                        <div class="mb-3">
                            <label for="empresa_cliente" class="form-label">Empresa</label>
                            <input type="text" class="form-control" id="empresa_cliente" name="empresa">
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Ações do Cliente -->
    <div class="modal fade" id="clienteAcoesModalMaster" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clienteNomeModalMaster"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Gerenciamento de arquivos do cliente aqui...</p>
                    <!-- O conteúdo detalhado do file manager será adicionado via JS -->
                    <div id="file-manager-master"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/master.js"></script>
</body>
</html>
