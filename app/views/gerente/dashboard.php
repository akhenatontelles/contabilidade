<!DOCTYPE html>
<html>
<head>
    <title>Teste Gerente</title>
</head>
<body>
    <h1>Teste Gerente</h1>
    <a href="/gerente/clients/create">Cadastrar Novo Cliente</a>
    <hr>
    <h2>Meus Clientes</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?php echo $client['id']; ?></td>
                    <td><?php echo $client['nome']; ?></td>
                    <td><?php echo $client['email']; ?></td>
                    <td><?php echo $client['status']; ?></td>
                    <td>
                        <a href="/gerente/clients/delete?id=<?php echo $client['id']; ?>" onclick="return confirm('Tem certeza?');">Excluir</a> |
                        <a href="/gerente/clients/toggle-block?id=<?php echo $client['id']; ?>">Mudar Status</a> |
                        <a href="/gerente/clients/reset-password?id=<?php echo $client['id']; ?>" onclick="return confirm('Isso irá gerar e salvar uma nova senha. Deseja continuar?');">Resetar Senha</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
